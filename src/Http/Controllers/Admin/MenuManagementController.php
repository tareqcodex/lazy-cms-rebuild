<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\NavigationMenu;
use Acme\CmsDashboard\Models\NavigationMenuItem;
use Acme\CmsDashboard\Models\Post;
use Acme\CmsDashboard\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuManagementController extends Controller
{
    public function index(Request $request)
    {
        $menus = NavigationMenu::all();
        $menu = null;

        if ($request->filled('menu')) {
            $menu = NavigationMenu::with(['allItems'])->find($request->menu);
        } elseif ($menus->isNotEmpty()) {
            $menu = $menus->first()->load(['allItems']);
        }

        $pages = Post::where('type', 'page')->where('status', 'published')->latest()->take(20)->get();
        $posts = Post::where('type', 'post')->where('status', 'published')->latest()->take(20)->get();
        $categories = Category::all();

        // Dynamic CPTs for Menu Builder
        $customPostTypes = \Acme\CmsDashboard\Models\PostType::where('show_in_menu', true)
            ->where('is_builtin', false)
            ->get();
        
        $cptData = [];
        foreach ($customPostTypes as $type) {
            $cptData[] = [
                'key' => 'cpt_' . $type->slug,
                'label' => $type->name,
                'items' => Post::where('type', $type->slug)->where('status', 'published')->latest()->take(20)->get(),
                'type' => $type->slug
            ];
        }

        // Pre-build FLAT JSON (depth-based) to avoid Blade parsing issues
        $menuItemsJson = '[]';
        if ($menu) {
            // Collect all object_ids grouped by type to detect orphans efficiently
            $allItems = $menu->allItems->flatMap(function($item) {
                return collect([$item])->concat($item->children->flatMap(fn($c) => collect([$c])->concat($c->children)));
            });

            $postIds     = $allItems->whereNotIn('type', ['category', 'custom'])->pluck('object_id')->filter()->unique()->values()->all();
            $categoryIds = $allItems->where('type','category')->pluck('object_id')->filter()->unique()->values()->all();

            $postsData = $postIds ? \Acme\CmsDashboard\Models\Post::withTrashed()->whereIn('id', $postIds)->get(['id', 'status', 'deleted_at'])->keyBy('id') : collect();
            $existingCategoryIds = $categoryIds ? \Acme\CmsDashboard\Models\Category::whereIn('id', $categoryIds)->pluck('id')->map(fn($id)=>(string)$id)->all() : [];

            $buildItem = function($item, $depth) use ($postsData, $existingCategoryIds) {
                $type      = $item->type ?? 'custom';
                $objectId  = $item->object_id ? (string)$item->object_id : null;
                $orphaned  = false;
                $isDraft   = false;
                $isTrashed = false;

                if ($type !== 'category' && $type !== 'custom') {
                    if ($objectId) {
                        $post = $postsData->get($objectId);
                        if (!$post) {
                            $orphaned = true;
                        } else {
                            if ($post->deleted_at) $isTrashed = true;
                            if ($post->status === 'draft') $isDraft = true;
                        }
                    }
                } elseif ($type === 'category' && $objectId) {
                    $orphaned = !in_array($objectId, $existingCategoryIds);
                }

                return [
                    'id'        => (string)$item->id,
                    'title'     => $item->title,
                    'url'       => $item->url ?? '#',
                    'type'      => $type,
                    'object_id' => $objectId,
                    'depth'     => $depth,
                    'orphaned'  => $orphaned || $isTrashed,
                    'is_draft'  => $isDraft,
                ];
            };

            $flat     = [];
            $topItems = $menu->allItems->where('parent_id', null)->sortBy('order')->values();
            foreach ($topItems as $item) {
                $flat[] = $buildItem($item, 0);
                foreach ($item->children->sortBy('order') as $child) {
                    $flat[] = $buildItem($child, 1);
                    foreach ($child->children->sortBy('order') as $grandchild) {
                        $flat[] = $buildItem($grandchild, 2);
                    }
                }
            }
            $menuItemsJson = json_encode($flat, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
        }

        return view('cms-dashboard::admin.menus.index', compact('menus', 'menu', 'pages', 'posts', 'categories', 'menuItemsJson', 'cptData'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $menu = NavigationMenu::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.menus.index', ['menu' => $menu->id])->with('success', 'Menu created successfully.');
    }

    public function update(Request $request, $id)
    {
        $menu = NavigationMenu::findOrFail($id);

        // Always update name if provided
        if ($request->filled('name')) {
            $menu->update(['name' => $request->name]);
        }

        if ($request->has('menu_items')) {
            $items = json_decode($request->menu_items, true);

            // Hard-delete ALL existing items for this menu using direct DB query
            // (avoids relationship cache issues that cause doubling)
            NavigationMenuItem::where('navigation_menu_id', $menu->id)->delete();

            // Only save if there are items to save
            if (is_array($items) && count($items) > 0) {
                $this->saveItems($menu->id, $items);
            }
        }

        return redirect()
            ->route('admin.menus.index', ['menu' => $menu->id])
            ->with('success', 'Menu saved successfully.');
    }

    private function saveItems($menuId, $items, $parentId = null)
    {
        foreach ($items as $index => $item) {
            $newItem = NavigationMenuItem::create([
                'navigation_menu_id' => $menuId,
                'parent_id' => $parentId,
                'title' => $item['title'] ?? 'Item',
                'url' => $item['url'] ?? '#',
                'type' => $item['type'] ?? 'custom',
                'object_id' => $item['object_id'] ?? null,
                'order' => $index,
            ]);

            if (!empty($item['children'])) {
                $this->saveItems($menuId, $item['children'], $newItem->id);
            }
        }
    }

    public function destroy($id)
    {
        NavigationMenu::findOrFail($id)->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted.');
    }
}
