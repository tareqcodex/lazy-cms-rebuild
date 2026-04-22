<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Settings - Lazy CMS</x-slot>

    <div class="px-2">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-6">Settings</h1>

        @if (session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="max-w-[800px]">
            @csrf

            <table class="w-full border-separate border-spacing-y-6">
                <!-- Site Title -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="site_title" class="text-[14px] font-semibold text-[#1d2327]">Site Title</label>
                    </th>
                    <td>
                        <input type="text" name="site_title" id="site_title"
                            value="{{ $settings['site_title'] ?? 'Lazy CMS' }}"
                            class="wp-input w-[400px] h-8 shadow-sm">
                    </td>
                </tr>

                <!-- Tagline -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="tagline" class="text-[14px] font-semibold text-[#1d2327]">Tagline</label>
                    </th>
                    <td>
                        <input type="text" name="tagline" id="tagline" value="{{ $settings['tagline'] ?? '' }}"
                            class="wp-input w-[400px] h-8 shadow-sm mb-1">
                        <p class="text-[12px] text-[#646970] italic">In a few words, explain what this site is about.
                            Example: “Just another WordPress site.”</p>
                    </td>
                </tr>

                <!-- Admin Email -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="admin_email" class="text-[14px] font-semibold text-[#1d2327]">Administration Email
                            Address</label>
                    </th>
                    <td>
                        <input type="email" name="admin_email" id="admin_email"
                            value="{{ $settings['admin_email'] ?? auth()->user()->email }}"
                            class="wp-input w-[400px] h-8 shadow-sm mb-1">
                        <p class="text-[12px] text-[#646970]">This address is used for admin purposes. If you change
                            this, an email will be sent to your new address to confirm it. The new address will not
                            become active until confirmed.</p>
                    </td>
                </tr>

                <!-- Membership -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Membership</label>
                    </th>
                    <td>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="users_can_register" id="users_can_register"
                                class="w-4 h-4 mr-2"
                                {{ ($settings['users_can_register'] ?? '0') == '1' ? 'checked' : '' }}>
                            <span class="text-[14px] text-[#1d2327]">Anyone can register</span>
                        </label>
                    </td>
                </tr>

                <!-- Themes Group -->
                <tr id="reg-theme-row">
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="registration_theme" class="text-[14px] font-semibold text-[#1d2327]">Choose
                            Registration theme</label>
                    </th>
                    <td>
                        <select name="registration_theme" id="registration_theme" class="wp-input w-[200px] h-8 py-0">
                            <option value="breeze"
                                {{ ($settings['registration_theme'] ?? 'breeze') == 'breeze' ? 'selected' : '' }}>Breeze
                                Style</option>
                            <option value="funny"
                                {{ ($settings['registration_theme'] ?? 'breeze') == 'funny' ? 'selected' : '' }}>Funny
                                Theme</option>
                            <option value="wp"
                                {{ ($settings['registration_theme'] ?? 'breeze') == 'wp' ? 'selected' : '' }}>WP Classic
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="login_theme" class="text-[14px] font-semibold text-[#1d2327]">Choose login
                            theme</label>
                    </th>
                    <td>
                        <select name="login_theme" id="login_theme" class="wp-input w-[200px] h-8 py-0">
                            <option value="funny"
                                {{ ($settings['login_theme'] ?? 'funny') == 'funny' ? 'selected' : '' }}>Funny Theme
                            </option>
                            <option value="breeze"
                                {{ ($settings['login_theme'] ?? 'funny') == 'breeze' ? 'selected' : '' }}>Breeze Style
                            </option>
                            <option value="wp"
                                {{ ($settings['login_theme'] ?? 'funny') == 'wp' ? 'selected' : '' }}>WP Classic
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- URLs Group -->
                <tr id="reg-url-row">
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="register_url" class="text-[14px] font-semibold text-[#1d2327]">Change registration
                            url</label>
                    </th>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-[#646970] text-[13px]">{{ url('/') }}/</span>
                            <input type="text" name="register_url" id="register_url"
                                value="{{ $settings['register_url'] ?? 'lazy-register' }}"
                                class="wp-input w-[280px] h-8 shadow-sm">
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="login_url" class="text-[14px] font-semibold text-[#1d2327]">Change login url</label>
                    </th>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-[#646970] text-[13px]">{{ url('/') }}/</span>
                            <input type="text" name="login_url" id="login_url"
                                value="{{ $settings['login_url'] ?? 'lazy-admin' }}"
                                class="wp-input w-[280px] h-8 shadow-sm">
                        </div>
                    </td>
                </tr>

                <!-- Default Role -->
                <tr id="default-role-row">
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="default_role" class="text-[14px] font-semibold text-[#1d2327]">New User Default
                            Role</label>
                    </th>
                    <td>
                        <select name="default_role" id="default_role" class="wp-input w-[200px] h-8 py-0">
                            <option value="subscriber"
                                {{ ($settings['default_role'] ?? '') == 'subscriber' ? 'selected' : '' }}>Subscriber
                            </option>
                            <option value="editor"
                                {{ ($settings['default_role'] ?? '') == 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="author"
                                {{ ($settings['default_role'] ?? '') == 'author' ? 'selected' : '' }}>Author</option>
                        </select>
                    </td>
                </tr>

                <!-- Timezone -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="timezone" class="text-[14px] font-semibold text-[#1d2327]">Timezone</label>
                    </th>
                    <td>
                        <select name="timezone" id="timezone" class="wp-input w-[250px] h-8 py-0 mb-1">
                            <option value="UTC+0">UTC+0</option>
                            <option value="Asia/Dhaka">UTC+6 (Dhaka)</option>
                        </select>
                        <p class="text-[12px] text-[#646970]">Choose either a city in the same timezone as you or a UTC
                            (Coordinated Universal Time) time offset.</p>
                        <p class="text-[12px] text-[#646970] mt-2">Universal time is <span
                                class="font-mono">{{ now()->format('Y-m-d H:i:s') }}</span>.</p>
                    </td>
                </tr>
            </table>

            <div class="mt-8 pt-6 border-t border-[#c3c4c7]">
                <button type="submit" class="wp-btn-primary h-[32px] px-4 font-semibold">Save Changes</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const registerCheckbox = document.getElementById('users_can_register');
                const regRows = [
                    document.getElementById('reg-theme-row'),
                    document.getElementById('reg-url-row'),
                    document.getElementById('default-role-row')
                ];

                function toggleRegistrationFields() {
                    const isVisible = registerCheckbox.checked;
                    regRows.forEach(row => {
                        if (row) {
                            row.style.display = isVisible ? 'table-row' : 'none';
                        }
                    });
                }

                // Initial check
                toggleRegistrationFields();

                // Listen for changes
                registerCheckbox.addEventListener('change', toggleRegistrationFields);
            });
        </script>
    @endpush
</x-cms-dashboard::layouts.admin>
