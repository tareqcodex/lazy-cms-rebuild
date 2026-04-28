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
        $query = Media::query();

        // Search filter
        if ($request->filled('s')) {
            $search = $request->s;
            $query->where(function($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type') && $request->type !== 'all') {
            if ($request->type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($request->type === 'video') {
                $query->where('mime_type', 'like', 'video/%');
            } elseif ($request->type === 'pdf') {
                $query->where('mime_type', 'application/pdf');
            }
        }

        // Date filter (Format: Ym, e.g., 202404)
        if ($request->filled('m')) {
            $year = substr($request->m, 0, 4);
            $month = substr($request->m, 4, 2);
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        }

        $media = $query->latest()->paginate(10)->appends($request->all());

        // Get unique months for filter dropdown
        $months = Media::selectRaw('DISTINCT DATE_FORMAT(created_at, "%Y%m") as month_val, DATE_FORMAT(created_at, "%M %Y") as month_label')
            ->orderBy('month_val', 'desc')
            ->get();

        // Get existing types for filter dropdown
        $hasImages = Media::where('mime_type', 'like', 'image/%')->exists();
        $hasVideos = Media::where('mime_type', 'like', 'video/%')->exists();
        $hasPdfs = Media::where('mime_type', 'application/pdf')->exists();

        $types = [];
        if ($hasImages) $types['image'] = 'Images';
        if ($hasVideos) $types['video'] = 'Video';
        if ($hasPdfs) $types['pdf'] = 'PDF';

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json($media);
        }
        return view('cms-dashboard::admin.media.index', compact('media', 'months', 'types'));
    }

    public function create()
    {
        return view('cms-dashboard::admin.media.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:51200',
            ]);

            $file = $request->file('file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            
            $filename = Str::slug($originalName) . '-' . time() . '.' . $extension;
            $path = $file->storeAs('media', $filename, 'public');

            $media = Media::create([
                'title' => $originalName,
                'filename' => $filename,
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'original_size' => $file->getSize(),
                'compressed_size' => $file->getSize(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $media
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 422);
        }

        $mediaItems = Media::whereIn('id', $ids)->get();
        foreach ($mediaItems as $item) {
            Storage::disk('public')->delete($item->path);
            $item->delete();
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Media $media)
    {
        $oldTitle = $media->title;
        $newTitle = $request->input('title');

        $media->alt_text = $request->input('alt_text');
        $media->title = $newTitle;
        $media->caption = $request->input('caption');
        $media->description = $request->input('description');

        // Rename file if title changed and it's not empty
        if ($newTitle && $newTitle !== $oldTitle) {
            $extension = pathinfo($media->path, PATHINFO_EXTENSION);
            $slug = \Illuminate\Support\Str::slug($newTitle);
            
            // Generate unique filename
            $newFilename = $slug . '.' . $extension;
            $newPath = 'media/' . $newFilename;

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($newPath)) {
                $newFilename = $slug . '-' . time() . '.' . $extension;
                $newPath = 'media/' . $newFilename;
            }

            // Move the file
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($media->path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->move($media->path, $newPath);
                $media->path = $newPath;
                $media->filename = $newFilename;
            }
        }

        $media->save();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
            'success' => true,
            'data' => $media
        ]);
        }

        return redirect()->back()->with('success', 'Media updated successfully');
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
