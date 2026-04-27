<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
    @forelse($posts as $post)
        <article class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100 group">
            <div class="relative overflow-hidden h-64">
                <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" 
                     class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700" 
                     alt="{{ $post->title }}">
                <div class="absolute top-4 left-4">
                    <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest text-primary">
                        {{ $post->type }}
                    </span>
                </div>
            </div>
            <div class="p-8">
                <h3 class="text-2xl font-bold mb-4 line-clamp-2 hover:text-primary transition cursor-pointer">
                    <a href="{{ get_lazy_permalink($post) }}">
                        {{ $post->title }}
                    </a>
                </h3>
                <p class="text-gray-500 mb-6 line-clamp-3 leading-relaxed">
                    {{ get_lazy_excerpt($post, 120) }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-400 flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        {{ $post->created_at->format('M d, Y') }}
                    </span>
                    <a href="{{ get_lazy_permalink($post) }}" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                <i data-lucide="folder-open" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No posts found</h3>
            <p class="text-gray-500">We couldn't find any content matching your criteria.</p>
        </div>
    @endforelse
</div>
