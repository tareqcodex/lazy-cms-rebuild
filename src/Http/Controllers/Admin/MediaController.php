<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Acme\CmsDashboard\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::latest()->paginate(40);
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json($media);
        }
        return view('cms-dashboard::admin.media.index', compact('media'));
    }

    public function create()
    {
        return view('cms-dashboard::admin.media.create');
    }

    public function store(Request $request)
    {
        // Step 1: Validate
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $file = $request->file('file');
        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'File invalid or PHP upload error: ' . $file->getError()
            ], 422);
        }

        $originalName = $file->getClientOriginalName();
        $originalSize = (int) $file->getSize();
        $mimeType     = $file->getMimeType();

        // Step 2: Ensure directory exists
        $diskRoot = storage_path('app/public');
        if (!is_dir($diskRoot . '/media')) {
            mkdir($diskRoot . '/media', 0755, true);
        }

        // Step 3: Save original file immediately
        $filename      = pathinfo($originalName, PATHINFO_FILENAME);
        $extension     = strtolower($file->getClientOriginalExtension());
        $finalFilename = Str::slug($filename) . '-' . time() . '.' . $extension;
        $path          = 'media/' . $finalFilename;
        $fullPath      = $diskRoot . '/media/' . $finalFilename;

        // Use move() for reliability (works better than storeAs on Windows/Laragon)
        $file->move($diskRoot . '/media', $finalFilename);

        if (!file_exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File move failed. Check storage/app/public/media permissions.'
            ], 500);
        }

        $compressedSize = $originalSize;
        $wasCompressed  = false;

        // Step 4: GD Compression (safe, won't affect upload success)
        try {
            @ini_set('memory_limit', '256M');
            @set_time_limit(60);

            if (in_array($mimeType, ['image/jpeg', 'image/jpg'])) {
                $image = @imagecreatefromjpeg($fullPath);
                if ($image !== false) {
                    imageinterlace($image, 1);
                    imagejpeg($image, $fullPath, 60);
                    imagedestroy($image);
                    clearstatcache(true, $fullPath);
                    $newSize = (int) @filesize($fullPath);
                    if ($newSize > 0 && $newSize < $originalSize) {
                        $compressedSize = $newSize;
                        $wasCompressed  = true;
                    }
                }
            } elseif ($mimeType === 'image/png') {
                $image = @imagecreatefrompng($fullPath);
                if ($image !== false) {
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagepng($image, $fullPath, 7);
                    imagedestroy($image);
                    clearstatcache(true, $fullPath);
                    $newSize = (int) @filesize($fullPath);
                    if ($newSize > 0 && $newSize < $originalSize) {
                        $compressedSize = $newSize;
                        $wasCompressed  = true;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Compression failed — original file is safely saved above, we still continue
        }

        // Step 5: Save to DB
        $media = Media::create([
            'filename'        => $originalName,
            'path'            => $path,
            'mime_type'       => $mimeType,
            'original_size'   => $originalSize,
            'compressed_size' => $compressedSize,
            'title'           => $filename,
            'user_id'         => auth()->id(),
        ]);

        return response()->json([
            'success'         => true,
            'media'           => $media,
            'url'             => asset('storage/' . $path),
            'was_compressed'  => $wasCompressed,
            'original_size'   => $this->formatBytes($originalSize),
            'compressed_size' => $this->formatBytes($compressedSize),
        ]);
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'alt_text'    => 'nullable|string',
            'title'       => 'nullable|string',
            'caption'     => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $media->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();
        return response()->json(['success' => true]);
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
