<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Acme\CmsDashboard\Models\CustomTaxonomy;
use Acme\CmsDashboard\Models\TaxonomyTerm;
use Illuminate\Support\Str;

class AcptTermController extends Controller
{
    public function index(Request $request, $taxonomySlug)
    {
        $taxonomy = CustomTaxonomy::where('slug', $taxonomySlug)->firstOrFail();
        $query = TaxonomyTerm::where('taxonomy_slug', $taxonomySlug);
        
        if ($request->filled('cpt')) {
            $query->where('cpt_slug', $request->cpt);
        }

        if ($request->filled('s')) {
            $query->where('name', 'like', '%' . $request->s . '%');
            $terms = $query->withCount('posts')->latest()->paginate(10)->withQueryString();
        } else {
            $allTerms = $query->withCount('posts')->orderBy('name')->get();
            $tree = collect();
            $buildTree = function($parentId, $level) use (&$buildTree, $allTerms, &$tree) {
                foreach ($allTerms->where('parent_id', $parentId) as $term) {
                    $term->level = $level;
                    $tree->push($term);
                    $buildTree($term->id, $level + 1);
                }
            };
            $buildTree(null, 0);

            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 10;
            $terms = new \Illuminate\Pagination\LengthAwarePaginator(
                $tree->forPage($page, $perPage),
                $tree->count(),
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        }
        
        return view('cms-dashboard::admin.acpt.taxonomies.terms.index', compact('taxonomy', 'terms'));
    }

    public function store(Request $request, $taxonomySlug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = $request->slug ? TaxonomyTerm::generateUniqueSlug($request->slug, 0, $request->cpt_slug) : TaxonomyTerm::generateUniqueSlug($request->name, 0, $request->cpt_slug);

        TaxonomyTerm::create([
            'name' => $request->name,
            'slug' => $slug,
            'taxonomy_slug' => $taxonomySlug,
            'cpt_slug' => $request->cpt_slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Term added successfully!');
    }

    public function bulk(Request $request, $taxonomySlug)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');

        \Illuminate\Support\Facades\Log::info("Bulk action: Action=$action, IDs=" . json_encode($ids));

        if (!$ids || !is_array($ids) || $action === '-1') {
            return redirect()->back()->with('error', 'Please select items and an action.');
        }

        if ($action === 'delete') {
            TaxonomyTerm::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Selected terms deleted.');
        }

        return redirect()->back();
    }

    public function destroy($taxonomySlug, $id)
    {
        if (!is_numeric($id)) {
            return redirect()->back()->with('error', 'Invalid term ID.');
        }
        $term = TaxonomyTerm::findOrFail($id);
        $term->delete();
        return redirect()->back()->with('success', 'Term deleted successfully!');
    }

    public function edit($taxonomySlug, $id)
    {
        $taxonomy = CustomTaxonomy::where('slug', $taxonomySlug)->firstOrFail();
        $term = TaxonomyTerm::findOrFail($id);
        $allTerms = TaxonomyTerm::where('taxonomy_slug', $taxonomySlug)
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();
            
        return view('cms-dashboard::admin.acpt.taxonomies.terms.edit', compact('taxonomy', 'term', 'allTerms'));
    }

    public function update(Request $request, $taxonomySlug, $id)
    {
        $term = TaxonomyTerm::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = $request->slug ? TaxonomyTerm::generateUniqueSlug($request->slug, $id, $term->cpt_slug) : TaxonomyTerm::generateUniqueSlug($request->name, $id, $term->cpt_slug);

        $term->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.acpt.terms.index', $taxonomySlug)->with('success', 'Term updated successfully!');
    }

    public function ajax(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'taxonomy_slug' => 'required|string',
        ]);

        $slug = TaxonomyTerm::generateUniqueSlug($request->name, 0, $request->cpt_slug);

        $term = TaxonomyTerm::create([
            'name' => $request->name,
            'slug' => $slug,
            'taxonomy_slug' => $request->taxonomy_slug,
            'cpt_slug' => $request->cpt_slug,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json($term);
    }
}
