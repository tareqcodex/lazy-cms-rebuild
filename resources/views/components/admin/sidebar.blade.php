<div id="adminmenuwrap" class="fixed top-8 left-0 bottom-0 w-40 bg-[#1d2327] overflow-y-auto text-[#c3c4c7] z-40 pb-10 custom-scrollbar">
    <ul class="pt-0">
        @foreach($menuGroups as $groupName => $menus)
            @if($groupName && $groupName !== 'Main')
                <li class="mt-2 mb-1 px-3 text-[10px] font-semibold text-[#8c8f94] uppercase tracking-wider">{{ $groupName }}</li>
            @endif
            @foreach($menus as $menu)
                    @php 
                        $hasChildren = $menu->children->isNotEmpty(); 
                        $href = $resolveRoute($menu->route, $menu->title);

                        parse_str(parse_url($href, PHP_URL_QUERY), $targetQueryParams);
                        $targetTypeVal = $targetQueryParams['type'] ?? null;
                        $targetPath = trim(parse_url($href, PHP_URL_PATH), '/');

                        // Clean, simple matching using $activeMenu
                        // $activeMenu is set by Sidebar::detectActiveMenu() automatically
                        $isActive = false;

                        if ($activeMenu === 'dashboard' && $targetPath === 'admin') {
                            $isActive = true;
                        } elseif ($activeMenu === 'posts' && $targetPath === 'admin/posts' && !$targetTypeVal) {
                            $isActive = true;
                        } elseif ($activeMenu === 'pages' && $targetPath === 'admin/pages') {
                            $isActive = true;
                        } elseif ($activeMenu === 'pages' && $targetTypeVal === 'page') {
                            $isActive = true;
                        } elseif ($targetTypeVal && $activeMenu === $targetTypeVal) {
                            // CPT match — e.g. activeMenu='movies' matches type=movies
                            $isActive = true;
                        } elseif (!$targetTypeVal && $targetPath === 'admin/' . $activeMenu) {
                            // Other modules like media, menus, acpt, etc.
                            $isActive = true;
                        }

                        // Also check children to decide if parent should expand
                        if (!$isActive && $hasChildren) {
                            foreach($menu->children as $child) {
                                $childHref = $resolveRoute($child->route, $child->title);
                                parse_str(parse_url($childHref, PHP_URL_QUERY), $childQueryParams);
                                $childTypeVal = $childQueryParams['type'] ?? null;
                                $childPath = trim(parse_url($childHref, PHP_URL_PATH), '/');

                                if ($activeMenu === 'pages' && $childTypeVal === 'page') { $isActive = true; break; }
                                if ($childTypeVal && $childTypeVal === $activeMenu) { $isActive = true; break; }
                                if (!$childTypeVal && $childPath === 'admin/' . $activeMenu) { $isActive = true; break; }
                            }
                        }
                    @endphp
                <li class="group sidebar-item {{ $menu->title === 'Menu' ? 'mt-2 border-t border-[#2c3338] pt-2' : '' }} {{ $menu->title === 'Comments' ? 'border-b border-[#2c3338] pb-2 mb-2' : '' }}">
                    <a href="{{ $href }}" class="sidebar-item-link relative flex items-center px-3 py-[8px] transition-colors {{ $isActive ? 'bg-[#2271b1] text-white' : 'hover:bg-[#2c3338] hover:text-[#72aee6] text-[#c3c4c7]' }}">
                        <div class="w-5 h-5 mr-3 flex items-center justify-center {!! $isActive ? 'text-white' : 'text-[#c3c4c7] group-hover:text-[#72aee6]' !!}">
                            {!! $menu->icon !!}
                        </div>
                        <span class="text-[14px] leading-none {{ $isActive ? 'font-semibold' : '' }}">{{ $menu->title }}</span>
                        @if($isActive)
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-0 h-0 border-y-[6px] border-y-transparent border-r-[6px] border-r-[#f0f0f1]"></div>
                        @endif
                    </a>
                    @if($hasChildren)
                        @if($isActive)
                            <!-- Active state: accordion -->
                            <div class="bg-[#2c3338] block w-full">
                                <ul class="py-1">
                                    @foreach($menu->children as $child)
                                        @php $isChildActive = request()->fullUrl() === url($resolveRoute($child->route, $child->title)); @endphp
                                        <li>
                                            <a href="{{ $resolveRoute($child->route, $child->title) }}" class="block px-3 py-[6px] transition text-[13px] {{ $isChildActive ? 'text-white font-semibold' : 'text-[#c3c4c7] hover:text-[#72aee6]' }}">
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <!-- Inactive state: flyout menu on hover -->
                            <div class="sidebar-flyout hidden bg-[#2c3338] w-40 z-[9999] shadow-lg">
                                <!-- Triangle pointer for the flyout -->
                                <div class="absolute -left-[6px] top-[10px] w-0 h-0 border-y-[6px] border-y-transparent border-r-[6px] border-r-[#2c3338]"></div>
                                <ul class="py-1">
                                    @foreach($menu->children as $child)
                                        <li>
                                            <a href="{{ $resolveRoute($child->route, $child->title) }}" class="block px-3 py-[6px] transition text-[13px] hover:text-[#72aee6] text-[#c3c4c7]">
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                </li>
            @endforeach
        @endforeach
        
        <!-- Collapse Menu Option -->
        <li class="mt-4 border-t border-[#2c3338] group relative sidebar-item">
            <a href="javascript:void(0)" id="collapse-sidebar" class="sidebar-item-link flex items-center px-3 py-2 transition hover:text-[#72aee6] text-[#c3c4c7]">
                <div class="w-5 h-5 mr-3 opacity-80 group-hover:opacity-100 collapse-icon transition-transform flex items-center justify-center">
                    <div class="w-5 h-5 rounded-full bg-[#c3c4c7] flex items-center justify-center shadow-sm">
                        <svg class="w-3 h-3 text-[#1d2327]" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </div>
                </div>
                <span class="text-[13px] collapse-text">Collapse Menu</span>
            </a>
        <li class="border-t border-[#2c3338] group relative sidebar-item">
            <form action="{{ route('admin.logout') }}" method="POST" id="sidebar-logout-form" class="hidden">@csrf</form>
            <a href="javascript:void(0)" onclick="document.getElementById('sidebar-logout-form').submit();" class="sidebar-item-link flex items-center px-3 py-[8px] transition hover:text-red-400 text-[#c3c4c7]">
                <div class="w-5 h-5 mr-3 flex items-center justify-center text-[#c3c4c7] group-hover:text-red-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </div>
                <span class="text-[13px] collapse-text">Log Out</span>
            </a>
        </li>
    </ul>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('collapse-sidebar');
        const body = document.body;
        
        // Initial state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('sidebar-collapsed');
            document.querySelector('.collapse-icon').classList.add('rotate-180');
        }

        btn?.addEventListener('click', function() {
            body.classList.toggle('sidebar-collapsed');
            const isCollapsed = body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            
            document.querySelector('.collapse-icon').classList.toggle('rotate-180', isCollapsed);
        });
    });
</script>
@endpush
<style>
/* Thin scrollbar for sidebar */
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #1d2327; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #3c434a; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #8c8f94; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarWrap = document.getElementById('adminmenuwrap');
    
    document.querySelectorAll('.sidebar-item').forEach(li => {
        const flyout = li.querySelector('.sidebar-flyout');
        const link = li.querySelector('a');
        
        if (flyout) {
            li.addEventListener('mouseenter', () => {
                const rect = li.getBoundingClientRect();
                flyout.style.position = 'fixed';
                flyout.style.top = rect.top + 'px';
                flyout.style.left = rect.right + 'px';
                flyout.classList.remove('hidden');
                
                // Add hover effect to parent link manually since we moved out of group-hover
                link.classList.add('bg-[#2c3338]', 'text-[#72aee6]');
            });
            
            li.addEventListener('mouseleave', () => {
                flyout.classList.add('hidden');
                link.classList.remove('bg-[#2c3338]', 'text-[#72aee6]');
            });
        }
    });

    // Make sure sidebar scrolling hides flyouts completely
    sidebarWrap.addEventListener('scroll', () => {
        document.querySelectorAll('.sidebar-flyout').forEach(flyout => {
            flyout.classList.add('hidden');
        });
    });
});
</script>
