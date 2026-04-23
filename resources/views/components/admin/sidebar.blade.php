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
                        
                        // Check if the current user can access this menu
                        if (!\Acme\CmsDashboard\View\Components\Admin\Sidebar::canAccess($href)) {
                            continue;
                        }

                        $isActive = \Acme\CmsDashboard\View\Components\Admin\Sidebar::isUrlActive($href);

                        if (!$isActive && $hasChildren) {
                            foreach($menu->children as $child) {
                                if (\Acme\CmsDashboard\View\Components\Admin\Sidebar::isUrlActive($resolveRoute($child->route, $child->title))) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        }

                        // Determine if we need separator lines
                        $isComments = ($menu->title === 'Comments');
                        $isMenu = ($menu->title === 'Menu');
                        
                        $liClasses = 'group sidebar-item relative';
                        if ($isComments) {
                            $liClasses .= ' border-b border-[#2c3338] pb-2 mb-2';
                        }
                        if ($isMenu) {
                            $liClasses .= ' border-t border-[#2c3338] pt-2 mt-2';
                        }
                    @endphp
                <li class="{{ $liClasses }}">
                    <a href="{{ $href }}" class="sidebar-item-link relative flex items-center px-3 py-[8px] transition-colors {{ $isActive ? 'bg-[#2271b1] text-white' : 'hover:bg-[#2c3338] hover:text-[#72aee6] text-[#c3c4c7]' }}">
                        <div class="w-5 h-5 mr-3 flex items-center justify-center {!! $isActive ? 'text-white' : 'text-[#c3c4c7] group-hover:text-[#72aee6]' !!}">
                            @if(str_starts_with($menu->icon, '<svg'))
                                {!! $menu->icon !!}
                            @else
                                <span class="material-symbols-outlined text-[20px]">{{ $menu->icon ?: 'radio_button_unchecked' }}</span>
                            @endif
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
                                        @php 
                                            $childHref = $resolveRoute($child->route, $child->title);
                                            $isChildActive = \Acme\CmsDashboard\View\Components\Admin\Sidebar::isUrlActive($childHref);
                                        @endphp
                                        <li>
                                            <a href="{{ $childHref }}" class="block px-3 py-[6px] transition text-[13px] {{ $isChildActive ? 'text-white font-semibold' : 'text-[#c3c4c7] hover:text-[#72aee6]' }}">
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

        {{-- Dynamic Options Pages Grouped by 'group' --}}
        @php 
            $customPages = config('lazy-options.pages') ?? []; 
            $groupedPages = [];
            foreach($customPages as $slug => $page) {
                $group = $page['group'] ?? 'Custom Options';
                $groupedPages[$group][$slug] = $page;
            }
        @endphp

        @foreach($groupedPages as $groupName => $pages)
            <li class="mt-4 mb-1 px-3 text-[10px] font-semibold text-[#8c8f94] uppercase tracking-wider">{{ $groupName }}</li>
            @foreach($pages as $slug => $page)
                @php 
                    $href = route('admin.options.index', $slug);
                    $isActive = request()->is('admin/options/' . $slug);
                @endphp
                <li class="group sidebar-item relative">
                    <a href="{{ $href }}" class="sidebar-item-link relative flex items-center px-3 py-[8px] transition-colors {{ $isActive ? 'bg-[#2271b1] text-white' : 'hover:bg-[#2c3338] hover:text-[#72aee6] text-[#c3c4c7]' }}">
                        <div class="w-5 h-5 mr-3 flex items-center justify-center {!! $isActive ? 'text-white' : 'text-[#c3c4c7] group-hover:text-[#72aee6]' !!}">
                            @if(isset($page['icon']) && str_starts_with($page['icon'], '<svg'))
                                {!! $page['icon'] !!}
                            @elseif(isset($page['icon']))
                                <span class="material-symbols-outlined text-[20px]">{{ $page['icon'] }}</span>
                            @else
                                <span class="material-symbols-outlined text-[20px]">settings</span>
                            @endif
                        </div>
                        <span class="text-[14px] leading-none {{ $isActive ? 'font-semibold' : '' }}">{{ $page['title'] }}</span>
                        @if($isActive)
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-0 h-0 border-y-[6px] border-y-transparent border-r-[6px] border-r-[#f0f0f1]"></div>
                        @endif
                    </a>
                </li>
            @endforeach
        @endforeach
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
