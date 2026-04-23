<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Acme\CmsDashboard\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('posts')->orderBy('name');
        
        if ($request->has('s')) {
            $query->where('name', 'like', '%' . $request->s . '%')
                  ->orWhere('description', 'like', '%' . $request->s . '%');
            $categories = $query->paginate(10);
        } else {
            $allCategories = $query->get();
            $tree = collect();
            $visitedIds = [];

            $buildTree = function($parentId, $level) use (&$buildTree, $allCategories, &$tree, &$visitedIds) {
                foreach ($allCategories->where('parent_id', $parentId) as $cat) {
                    if (in_array($cat->id, $visitedIds)) continue; // Prevent infinite loops
                    $visitedIds[] = $cat->id;
                    $cat->level = $level;
                    $tree->push($cat);
                    $buildTree($cat->id, $level + 1);
                }
            };
            
            // Build tree starting from root categories
            $buildTree(null, 0);

            // If there are still categories not in the tree (orphans or circular loops)
            if ($tree->count() < $allCategories->count()) {
                $orphans = $allCategories->whereNotIn('id', $visitedIds);
                foreach ($orphans as $orphan) {
                    if (in_array($orphan->id, $visitedIds)) continue;
                    $orphan->level = 0; // Show orphans at root level
                    $tree->push($orphan);
                    $visitedIds[] = $orphan->id;
                    $buildTree($orphan->id, 1);
                }
            }

            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 10;
            $fullTree = $tree;
            $categories = new \Illuminate\Pagination\LengthAwarePaginator(
                $tree->forPage($page, $perPage),
                $tree->count(),
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        }

        return view('cms-dashboard::admin.categories.index', compact('categories', 'fullTree'));
    }

    public function bulk(Request $request)
    {
        $action = $request->input('action') !== '-1' ? $request->input('action') : $request->input('action2');
        $ids = $request->input('ids');

        if (($action === 'delete') && !empty($ids)) {
            Category::whereIn('id', $ids)->delete();
            return back()->with('success', 'Selected categories deleted.');
        }

        return back()->with('error', 'Please select an action and at least one item.');
    }

    public function store(Request $request)
    {
        $baseSlug = $request->slug ?: $request->name;
        $request->merge(['slug' => Category::generateUniqueSlug($baseSlug)]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index', ['type' => 'post'])->with('success', 'Category added.');
    }

    public function edit(Category $category)
    {
        $allCategories = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        $fullTree = collect();
        $visitedIds = [];

        $buildTree = function($parentId, $level) use (&$buildTree, $allCategories, &$fullTree, &$visitedIds) {
            foreach ($allCategories->where('parent_id', $parentId) as $cat) {
                if (in_array($cat->id, $visitedIds)) continue;
                $visitedIds[] = $cat->id;
                $cat->level = $level;
                $fullTree->push($cat);
                $buildTree($cat->id, $level + 1);
            }
        };
        $buildTree(null, 0);

        return view('cms-dashboard::admin.categories.edit', compact('category', 'fullTree'));
    }

    public function update(Request $request, Category $category)
    {
        $baseSlug = $request->slug ?: $request->name;
        $request->merge(['slug' => Category::generateUniqueSlug($baseSlug, $category->id)]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($value == $category->id) {
                        $fail('A category cannot be its own parent.');
                        return;
                    }
                    if ($value) {
                        $parent = \Acme\CmsDashboard\Models\Category::find($value);
                        while ($parent) {
                            if ($parent->id == $category->id) {
                                $fail('Circular reference detected: The selected parent is already a sub-category of this category.');
                                break;
                            }
                            $parent = $parent->parent;
                        }
                    }
                },
            ],
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index', ['type' => 'post'])->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index', ['type' => 'post'])->with('success', 'Category deleted.');
    }
    public function ajax(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $slug = Category::generateUniqueSlug($request->name);
        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
        ]);
        return response()->json($category);
    }
}
