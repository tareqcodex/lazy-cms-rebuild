<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    protected function generateUniqueSlug($title, $id = 0)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
        while (Page::withTrashed()->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        return $slug;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Page::query();

        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            $query->withoutTrashed();
            if ($status) {
                $query->where('status', $status);
            }
        }

        // Search
        if ($request->filled('s')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->s . '%')
                  ->orWhere('content', 'like', '%' . $request->s . '%');
            });
        }

        // Date Filter
        if ($request->filled('m') && $request->m != '-1') {
            $year = substr($request->m, 0, 4);
            $month = substr($request->m, 4, 2);
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }

        $pages = $query->latest()->paginate(10)->withQueryString();

        $dates = Page::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        $allCount = Page::count();
        $publishedCount = Page::where('status', 'published')->count();
        $draftCount = Page::where('status', 'draft')->count();
        $trashCount = Page::onlyTrashed()->count();

        return view('cms-dashboard::admin.pages.index', compact('pages', 'dates', 'allCount', 'publishedCount', 'draftCount', 'trashCount'));
    }

    public function create()
    {
        $allPages = Page::orderBy('title')->get();
        
        // Fetch applicable custom field groups for pages
        $fieldGroups = \Acme\CmsDashboard\Models\FieldGroup::where('is_active', true)
            ->where(function($q) {
                $q->whereJsonContains('rules->post_type', 'page');
            })
            ->with('fields')
            ->orderBy('order')
            ->get();

        return view('cms-dashboard::admin.pages.create', compact('allPages', 'fieldGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,scheduled',
            'parent_id' => 'nullable|exists:posts,id',
            'menu_order' => 'nullable|integer',
            'template' => 'nullable|string',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable',
            'editor_type' => 'nullable|string|in:rich,builder',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        } elseif ($request->filled('featured_image')) {
            $validated['featured_image'] = $request->input('featured_image');
        }

        if (empty($validated['template']) || $validated['template'] === 'default') {
            $validated['template'] = 'site-width';
        }

        $page = Page::create($validated);

        // Save Custom Fields
        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $fieldId => $value) {
                DB::table('post_custom_field_values')->insert([
                    'post_id' => $page->id,
                    'field_id' => $fieldId,
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'created_at' => now(), 'updated_at' => now()
                ]);
            }
        }

        if ($request->has('redirect_to_builder')) {
            return redirect()->route('admin.pages.edit', ['page' => $page, 'start_builder' => 1])->with('success', 'Page created successfully.');
        }

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $allPages = Page::where('id', '!=', $page->id)->orderBy('title')->get();
        
        // Fetch applicable custom field groups for pages
        $fieldGroups = \Acme\CmsDashboard\Models\FieldGroup::where('is_active', true)
            ->where(function($q) {
                $q->whereJsonContains('rules->post_type', 'page');
            })
            ->with('fields')
            ->orderBy('order')
            ->get();

        // Get existing field values
        $fieldValues = DB::table('post_custom_field_values')
            ->where('post_id', $page->id)
            ->pluck('value', 'field_id')
            ->toArray();

        return view('cms-dashboard::admin.pages.edit', compact('page', 'allPages', 'fieldGroups', 'fieldValues'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,scheduled',
            'parent_id' => 'nullable|exists:posts,id',
            'menu_order' => 'nullable|integer',
            'template' => 'nullable|string',
            'slug' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable',
            'editor_type' => 'nullable|string|in:rich,builder'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $page->id);
        } else {
            $validated['slug'] = $this->generateUniqueSlug($validated['slug'], $page->id);
        }

        if ($request->hasFile('featured_image')) {
            if ($page->featured_image && !\Acme\CmsDashboard\Models\Media::where('path', $page->featured_image)->exists()) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        } elseif ($request->input('remove_featured_image') === '1') {
            if ($page->featured_image && !\Acme\CmsDashboard\Models\Media::where('path', $page->featured_image)->exists()) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->featured_image);
            }
            $validated['featured_image'] = null;
        } elseif ($request->has('featured_image')) {
            $validated['featured_image'] = $request->input('featured_image');
        }

        if (empty($validated['template']) || $validated['template'] === 'default') {
            $validated['template'] = 'site-width';
        }

        $page->update($validated);

        // Update Custom Fields
        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $fieldId => $value) {
                DB::table('post_custom_field_values')->updateOrInsert(
                    ['post_id' => $page->id, 'field_id' => $fieldId],
                    [
                        'value' => is_array($value) ? json_encode($value) : $value,
                        'updated_at' => now()
                    ]
                );
            }
        }

        return back()->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page moved to trash.');
    }

    public function restore($id)
    {
        $page = Page::onlyTrashed()->findOrFail($id);
        $page->restore();
        return back()->with('success', 'Page restored successfully.');
    }

    public function forceDelete($id)
    {
        $page = Page::onlyTrashed()->findOrFail($id);
        $page->forceDelete();
        return back()->with('success', 'Page deleted permanently.');
    }

    public function bulk(Request $request)
    {
        $action = $request->input('action') !== '-1' ? $request->input('action') : $request->input('action2');
        if ($action === '-1' || !$request->has('post_ids')) {
            return back()->with('error', 'Please select an action and at least one item.');
        }

        $ids = $request->input('post_ids');

        if ($action === 'trash') {
            Page::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' pages moved to trash.');
        } elseif ($action === 'restore') {
            Page::onlyTrashed()->whereIn('id', $ids)->restore();
            return back()->with('success', count($ids) . ' pages restored.');
        } elseif ($action === 'delete') {
            Page::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            return back()->with('success', count($ids) . ' pages deleted permanently.');
        } elseif (in_array($action, ['draft', 'published'])) {
            Page::whereIn('id', $ids)->update(['status' => $action]);
            return back()->with('success', count($ids) . ' pages marked as ' . ucfirst($action) . '.');
        }

        return back();
    }
}
