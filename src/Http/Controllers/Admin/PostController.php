<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function builder($id)
    {
        $post = Post::findOrFail($id);
        return view('cms-dashboard::admin.builder.index', compact('post'));
    }

    public function saveBuilder(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update([
            'content' => json_encode($request->input('layout')),
            'editor_type' => 'builder'
        ]);

        return response()->json(['success' => true, 'message' => 'Page layout saved successfully.']);
    }

    public function previewBuilder($id)
    {
        $post = Post::findOrFail($id);
        // This would typically return a front-end view that renders the builder JSON
        return view('cms-dashboard::admin.builder.preview', compact('post'));
    }

    public function __construct()
    {
        // We could use middleware, but for simplicity in this controller:
    }

    protected function checkTypeActive($slug)
    {
        $postType = \Acme\CmsDashboard\Models\PostType::where('slug', $slug)->first();
        if ($postType && !$postType->is_active) {
            abort(404, "This post type is deactivated.");
        }
    }

    protected function generateUniqueSlug($title, $id = 0, $type = 'post')
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
        while (Post::withTrashed()->where('slug', $slug)->where('type', $type)->where('id', '!=', $id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        return $slug;
    }

    public function index(Request $request)
    {
        $type = $request->query('type', 'post');
        if (!auth()->user()->hasPermission($type === 'page' ? 'manage_pages' : 'manage_posts')) {
            abort(403, "You do not have permission to manage {$type}s.");
        }
        $this->checkTypeActive($type);
        $status = $request->query('status');
        
        $query = Post::with(['categories', 'tags', 'taxonomyTerms'])->where('type', $type);

        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            $query->withoutTrashed();
            if ($status) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('s')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->s . '%')
                  ->orWhere('content', 'like', '%' . $request->s . '%');
            });
        }

        if ($request->filled('cat') && $request->cat != '-1') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->cat);
            });
        }

        if ($request->filled('m') && $request->m != '-1') {
            $year = substr($request->m, 0, 4);
            $month = substr($request->m, 4, 2);
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }

        $posts = $query->latest()->paginate(10)->withQueryString();
        $categories = \Acme\CmsDashboard\Models\Category::orderBy('name')->get();
        $dates = Post::where('type', $type)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $allCount = Post::where('type', $type)->count();
        $publishedCount = Post::where('type', $type)->where('status', 'published')->count();
        $draftCount = Post::where('type', $type)->where('status', 'draft')->count();
        $trashCount = Post::where('type', $type)->onlyTrashed()->count();

        $postType = \Acme\CmsDashboard\Models\PostType::where('slug', $type)->first();
        
        $assignedTaxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)
            ->whereJsonContains('post_types', $type)
            ->get();

        $overriddenTaxonomies = $assignedTaxonomies->whereIn('slug', ['categories', 'tags'])->pluck('slug')->toArray();

        return view('cms-dashboard::admin.posts.index', compact('posts', 'type', 'categories', 'dates', 'allCount', 'publishedCount', 'draftCount', 'trashCount', 'postType', 'assignedTaxonomies', 'overriddenTaxonomies'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'post');
        if (!auth()->user()->hasPermission($type === 'page' ? 'manage_pages' : 'manage_posts')) {
            abort(403, "You do not have permission to create {$type}s.");
        }
        $this->checkTypeActive($type);
        
        $pages = Post::where('type', 'page')->orderBy('title')->get();
        $postType = \Acme\CmsDashboard\Models\PostType::where('slug', $type)->first();
        $supports = $postType ? ($postType->supports ?? ['title', 'editor', 'excerpt', 'featured_image']) : ['title', 'editor', 'excerpt', 'featured_image'];

        $assignedTaxonomies = [];
        
        // Detect custom taxonomies that override built-in ones
        $overriddenTaxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)
            ->whereJsonContains('post_types', $type)
            ->whereIn('slug', ['categories', 'tags'])
            ->pluck('slug')
            ->toArray();

        $taxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)->get();
        foreach ($taxonomies as $tax) {
            if (is_array($tax->post_types) && in_array($type, $tax->post_types)) {
                $slugLower = strtolower($tax->slug);
                if (in_array($slugLower, ['categories', 'tags', 'category', 'post_tag']) && !in_array($slugLower, $overriddenTaxonomies)) continue;
                
                $tax->terms = \Acme\CmsDashboard\Models\TaxonomyTerm::where('taxonomy_slug', $tax->slug)
                    ->where('cpt_slug', $type)
                    ->get();
                $assignedTaxonomies[] = $tax;
            }
        }
        
        // Custom Fields
        $fieldGroups = \Acme\CmsDashboard\Models\FieldGroup::where('is_active', true)
            ->where(function($q) use ($type) {
                $q->whereJsonContains('rules->post_type', $type);
            })
            ->with('fields')
            ->orderBy('order')
            ->get();

        $post = new Post();

        return view('cms-dashboard::admin.posts.create', compact('post', 'type', 'pages', 'supports', 'assignedTaxonomies', 'fieldGroups', 'postType', 'overriddenTaxonomies'));
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'post');
        if (!auth()->user()->hasPermission($type === 'page' ? 'manage_pages' : 'manage_posts')) {
            abort(403, "You do not have permission to store {$type}s.");
        }
        $this->checkTypeActive($type);
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'slug'    => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'type'    => 'required|string',
            'status'  => 'required|string|in:draft,published,scheduled',
            'published_at'   => 'nullable|date',
            'featured_image' => 'nullable',
            'parent_id'      => 'nullable|exists:posts,id',
            'template'       => 'nullable|string',
            'menu_order'     => 'nullable|integer',
            'editor_type'    => 'nullable|string|in:rich,builder',
        ]);

        $slugSource = !empty($validated['slug']) ? $validated['slug'] : $validated['title'];
        $validated['slug'] = $this->generateUniqueSlug($slugSource, 0, $validated['type']);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        } elseif ($request->filled('featured_image')) {
            $validated['featured_image'] = $request->input('featured_image');
        }

        if (empty($validated['template']) || $validated['template'] === 'default') {
            $validated['template'] = 'site-width';
        }

        $postType = \Acme\CmsDashboard\Models\PostType::where('slug', $validated['type'])->first();
        $overriddenTaxonomies = [];
        if ($postType) {
            $overriddenTaxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)
                ->whereJsonContains('post_types', $validated['type'])
                ->pluck('slug')
                ->toArray();
        }

        $post = Post::create($validated);

        // Sync Built-in Categories
        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }

        // Sync Custom Taxonomies (for CPTs or assigned tags)
        if ($request->has('tax_terms')) {
            $post->taxonomyTerms()->sync($request->tax_terms);
        }

        if ($request->tags && !in_array('tags', $overriddenTaxonomies)) {
            $tagIds = [];
            $tags = array_map('trim', explode(',', $request->tags));
            foreach($tags as $tagName) {
                if(empty($tagName)) continue;
                $tag = \Acme\CmsDashboard\Models\Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        // Save Custom Fields
        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $fieldId => $value) {
                DB::table('post_custom_field_values')->insert([
                    'post_id' => $post->id,
                    'field_id' => $fieldId,
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'created_at' => now(), 'updated_at' => now()
                ]);
            }
        }

        if ($request->has('redirect_to_builder')) {
            return redirect()->route('admin.posts.edit', ['post' => $post, 'start_builder' => 1])->with('success', ucfirst($validated['type']) . ' created successfully.');
        }

        return redirect()->route('admin.posts.edit', $post)->with('success', ucfirst($validated['type']) . ' created successfully.');
    }

    public function edit(Post $post)
    {
        if (!auth()->user()->hasPermission($post->type === 'page' ? 'manage_pages' : 'manage_posts')) {
            abort(403, "You do not have permission to edit {$post->type}s.");
        }
        $this->checkTypeActive($post->type);
        $type = $post->type;
        $pages = Post::where('type', 'page')->where('id', '!=', $post->id)->orderBy('title')->get();
        $postType = \Acme\CmsDashboard\Models\PostType::where('slug', $type)->first();
        $supports = $postType ? ($postType->supports ?? ['title', 'editor', 'excerpt', 'featured_image']) : ['title', 'editor', 'excerpt', 'featured_image'];

        // Detect custom taxonomies that override built-in ones
        $overriddenTaxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)
            ->whereJsonContains('post_types', $type)
            ->whereIn('slug', ['categories', 'tags'])
            ->pluck('slug')
            ->toArray();

        $assignedTaxonomies = [];
        $taxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)->get();
        foreach ($taxonomies as $tax) {
            if (is_array($tax->post_types) && in_array($type, $tax->post_types)) {
                $slugLower = strtolower($tax->slug);
                if (in_array($slugLower, ['categories', 'tags', 'category', 'post_tag']) && !in_array($slugLower, $overriddenTaxonomies)) continue;

                $tax->terms = \Acme\CmsDashboard\Models\TaxonomyTerm::where('taxonomy_slug', $tax->slug)
                    ->where('cpt_slug', $type)
                    ->get();
                $tax->selected_ids = $post->taxonomyTerms()->where('taxonomy_slug', $tax->slug)->pluck('taxonomy_terms.id')->toArray();
                $assignedTaxonomies[] = $tax;
            }
        }

        // Fetch applicable custom field groups
        $fieldGroups = \Acme\CmsDashboard\Models\FieldGroup::where('is_active', true)
            ->where(function($q) use ($post) {
                $q->whereJsonContains('rules->post_type', $post->type);
            })
            ->with('fields')
            ->orderBy('order')
            ->get();

        // Get existing field values
        $fieldValues = DB::table('post_custom_field_values')
            ->where('post_id', $post->id)
            ->pluck('value', 'field_id')
            ->toArray();

        $postType = \Acme\CmsDashboard\Models\PostType::where('slug', $post->type)->first();

        return view('cms-dashboard::admin.posts.edit', compact('post', 'pages', 'type', 'supports', 'assignedTaxonomies', 'fieldGroups', 'fieldValues', 'postType', 'overriddenTaxonomies'));
    }

    public function update(Request $request, Post $post)
    {
        if (!auth()->user()->hasPermission($post->type === 'page' ? 'manage_pages' : 'manage_posts')) {
            abort(403, "You do not have permission to update {$post->type}s.");
        }
        $this->checkTypeActive($post->type);
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'slug'    => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'type'    => 'required|string',
            'status'  => 'required|string|in:draft,published,scheduled',
            'published_at'   => 'nullable|date',
            'featured_image' => 'nullable',
            'parent_id'      => 'nullable|exists:posts,id',
            'template'       => 'nullable|string',
            'menu_order'     => 'nullable|integer',
            'editor_type'    => 'nullable|string|in:rich,builder',
        ]);

        $slugSource = !empty($validated['slug']) ? $validated['slug'] : $validated['title'];
        $validated['slug'] = $this->generateUniqueSlug($slugSource, $post->id, $post->type);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        } elseif ($request->input('remove_featured_image') === '1') {
            $validated['featured_image'] = null;
        } elseif ($request->filled('featured_image')) {
            $validated['featured_image'] = $request->input('featured_image');
        }

        if (empty($validated['template']) || $validated['template'] === 'default') {
            $validated['template'] = 'site-width';
        }

        $postType = \Acme\CmsDashboard\Models\PostType::where('slug', $validated['type'])->first();
        $overriddenTaxonomies = [];
        if ($postType) {
            $overriddenTaxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)
                ->whereJsonContains('post_types', $validated['type'])
                ->pluck('slug')
                ->toArray();
        }

        $post->update($validated);

        // Sync Built-in Categories
        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }

        // Sync Custom Taxonomies
        if ($request->has('tax_terms')) {
            $post->taxonomyTerms()->sync($request->tax_terms);
        } else {
            // If it's a CPT and no terms sent, clear only terms for assigned taxonomies
            if ($post->type !== 'post') {
                $post->taxonomyTerms()->detach();
            }
        }

        if ($request->has('tags') && !in_array('tags', $overriddenTaxonomies)) {
            $tagIds = [];
            $tags = array_map('trim', explode(',', $request->tags));
            foreach($tags as $tagName) {
                if(empty($tagName)) continue;
                $tag = \Acme\CmsDashboard\Models\Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        // Update Custom Fields
        if ($request->has('custom_fields')) {
            foreach ($request->custom_fields as $fieldId => $value) {
                DB::table('post_custom_field_values')->updateOrInsert(
                    ['post_id' => $post->id, 'field_id' => $fieldId],
                    [
                        'value' => is_array($value) ? json_encode($value) : $value,
                        'updated_at' => now()
                    ]
                );
            }
        }
        
        return redirect()->back()->with('success', ucfirst($post->type) . ' updated successfully.');
    }

    public function destroy(Post $post)
    {
        if (!auth()->user()->hasPermission($post->type === 'page' ? 'manage_pages' : 'manage_posts')) {
            abort(403, "You do not have permission to delete {$post->type}s.");
        }
        $type = $post->type;
        $post->delete();
        return redirect()->route('admin.posts.index', ['type' => $type])->with('success', 'Moved to trash.');
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return redirect()->back()->with('success', 'Restored successfully.');
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        
        $post->forceDelete();
        return redirect()->back()->with('success', 'Deleted permanently.');
    }

    public function bulk(Request $request)
    {
        $ids = $request->input('post_ids');
        $action = $request->input('action') !== '-1' ? $request->input('action') : $request->input('action2');

        if (!$ids || $action === '-1') return redirect()->back()->with('error', 'Please select items and an action.');

        if ($action === 'trash') {
            Post::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Selected items moved to trash.');
        }

        if ($action === 'restore') {
            Post::onlyTrashed()->whereIn('id', $ids)->restore();
            return redirect()->back()->with('success', 'Selected items restored.');
        }

        if ($action === 'delete') {
            $posts = Post::onlyTrashed()->whereIn('id', $ids)->get();
            foreach($posts as $p) {
                $p->forceDelete();
            }
            return redirect()->back()->with('success', 'Selected items deleted permanently.');
        }

        if (in_array($action, ['draft', 'published'])) {
            Post::whereIn('id', $ids)->update(['status' => $action]);
            return redirect()->back()->with('success', 'Selected items updated.');
        }

        return redirect()->back();
    }
}
