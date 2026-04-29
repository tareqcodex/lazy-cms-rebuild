<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Settings - Lazy CMS</x-slot>

    <div class="px-2">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-4">Settings</h1>
        
        @include('cms-dashboard::admin.settings.nav')

        @if (session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="max-w-[800px]">
            @csrf
            {!! do_lazy_action('lazy_settings_form_top') !!}

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
                        <p class="text-[12px] text-[#646970]">This address is used for admin purposes.</p>
                    </td>
                </tr>

                <!-- Homepage Selection -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="home_page_id" class="text-[14px] font-semibold text-[#1d2327]">Select your Home page</label>
                    </th>
                    <td>
                        <select name="home_page_id" id="home_page_id" class="wp-input w-[400px] h-8 py-0 shadow-sm mb-1">
                            <option value="">Latest Blog Posts (Default)</option>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}" {{ ($settings['home_page_id'] ?? '') == $page->id ? 'selected' : '' }}>
                                    {{ $page->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[12px] text-[#646970]">Choose what to display on your site's home page. If none selected, the latest blog posts will be shown.</p>
                    </td>
                </tr>

                <!-- Membership -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Who can Sign Up</label>
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
                                {{ ($settings['login_theme'] ?? 'breeze') == 'funny' ? 'selected' : '' }}>Funny Theme
                            </option>
                            <option value="breeze"
                                {{ ($settings['login_theme'] ?? 'breeze') == 'breeze' ? 'selected' : '' }}>Breeze Style
                            </option>
                            <option value="wp"
                                {{ ($settings['login_theme'] ?? 'breeze') == 'wp' ? 'selected' : '' }}>WP Classic
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

            @include('cms-dashboard::components.admin.dynamic-fields')

            <div class="pt-8 border-t border-gray-100 mt-8">
                <h3 class="text-[18px] font-medium text-[#1d2327] mb-6">Media & Image Optimization</h3>
                
                <table class="w-full border-separate border-spacing-y-6">
                    <!-- Page Cache -->
                    <tr>
                        <th scope="row" class="w-[200px] text-left align-top pt-2">
                            <label class="text-[14px] font-semibold text-[#1d2327]">Static Caching</label>
                        </th>
                        <td>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_page_cache" value="1" {{ ($settings['enable_page_cache'] ?? '0') == '1' ? 'checked' : '' }} class="w-4 h-4 mr-2">
                                <span class="text-[14px] text-[#1d2327]">Enable response caching for frontend</span>
                            </label>
                            <p class="text-[12px] text-[#646970] mt-1">Drastically improves speed by caching HTML output. Cache is cleared when you save settings or update content.</p>
                        </td>
                    </tr>

                    <!-- Auto WebP -->
                    <tr>
                        <th scope="row" class="w-[200px] text-left align-top pt-2">
                            <label class="text-[14px] font-semibold text-[#1d2327]">WebP Conversion</label>
                        </th>
                        <td>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="image_auto_webp" value="1" {{ ($settings['image_auto_webp'] ?? '1') == '1' ? 'checked' : '' }} class="w-4 h-4 mr-2">
                                <span class="text-[14px] text-[#1d2327]">Auto convert uploaded images to WebP</span>
                            </label>
                            <p class="text-[12px] text-[#646970] mt-1">Recommended for better performance and smaller file sizes.</p>
                        </td>
                    </tr>

                    <!-- Image Quality -->
                    <tr>
                        <th scope="row" class="w-[200px] text-left align-top pt-2">
                            <label for="image_quality" class="text-[14px] font-semibold text-[#1d2327]">Image Quality</label>
                        </th>
                        <td>
                            <input type="number" name="image_quality" id="image_quality" value="{{ $settings['image_quality'] ?? '80' }}" class="wp-input w-[100px] h-8 shadow-sm" min="1" max="100">
                            <span class="text-[12px] text-[#646970] ml-2">(0-100) Lower quality means smaller file sizes. 80 is recommended.</span>
                        </td>
                    </tr>

                    <!-- Max Width -->
                    <tr>
                        <th scope="row" class="w-[200px] text-left align-top pt-2">
                            <label for="image_max_width" class="text-[14px] font-semibold text-[#1d2327]">Max Image Width</label>
                        </th>
                        <td>
                            <div class="flex items-center gap-2">
                                <input type="number" name="image_max_width" id="image_max_width" value="{{ $settings['image_max_width'] ?? '1920' }}" class="wp-input w-[100px] h-8 shadow-sm">
                                <span class="text-[12px] text-[#646970]">Pixels. Images wider than this will be automatically resized. 1920 is default.</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Bulk Optimize Action -->
                    <tr>
                        <th scope="row" class="w-[200px] text-left align-top pt-2">
                            <label class="text-[14px] font-semibold text-[#1d2327]">Bulk Actions</label>
                        </th>
                        <td>
                            <button type="button" id="bulk-optimize-btn" class="wp-btn-secondary px-4 h-8">
                                Optimize Existing Images Now
                            </button>
                            <p class="text-[12px] text-[#b32d2e] mt-2 font-medium">Caution: This will replace all existing original images with optimized versions (and WebP if enabled). This process cannot be undone.</p>
                            <div id="optimization-status" class="hidden mt-2 text-[13px] font-medium">
                                <span class="text-[#2271b1]">Optimizing images, please wait...</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {!! do_lazy_action('lazy_settings_form_bottom') !!}

            <div class="pt-6 border-t border-gray-100 mt-6">
                <button type="submit" class="wp-btn-primary px-4 h-8 font-semibold">Save Changes</button>
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
                
                // Media Modal for settings
                document.querySelectorAll('.open-media-for-setting').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const target = this.getAttribute('data-target');
                        window.openMediaModal(function(media) {
                            const input = document.getElementById('input-' + target);
                            if (input) input.value = media.path;
                            const preview = document.getElementById('media-preview-' + target);
                            if (preview) {
                                preview.innerHTML = `<img src="/storage/${media.path}" class="max-w-full max-h-full object-contain">`;
                                preview.classList.remove('hidden');
                            }
                        });
                    });
                });

                // Bulk Optimize Logic
                const optimizeBtn = document.getElementById('bulk-optimize-btn');
                const statusDiv = document.getElementById('optimization-status');

                if (optimizeBtn) {
                    optimizeBtn.addEventListener('click', function() {
                        if (!confirm('Are you sure you want to optimize all existing images? This will replace original files and may take some time.')) return;

                        optimizeBtn.disabled = true;
                        optimizeBtn.innerText = 'Processing...';
                        statusDiv.classList.remove('hidden');

                        fetch("{{ route('admin.media.bulk-optimize') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('An unexpected error occurred.');
                        })
                        .finally(() => {
                            optimizeBtn.disabled = false;
                            optimizeBtn.innerText = 'Optimize Existing Images Now';
                            statusDiv.classList.add('hidden');
                        });
                    });
                }
            });
        </script>
    @endpush
</x-cms-dashboard::layouts.admin>
