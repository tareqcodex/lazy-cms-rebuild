<x-cms-dashboard::layouts.admin title="Edit Taxonomy">
    <div class="max-w-[1280px] mx-auto pb-12" x-data="taxonomyForm()">
        <form action="{{ route('admin.acpt.taxonomies.update', $taxonomy->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Header -->
            <div class="flex items-center justify-between mb-4 mt-2">
                <h1 class="text-[22px] font-normal text-[#1d2327]">Edit Taxonomy: <strong>{{ $taxonomy->name }}</strong></h1>
                <button type="submit" class="bg-[#2271b1] hover:bg-[#135e96] text-white px-3 py-[4px] text-[13px] rounded-[3px] border border-[#2271b1]">Update</button>
            </div>

            @if($errors->any())
                <div class="bg-white border-l-4 border-[#d63638] p-3 mb-4 shadow-[0_1px_1px_rgba(0,0,0,0.04)] text-[13px] text-[#1d2327]">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-[#c3c4c7] shadow-[0_1px_1px_rgba(0,0,0,0.04)] rounded-[4px] mb-6 p-6 space-y-6">

                <!-- Plural Label -->
                <div class="grid grid-cols-[200px_1fr] items-start">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Plural Label <span class="text-[#d63638]">*</span></label>
                    <div class="w-full max-w-[400px]">
                        <input type="text" name="plural_label" x-model="pluralLabel" required
                            value="{{ old('plural_label', $taxonomy->name) }}"
                            class="w-full border-[#8c8f94] focus:border-[#2271b1] border py-1.5 px-3 rounded-[3px] shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)] text-[14px]">
                    </div>
                </div>

                <!-- Singular Label -->
                <div class="grid grid-cols-[200px_1fr] items-start">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Singular Label <span class="text-[#d63638]">*</span></label>
                    <div class="w-full max-w-[400px]">
                        <input type="text" name="singular_label" required
                            value="{{ old('singular_label', $taxonomy->singular_name) }}"
                            class="w-full border-[#8c8f94] focus:border-[#2271b1] border py-1.5 px-3 rounded-[3px] shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)] text-[14px]">
                    </div>
                </div>

                <!-- Taxonomy Key -->
                <div class="grid grid-cols-[200px_1fr] items-start">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Taxonomy Key <span class="text-[#d63638]">*</span></label>
                    <div class="w-full max-w-[400px]">
                        <input type="text" name="taxonomy_key" x-model="taxonomyKey" required
                            value="{{ old('taxonomy_key', $taxonomy->slug) }}"
                            class="w-full border-[#8c8f94] focus:border-[#2271b1] border py-1.5 px-3 rounded-[3px] shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)] text-[14px]">
                        <p class="text-[12px] text-[#2271b1] mt-1">Lower case letters, underscores and dashes only. Max 32 characters.</p>
                    </div>
                </div>

                <!-- Post Types multi-select -->
                <div class="grid grid-cols-[200px_1fr] items-start" x-data="postTypeSelect()">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Post Types</label>
                    <div class="w-full max-w-[400px]">
                        <div class="relative border border-[#8c8f94] rounded-[3px] bg-white shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)]">
                            <div class="flex flex-wrap gap-1 p-1 min-h-[36px]" @click="open = true">
                                <template x-for="(item, index) in selected" :key="item.slug">
                                    <span class="flex items-center bg-[#2271b1] text-white text-[12px] px-2 py-0.5 rounded-[2px]">
                                        <span x-text="item.name"></span>
                                        <button type="button" @click.stop="removeItem(index)" class="ml-1 text-white/80 hover:text-white leading-none">×</button>
                                        <input type="hidden" :name="'post_types[]'" :value="item.slug">
                                    </span>
                                </template>
                                <input type="text" x-model="search" @focus="open = true" @input="open = true"
                                    placeholder="Select"
                                    class="flex-1 min-w-[80px] border-0 outline-none text-[13px] px-1 py-0.5 bg-transparent">
                            </div>
                            <div x-show="open" @click.away="open = false"
                                class="absolute left-0 right-0 top-full border border-[#8c8f94] bg-white z-50 max-h-[200px] overflow-y-auto shadow-md">
                                <template x-for="pt in filtered" :key="pt.slug">
                                    <div @click="selectItem(pt)"
                                        class="px-3 py-2 text-[13px] cursor-pointer hover:bg-[#2271b1] hover:text-white"
                                        :class="isSelected(pt) ? 'bg-[#2271b1] text-white' : 'text-[#1d2327]'"
                                        x-text="pt.name">
                                    </div>
                                </template>
                                <div x-show="filtered.length === 0" class="px-3 py-2 text-[13px] text-[#646970]">No post types found</div>
                            </div>
                        </div>
                        <p class="text-[12px] text-[#646970] mt-1">Select which post types this taxonomy applies to.</p>
                    </div>
                </div>

                <input type="hidden" name="hierarchical" value="1">

            </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-[#2271b1] hover:bg-[#135e96] text-white px-4 py-2 text-[13px] rounded-[3px]">Update</button>
                </div>
            </div>
        </form>

        <div class="flex justify-between items-center -mt-10 mb-10 px-6">
            <form action="{{ route('admin.acpt.taxonomies.destroy', $taxonomy->id) }}" method="POST" onsubmit="return confirm('Delete this taxonomy?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-[#d63638] text-[13px] hover:underline">Delete Taxonomy</button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const availablePostTypes = @json($postTypes->map(fn($pt) => ['name' => $pt->name . ' (' . $pt->slug . ')', 'slug' => $pt->slug])->values());
        const existingPostTypes  = @json(is_array($taxonomy->post_types) ? $taxonomy->post_types : []);

        document.addEventListener('alpine:init', () => {
            Alpine.data('taxonomyForm', () => ({
                pluralLabel: '{{ $taxonomy->name }}',
                singularLabel: '{{ $taxonomy->singular_name }}',
                taxonomyKey: '{{ $taxonomy->slug }}',
            }));

            Alpine.data('postTypeSelect', () => ({
                open: false,
                search: '',
                items: availablePostTypes,
                selected: availablePostTypes.filter(pt => existingPostTypes.includes(pt.slug)),

                get filtered() {
                    const q = this.search.toLowerCase();
                    return this.items.filter(pt => pt.name.toLowerCase().includes(q) && !this.isSelected(pt));
                },
                isSelected(pt) { return this.selected.some(s => s.slug === pt.slug); },
                selectItem(pt) { if (!this.isSelected(pt)) this.selected.push(pt); this.search = ''; this.open = false; },
                removeItem(index) { this.selected.splice(index, 1); }
            }));
        });
    </script>
    <style>[x-cloak] { display: none !important; }</style>
    @endpush
</x-cms-dashboard::layouts.admin>
