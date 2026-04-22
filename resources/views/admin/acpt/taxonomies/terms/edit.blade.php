<x-cms-dashboard::layouts.admin title="Edit {{ $taxonomy->singular_name ?? $taxonomy->name }}" active-menu="acpt">
    <div class="mb-4">
        <h1 class="text-[23px] font-normal text-[#1d2327]">Edit {{ $taxonomy->singular_name ?? $taxonomy->name }}</h1>
    </div>

    <div class="max-w-4xl bg-white border border-[#c3c4c7] p-6">
        <form action="{{ route('admin.acpt.terms.update', [$taxonomy->slug, $term->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-[14px] text-[#1d2327] mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $term->name) }}" class="wp-input w-full md:w-1/2" required>
                    <p class="text-[12px] text-[#646970] mt-1">The name is how it appears on your site.</p>
                </div>

                <div>
                    <label class="block text-[14px] text-[#1d2327] mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $term->slug) }}" class="wp-input w-full md:w-1/2">
                    <p class="text-[12px] text-[#646970] mt-1">The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</p>
                </div>

                <div>
                    <label class="block text-[14px] text-[#1d2327] mb-1">Parent {{ $taxonomy->singular_name ?? $taxonomy->name }}</label>
                    <select name="parent_id" class="wp-input w-full md:w-1/2 h-8 py-0">
                        <option value="">None</option>
                        @foreach($allTerms as $t)
                            <option value="{{ $t->id }}" {{ old('parent_id', $term->parent_id) == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[12px] text-[#646970] mt-1">Assign a parent term to create a hierarchy. The term Jazz, for example, would be the child of Genre.</p>
                </div>

                <div>
                    <label class="block text-[14px] text-[#1d2327] mb-1">Description</label>
                    <textarea name="description" rows="5" class="wp-input w-full md:w-1/2">{{ old('description', $term->description) }}</textarea>
                    <p class="text-[12px] text-[#646970] mt-1">The description is not prominent by default; however, some themes may show it.</p>
                </div>

                <div class="pt-4 border-t border-[#f0f0f1]">
                    <button type="submit" class="wp-btn-primary">Update</button>
                    <a href="{{ route('admin.acpt.terms.index', $taxonomy->slug) }}" class="ml-4 text-[#2271b1] text-[13px] underline">Back to {{ $taxonomy->name }}</a>
                </div>
            </div>
        </form>
    </div>
</x-cms-dashboard::layouts.admin>
