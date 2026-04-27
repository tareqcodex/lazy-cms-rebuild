<?php

namespace Acme\CmsDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Acme\CmsDashboard\Models\Post;
use Acme\CmsDashboard\Models\PostType;
use Illuminate\Http\Request;

use Acme\CmsDashboard\Models\Category;
use Acme\CmsDashboard\Models\Tag;


class FrontendController extends Controller
{
    public function index()
    {
        $homePageId = get_cms_option('home_page_id');
        
        if ($homePageId) {
            $post = Post::where('id', $homePageId)->where('status', 'published')->first();
            if ($post) {
                $view = ($post->type === 'page') ? 'cms-dashboard::themes.lazy-theme.page' : 'cms-dashboard::themes.lazy-theme.single';
                return view($view, compact('post'));
            }
        }

        return view('cms-dashboard::themes.lazy-theme.index');
    }

    public function archive($slug)
    {
        $routeName = request()->route()->getName();
        $items = collect();
        $title = '';

        if ($routeName === 'frontend.category') {
            $slugs = explode('/', $slug);
            $lastSlug = end($slugs);
            $category = Category::where('slug', $lastSlug)->firstOrFail();
            $items = $category->posts()->where('status', 'published')->latest()->paginate(12);
            $title = 'Category: ' . $category->name;
        } elseif ($routeName === 'frontend.tag') {
            $tag = Tag::where('slug', $slug)->firstOrFail();
            $items = $tag->posts()->where('status', 'published')->latest()->paginate(12);
            $title = 'Tag: ' . $tag->name;
        }

        return view('cms-dashboard::themes.lazy-theme.archive', compact('items', 'title'));
    }

    public function show($typeOrSlug, $slug = null)
    {
        $homePageId = get_cms_option('home_page_id');

        if ($slug) {
            $type = $typeOrSlug;
            $postSlug = $slug;
            
            // Check if it's a Custom Taxonomy first
            $customTaxonomy = \Acme\CmsDashboard\Models\CustomTaxonomy::where('slug', $type)->first();
            if ($customTaxonomy) {
                $slugs = explode('/', $postSlug);
                $lastSlug = end($slugs);
                $term = \Acme\CmsDashboard\Models\TaxonomyTerm::where('taxonomy_slug', $type)
                    ->where('slug', $lastSlug)
                    ->firstOrFail();
                
                $items = $term->posts()->where('status', 'published')->latest()->paginate(12);
                $title = $customTaxonomy->name . ': ' . $term->name;
                return view('cms-dashboard::themes.lazy-theme.archive', compact('items', 'title'));
            }

            $postType = PostType::where('slug', $type)->first();
            if (!$postType || !$postType->is_public) {
                abort(404);
            }
            
            $post = Post::where('type', $type)
                ->where('slug', $postSlug)
                ->where('status', 'published')
                ->firstOrFail();
        } else {
            $postSlug = $typeOrSlug;
            
            $post = Post::where('slug', $postSlug)
                ->where('status', 'published')
                ->first();
                
            if (!$post) {
                abort(404);
            }
            
            $postType = $post->postTypeDefinition;
            if ($postType && !$postType->is_public) {
                 abort(404);
            }
        }

        // Redirect to home if this page is set as the static home page
        if ($homePageId && $post->id == $homePageId) {
            return redirect('/', 301);
        }

        $view = ($post->type === 'page') ? 'cms-dashboard::themes.lazy-theme.page' : 'cms-dashboard::themes.lazy-theme.single';
        
        // If a custom view exists in resources/views/{slug}.blade.php, use it
        if (view()->exists($post->slug)) {
            $view = $post->slug;
        }

        return view($view, compact('post'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $title = 'Search results for: ' . $query;
        
        $items = Post::where('status', 'published')
            ->where('title', 'like', "%{$query}%")
            ->latest()
            ->paginate(12);
            
        return view('cms-dashboard::themes.lazy-theme.archive', compact('items', 'title'));
    }

    public function storeComment(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|min:3',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        \Acme\CmsDashboard\Models\Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => auth()->id(),
            'name' => auth()->check() ? auth()->user()->name : ($validated['name'] ?? 'Guest'),
            'email' => auth()->check() ? auth()->user()->email : ($validated['email'] ?? null),
            'comment' => $validated['comment'],
            'parent_id' => $validated['parent_id'] ?? null,
            'is_approved' => true // Auto-approve for now as requested or common practice in simple CMS
        ]);

        return back()->with('success', 'Comment posted successfully.');
    }
}
