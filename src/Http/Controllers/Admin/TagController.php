<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Acme\CmsDashboard\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $query = Tag::withCount('posts')->orderBy('name');

        if ($request->has('s')) {
            $query->where('name', 'like', '%' . $request->s . '%')
                  ->orWhere('description', 'like', '%' . $request->s . '%');
        }

        $tags = $query->paginate(10);
        return view('cms-dashboard::admin.tags.index', compact('tags'));
    }

    public function bulk(Request $request)
    {
        $action = $request->input('action') !== '-1' ? $request->input('action') : $request->input('action2');
        $ids = $request->input('ids');

        if (($action === 'delete') && !empty($ids)) {
            Tag::whereIn('id', $ids)->delete();
            return back()->with('success', 'Selected tags deleted.');
        }

        return back()->with('error', 'Please select an action and at least one item.');
    }

    public function store(Request $request)
    {
        $baseSlug = $request->slug ?: $request->name;
        $request->merge(['slug' => Tag::generateUniqueSlug($baseSlug)]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Tag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag added.');
    }

    public function edit(Tag $tag)
    {
        return view('cms-dashboard::admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $baseSlug = $request->slug ?: $request->name;
        $request->merge(['slug' => Tag::generateUniqueSlug($baseSlug, $tag->id)]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted.');
    }
    public function ajax(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $slug = Tag::generateUniqueSlug($request->name);
        $tag = Tag::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);
        return response()->json($tag);
    }
}
