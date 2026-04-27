<footer class="bg-secondary text-white pt-16 pb-8">
    <div class="site-container">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <!-- About -->
            <div class="col-span-1 md:col-span-2">
                <a href="{{ url('/') }}" class="text-2xl font-black tracking-tighter text-white mb-6 block">
                    {{ strtoupper(get_cms_option('site_title', 'LAZY')) }}<span class="text-primary">THEME</span>
                </a>
                <p class="text-gray-400 max-w-md leading-relaxed">
                    {{ get_cms_option('site_description', 'Building the next generation of content management experiences. Lazy CMS allows you to create, manage, and scale your digital presence with ease and speed.') }}
                </p>
                <div class="flex space-x-4 mt-8">
                    @if(get_cms_option('facebook_url'))
                        <a href="{{ get_cms_option('facebook_url') }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                    @endif
                    @if(get_cms_option('twitter_url'))
                        <a href="{{ get_cms_option('twitter_url') }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                    @endif
                    @if(get_cms_option('instagram_url'))
                        <a href="{{ get_cms_option('instagram_url') }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-bold mb-6">Quick Links</h4>
                <ul class="space-y-4 text-gray-400">
                    @foreach(get_lazy_menu('footer') as $item)
                        <li><a href="{{ $item->url }}" class="hover:text-white transition">{{ $item->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-lg font-bold mb-6">Contact</h4>
                <ul class="space-y-4 text-gray-400">
                    <li class="flex items-center space-x-3">
                        <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                        <span>{{ get_cms_option('contact_email', 'hello@lazytheme.com') }}</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                        <span>{{ get_cms_option('contact_address', 'Dhaka, Bangladesh') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} {{ get_cms_option('site_title', 'Lazy Theme') }}. All rights reserved.</p>
            <p>Designed with ❤️ using Lazy CMS</p>
        </div>
    </div>
</footer>
