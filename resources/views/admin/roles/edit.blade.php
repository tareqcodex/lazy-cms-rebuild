<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Edit Role - Lazy CMS</x-slot>

    <div class="px-2">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-6">Edit Role</h1>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="max-w-[800px]">
            @csrf
            @method('PUT')
            
            <table class="w-full border-separate border-spacing-y-6">
                <!-- Name -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="name" class="text-[14px] font-semibold text-[#1d2327]">Name <span class="text-[#d72828]">*</span></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" value="{{ $role->name }}" required class="wp-input w-[400px] h-8 shadow-sm">
                    </td>
                </tr>

                <!-- Slug -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="slug" class="text-[14px] font-semibold text-[#1d2327]">Slug <span class="text-[#d72828]">*</span></label>
                    </th>
                    <td>
                        <input type="text" name="slug" id="slug" value="{{ $role->slug }}" required class="wp-input w-[400px] h-8 shadow-sm" {{ in_array($role->slug, ['administrator', 'subscriber']) ? 'readonly' : '' }}>
                        <p class="text-[12px] text-[#646970] mt-1">Unique identifier (e.g., manager)</p>
                    </td>
                </tr>

                <!-- Description -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="description" class="text-[14px] font-semibold text-[#1d2327]">Description</label>
                    </th>
                    <td>
                        <textarea name="description" id="description" rows="3" class="wp-input w-[400px] shadow-sm">{{ $role->description }}</textarea>
                    </td>
                </tr>

                <!-- Privileges (Permissions) -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Privileges</label>
                    </th>
                    <td>
                        <div class="space-y-6">
                            <!-- Core Permissions -->
                            <div>
                                <h4 class="text-[12px] font-bold text-[#1d2327] uppercase tracking-wider mb-3 border-b border-[#f0f0f1] pb-1">Core Permissions</h4>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    @foreach($corePermissions as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                class="w-4 h-4 mr-2" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <span class="text-[13px] text-[#1d2327]">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- CPT Permissions -->
                            @if($cptPermissions->count() > 0)
                            <div>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    @foreach($cptPermissions as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                class="w-4 h-4 mr-2" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <span class="text-[13px] text-[#1d2327]">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            <div class="flex gap-4 mt-8 pt-6 border-t border-[#c3c4c7]">
                <button type="submit" class="wp-btn-primary h-[32px] px-4 font-semibold text-[13px]">Update Role</button>
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-1 border border-[#ccd0d4] text-[#2c3338] hover:bg-[#f6f7f7] rounded-[3px] text-[13px] font-semibold flex items-center">Cancel</a>
            </div>
        </form>
    </div>
</x-cms-dashboard::layouts.admin>
