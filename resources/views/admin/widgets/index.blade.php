<x-cms-dashboard::layouts.admin title="Manage Widgets">
<div class="max-w-[1400px] mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Widgets</h1>
            <p class="text-slate-500 text-sm mt-1">Drag and add widgets to your theme areas.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Available Widgets -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Available Widgets</h3>
                </div>
                <div class="p-5 space-y-4">
                    @foreach($availableWidgets as $type => $info)
                        <div class="p-4 border border-slate-200 rounded-lg hover:border-primary/30 hover:bg-slate-50 transition-all group">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-slate-900">{{ $info['name'] }}</h4>
                                <div class="dropdown relative">
                                    <button class="p-1 text-slate-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                                    </button>
                                    <div class="dropdown-menu hidden absolute right-0 top-full w-48 bg-white border border-slate-200 shadow-xl rounded-lg py-2 z-50">
                                        @foreach($widgetAreas as $areaKey => $areaName)
                                            <form action="{{ route('admin.widgets.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $type }}">
                                                <input type="hidden" name="area" value="{{ $areaKey }}">
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                                                    Add to {{ $areaName }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ $info['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Widget Areas -->
        <div class="lg:col-span-8 space-y-6">
            @foreach($widgetAreas as $areaKey => $areaName)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">{{ $areaName }}</h3>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            {{ ($activeWidgets[$areaKey] ?? collect())->count() }} Widgets
                        </span>
                    </div>
                    <div class="p-5 space-y-3 min-h-[100px] widget-area" data-area="{{ $areaKey }}">
                        @forelse($activeWidgets[$areaKey] ?? [] as $widget)
                            <div class="widget-item bg-slate-50 border border-slate-200 rounded-lg overflow-hidden transition-all hover:border-slate-300" data-id="{{ $widget->id }}">
                                <div class="px-4 py-3 flex items-center justify-between cursor-move">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-slate-300 text-[18px]">drag_indicator</span>
                                        <span class="font-bold text-slate-700 text-sm">{{ $widget->title ?: ucwords(str_replace('_', ' ', $widget->type)) }}</span>
                                        <span class="text-[10px] px-2 py-0.5 bg-slate-200 text-slate-500 rounded font-bold uppercase">
                                            {{ str_replace('_', ' ', $widget->type) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button onclick="toggleWidgetSettings({{ $widget->id }})" class="p-1 text-slate-400 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">settings</span>
                                        </button>
                                        <form action="{{ route('admin.widgets.destroy', $widget->id) }}" method="POST" onsubmit="return confirm('Remove this widget?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-red-500 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Widget Settings Form -->
                                <div id="widget-settings-{{ $widget->id }}" class="hidden px-4 pb-4 pt-2 border-t border-slate-200 bg-white">
                                    <form action="{{ route('admin.widgets.update', $widget->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Widget Title</label>
                                            <input type="text" name="title" value="{{ $widget->title }}" class="w-full border border-slate-200 rounded px-3 py-2 text-sm focus:border-primary outline-none">
                                        </div>

                                        @if($widget->type === 'recent_posts')
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Number of posts</label>
                                                <input type="number" name="settings[limit]" value="{{ $widget->settings['limit'] ?? 5 }}" class="w-full border border-slate-200 rounded px-3 py-2 text-sm">
                                            </div>
                                        @elseif($widget->type === 'custom_html')
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">HTML Content</label>
                                                <textarea name="settings[content]" rows="5" class="w-full border border-slate-200 rounded px-3 py-2 text-sm font-mono">{{ $widget->settings['content'] ?? '' }}</textarea>
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-between pt-2">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1" {{ $widget->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary">
                                                <span class="text-xs text-slate-600">Active</span>
                                            </label>
                                            <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded text-xs font-bold hover:bg-primary-hover transition-colors">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center border-2 border-dashed border-slate-100 rounded-lg">
                                <p class="text-slate-400 text-sm italic">No widgets in this area.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .dropdown:hover .dropdown-menu { display: block; }
    /* Bridge the gap between button and menu */
    .dropdown-menu::before {
        content: '';
        position: absolute;
        top: -10px;
        left: 0;
        right: 0;
        height: 10px;
    }

    /* Toast Styles */
    .toast-container {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 9999;
    }
    .toast-message {
        background: #1d2327;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 0.75rem;
        display: flex;
        items-center;
        gap: 0.75rem;
        animation: toast-in 0.3s ease-out;
    }
    @keyframes toast-in {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="toast-container" id="toast-container"></div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.innerHTML = `
            <span class="material-symbols-outlined text-green-400 text-[20px]">check_circle</span>
            <span class="text-sm font-medium">${message}</span>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Check for Laravel session success message
    @if(session('success'))
        window.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}"));
    @endif

    function toggleWidgetSettings(id) {
        const el = document.getElementById(`widget-settings-${id}`);
        el.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const areas = document.querySelectorAll('.widget-area');
        
        areas.forEach(area => {
            new Sortable(area, {
                group: 'widgets',
                animation: 150,
                ghostClass: 'bg-primary/10',
                handle: '.cursor-move',
                onEnd: function() {
                    saveWidgetOrder();
                }
            });
        });

        function saveWidgetOrder() {
            const data = [];
            document.querySelectorAll('.widget-area').forEach(area => {
                const areaKey = area.dataset.area;
                area.querySelectorAll('.widget-item').forEach((item, index) => {
                    data.push({
                        id: item.dataset.id,
                        area: areaKey,
                        order: index + 1
                    });
                });
            });

            fetch('{{ route("admin.widgets.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ widgets: data })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    showToast('Widget order updated successfully!');
                }
            });
        }
    });
</script>
</x-cms-dashboard::layouts.admin>
