<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Add New Role - Lazy CMS</x-slot>

    <div class="px-2">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-6">Add New Role</h1>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="max-w-[800px]">
            @csrf
            
            <table class="w-full border-separate border-spacing-y-6">
                <!-- Name -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="name" class="text-[14px] font-semibold text-[#1d2327]">Name <span class="text-[#d72828]">*</span></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" required class="wp-input w-[400px] h-8 shadow-sm">
                        <p class="text-[12px] text-[#646970] mt-1">Example: Manager, Editor</p>
                    </td>
                </tr>

                <!-- Slug -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="slug" class="text-[14px] font-semibold text-[#1d2327]">Slug <span class="text-[#d72828]">*</span></label>
                    </th>
                    <td>
                        <input type="text" name="slug" id="slug" required class="wp-input w-[400px] h-8 shadow-sm">
                        <p class="text-[12px] text-[#646970] mt-1">Unique identifier (e.g., manager)</p>
                    </td>
                </tr>

                <!-- Description -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="description" class="text-[14px] font-semibold text-[#1d2327]">Description</label>
                    </th>
                    <td>
                        <textarea name="description" id="description" rows="3" class="wp-input w-[400px] shadow-sm"></textarea>
                    </td>
                </tr>

                <!-- Privileges (Permissions) -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Privileges</label>
                    </th>
                    <td>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($permissions as $permission)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="w-4 h-4 mr-2">
                                    <span class="text-[13px] text-[#1d2327]">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                </tr>
            </table>

            <div class="mt-8 pt-6 border-t border-[#c3c4c7]">
                <button type="submit" class="wp-btn-primary h-[32px] px-4 font-semibold text-[13px]">Add New Role</button>
            </div>
        </form>
    </div>
</x-cms-dashboard::layouts.admin>
