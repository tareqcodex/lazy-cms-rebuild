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
            $buildTree = function($parentId, $level) use (&$buildTree, $allCategories, &$tree) {
                foreach ($allCategories->where('parent_id', $parentId) as $cat) {
                    $cat->level = $level;
                    $tree->push($cat);
                    $buildTree($cat->id, $level + 1);
                }
            };
            $buildTree(null, 0);

            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 10;
            $categories = new \Illuminate\Pagination\LengthAwarePaginator(
                $tree->forPage($page, $perPage),
                $tree->count(),
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        }

        return view('cms-dashboard::admin.categories.index', compact('categories'));
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

        return redirect()->route('admin.categories.index')->with('success', 'Category added.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        return view('cms-dashboard::admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $baseSlug = $request->slug ?: $request->name;
        $request->merge(['slug' => Category::generateUniqueSlug($baseSlug, $category->id)]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
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
