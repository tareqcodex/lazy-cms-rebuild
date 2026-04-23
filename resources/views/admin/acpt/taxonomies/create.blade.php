<x-cms-dashboard::layouts.admin title="Add New Taxonomy">
    <div class="max-w-[1280px] mx-auto pb-12" x-data="taxonomyForm()">
        <form action="{{ route('admin.acpt.taxonomies.store') }}" method="POST">
            @csrf

            <!-- Header -->
            <div class="flex items-center justify-between mb-4 mt-2">
                <h1 class="text-[22px] font-normal text-[#1d2327]">Add New Taxonomy</h1>
                <button type="submit" class="bg-[#2271b1] hover:bg-[#135e96] text-white px-3 py-[4px] text-[13px] rounded-[3px] border border-[#2271b1]">Save Changes</button>
            </div>

            @if($errors->any())
                <div class="bg-white border-l-4 border-[#d63638] p-3 mb-4 shadow-[0_1px_1px_rgba(0,0,0,0.04)] text-[13px] text-[#1d2327]">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Box -->
            <div class="bg-white border border-[#c3c4c7] shadow-[0_1px_1px_rgba(0,0,0,0.04)] rounded-[4px] mb-6 p-6 space-y-6">

                <!-- Plural Label -->
                <div class="grid grid-cols-[200px_1fr] items-start">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Plural Label <span class="text-[#d63638]">*</span></label>
                    <div class="w-full max-w-[400px]">
                        <input type="text" name="plural_label" x-model="pluralLabel" @input="updateKey" required
                            placeholder="Genres"
                            class="w-full border-[#8c8f94] focus:border-[#2271b1] border py-1.5 px-3 rounded-[3px] shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)] text-[14px]">
                    </div>
                </div>

                <!-- Singular Label -->
                <div class="grid grid-cols-[200px_1fr] items-start">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Singular Label <span class="text-[#d63638]">*</span></label>
                    <div class="w-full max-w-[400px]">
                        <input type="text" name="singular_label" x-model="singularLabel" required
                            placeholder="Genre"
                            class="w-full border-[#8c8f94] focus:border-[#2271b1] border py-1.5 px-3 rounded-[3px] shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)] text-[14px]">
                    </div>
                </div>

                <!-- Taxonomy Key -->
                <div class="grid grid-cols-[200px_1fr] items-start">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Taxonomy Key <span class="text-[#d63638]">*</span></label>
                    <div class="w-full max-w-[400px]">
                        <input type="text" name="taxonomy_key" x-model="taxonomyKey" required
                            placeholder="genre"
                            class="w-full border-[#8c8f94] focus:border-[#2271b1] border py-1.5 px-3 rounded-[3px] shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)] text-[14px]">
                        <p class="text-[12px] text-[#2271b1] mt-1">Lower case letters, underscores and dashes only. Max 32 characters.</p>
                    </div>
                </div>

                <!-- Post Types (multi-select searchable) -->
                <div class="grid grid-cols-[200px_1fr] items-start" x-data="postTypeSelect()">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Post Types</label>
                    <div class="w-full max-w-[400px]">
                        <div class="relative border border-[#8c8f94] rounded-[3px] bg-white shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)]">
                            <!-- Selected Tags -->
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

                            <!-- Dropdown -->
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

                <!-- Style Mode -->
                <div class="grid grid-cols-[200px_1fr] items-start">
                    <label class="text-[13px] font-semibold text-[#2c3338] pt-1">Style Mode</label>
                    <div class="w-full max-w-[400px]">
                        <select name="hierarchical" class="w-full border-[#8c8f94] focus:border-[#2271b1] border py-1.5 px-3 rounded-[3px] shadow-[inset_0_1px_2px_rgba(0,0,0,0.07)] text-[14px]">
                            <option value="1">Hierarchical Taxonomy (Category/Tree structure)</option>
                            <option value="0">Non-Hierarchical Taxonomy (Tag structure)</option>
                        </select>
                        <p class="text-[12px] text-[#646970] mt-1">Hierarchical taxonomies can have parents/children (like Categories). Non-hierarchical ones are flat (like Tags).</p>
                    </div>
                </div>

            </div>

            <!-- Bottom Save -->
            <div class="flex justify-end">
                <button type="submit" class="bg-[#2271b1] hover:bg-[#135e96] text-white px-4 py-2 text-[13px] rounded-[3px] border border-[#2271b1]">Save Changes</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Available post types (CPTs from DB + built-ins)
        const availablePostTypes = @json($postTypes->map(fn($pt) => ['name' => $pt->name . ' (' . $pt->slug . ')', 'slug' => $pt->slug])->values());

        document.addEventListener('alpine:init', () => {
            Alpine.data('taxonomyForm', () => ({
                pluralLabel: '',
                singularLabel: '',
                taxonomyKey: '',

                updateKey() {
                    this.taxonomyKey = this.pluralLabel.toLowerCase()
                        .replace(/[^a-z0-9]/g, '_')
                        .replace(/_+/g, '_')
                        .replace(/^_|_$/g, '')
                        .substring(0, 32);
                }
            }));

            Alpine.data('postTypeSelect', () => ({
                open: false,
                search: '',
                selected: [],
                items: availablePostTypes,

                get filtered() {
                    const q = this.search.toLowerCase();
                    return this.items.filter(pt =>
                        pt.name.toLowerCase().includes(q) && !this.isSelected(pt)
                    );
                },

                isSelected(pt) {
                    return this.selected.some(s => s.slug === pt.slug);
                },

                selectItem(pt) {
                    if (!this.isSelected(pt)) {
                        this.selected.push(pt);
                    }
                    this.search = '';
                    this.open = false;
                },

                removeItem(index) {
                    this.selected.splice(index, 1);
                }
            }));
        });
    </script>
    <style>[x-cloak] { display: none !important; }</style>
    @endpush
</x-cms-dashboard::layouts.admin>
