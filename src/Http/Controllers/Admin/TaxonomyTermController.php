<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Acme\CmsDashboard\Models\TaxonomyTerm;
use Acme\CmsDashboard\Models\CustomTaxonomy;

class TaxonomyTermController extends Controller
{
    public function index(Request $request, $taxonomySlug)
    {
        $taxonomy = CustomTaxonomy::where('slug', $taxonomySlug)->firstOrFail();
        $cptSlug  = $request->query('cpt');

        $query = TaxonomyTerm::where('taxonomy_slug', $taxonomySlug)
            ->where('cpt_slug', $cptSlug);

        if ($request->filled('s')) {
            $query->where('name', 'like', '%' . $request->s . '%');
        }

        $terms   = $query->latest()->paginate(20);
        $parents = TaxonomyTerm::where('taxonomy_slug', $taxonomySlug)
            ->where('cpt_slug', $cptSlug)
            ->whereNull('parent_id')
            ->get();

        return view('cms-dashboard::admin.acpt.taxonomies.terms', compact('taxonomy', 'terms', 'parents', 'cptSlug'));
    }

    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'taxonomy_slug' => 'required|string',
            'cpt_slug'      => 'required|string',
            'parent_id'     => 'nullable|exists:taxonomy_terms,id',
        ]);

        $taxonomySlug = $request->taxonomy_slug;
        $cptSlug      = $request->cpt_slug;
        $name         = trim($request->name);

        // Check if exists
        $term = TaxonomyTerm::where('taxonomy_slug', $taxonomySlug)
            ->where('cpt_slug', $cptSlug)
            ->where('name', $name)
            ->first();

        if ($term) {
            return response()->json($term);
        }

        $slug = TaxonomyTerm::generateUniqueSlug($name, 0, $cptSlug);

        $term = TaxonomyTerm::create([
            'taxonomy_slug' => $taxonomySlug,
            'cpt_slug'      => $cptSlug,
            'name'          => $name,
            'slug'          => $slug,
            'parent_id'     => $request->parent_id ?: null,
        ]);

        return response()->json($term);
    }

    public function store(Request $request, $taxonomySlug)
    {
        $cptSlug = $request->input('cpt_slug');

        $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:taxonomy_terms,id',
        ]);

        $baseName = $request->slug ? $request->slug : $request->name;
        $slug = TaxonomyTerm::generateUniqueSlug($baseName, 0, $cptSlug);

        TaxonomyTerm::create([
            'taxonomy_slug' => $taxonomySlug,
            'cpt_slug'      => $cptSlug,
            'name'          => $request->name,
            'slug'          => $slug,
            'description'   => $request->description,
            'parent_id'     => $request->parent_id ?: null,
        ]);

        return redirect()->back()->with('success', '"' . $request->name . '" added successfully.');
    }

    public function destroy(Request $request, $taxonomySlug, $id)
    {
        $term = TaxonomyTerm::findOrFail($id);
        $term->delete();
        return redirect()->back()->with('success', 'Term deleted.');
    }

    public function bulk(Request $request, $taxonomySlug)
    {
        $action = $request->input('action');
        if (empty($action) || $action === '-1') {
            $action = $request->input('action2');
        }
        $ids = $request->input('ids', []);

        if ($action === 'delete' && !empty($ids)) {
            TaxonomyTerm::whereIn('id', $ids)->delete();
        }

        return redirect()->back()->with('success', 'Action applied.');
    }
}
