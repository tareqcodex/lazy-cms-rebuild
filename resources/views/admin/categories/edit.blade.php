<x-cms-dashboard::layouts.admin title="Edit Category">
    <div class="mb-4">
        <h1 class="text-[23px] font-normal text-[#1d2327]">Edit Category</h1>
    </div>

    @if(session('success'))
        <div class="bg-[#fff] border-l-4 border-[#00a32a] shadow-[0_1px_1px_rgba(0,0,0,.04)] p-3 mb-4 rounded-sm text-[13px]">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="max-w-4xl bg-white border border-[#c3c4c7] p-5">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <table class="w-full text-[14px]">
                <tr class="align-top border-b border-[#f0f0f1]">
                    <th class="w-[200px] text-left py-4 px-2 font-medium">Name</th>
                    <td class="py-4 px-2">
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="wp-input w-full max-w-[400px]" required>
                        <p class="text-[12px] text-[#646970] mt-1">The name is how it appears on your site.</p>
                    </td>
                </tr>

                <tr class="align-top border-b border-[#f0f0f1]">
                    <th class="w-[200px] text-left py-4 px-2 font-medium">Slug</th>
                    <td class="py-4 px-2">
                        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="wp-input w-full max-w-[400px]">
                        <p class="text-[12px] text-[#646970] mt-1">The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</p>
                    </td>
                </tr>

                <tr class="align-top border-b border-[#f0f0f1]">
                    <th class="w-[200px] text-left py-4 px-2 font-medium">Parent Category</th>
                    <td class="py-4 px-2">
                        <select name="parent_id" class="wp-input w-full max-w-[400px]">
                            <option value="">None</option>
                            @foreach($fullTree as $cat)
                                <option value="{{ $cat->id }}" {{ $cat->id == old('parent_id', $category->parent_id) ? 'selected' : '' }}>{{ str_repeat('— ', $cat->level ?? 0) }}{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[12px] text-[#646970] mt-1">Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.</p>
                    </td>
                </tr>

                <tr class="align-top">
                    <th class="w-[200px] text-left py-4 px-2 font-medium">Description</th>
                    <td class="py-4 px-2">
                        <textarea name="description" rows="5" class="wp-input w-full max-w-[400px]">{{ old('description', $category->description) }}</textarea>
                        <p class="text-[12px] text-[#646970] mt-1">The description is not prominent by default; however, some themes may show it.</p>
                    </td>
                </tr>
            </table>

            <div class="mt-6 flex items-center space-x-4">
                <button type="submit" class="wp-btn-primary">Update</button>
                <a href="{{ route('admin.categories.index') }}" class="text-[#2271b1] text-[13px] underline">Back to Categories</a>
            </div>
        </form>
    </div>
</x-cms-dashboard::layouts.admin>
