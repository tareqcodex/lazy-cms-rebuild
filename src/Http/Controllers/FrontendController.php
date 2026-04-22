<?php

namespace Acme\CmsDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Acme\CmsDashboard\Models\Post;
use Acme\CmsDashboard\Models\PostType;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function show($typeOrSlug, $slug = null)
    {
        if ($slug) {
            $type = $typeOrSlug;
            $postSlug = $slug;
            
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

        return view('cms-dashboard::frontend.single', compact('post'));
    }
}
