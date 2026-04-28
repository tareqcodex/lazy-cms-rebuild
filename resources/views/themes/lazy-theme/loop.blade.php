<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
    @foreach($posts as $post)
        <article class="post-card flex flex-col group overflow-hidden">
            <!-- Image Area -->
            <div class="relative aspect-[16/9] overflow-hidden bg-slate-100 border-b border-slate-100">
                <a href="{{ get_lazy_permalink($post) }}" class="block h-full">
                    <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                         alt="{{ $post->title }}"
                         loading="lazy">
                </a>
            </div>

            <!-- Content Area -->
            <div class="p-8 flex flex-col flex-grow">
                <div class="flex items-center gap-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-4">
                    <span>{{ $post->type }}</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                </div>
                
                <h3 class="text-xl font-bold mb-4 group-hover:text-primary transition-colors leading-tight">
                    <a href="{{ get_lazy_permalink($post) }}">{{ $post->title }}</a>
                </h3>
                
                <p class="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                    {{ Str::limit(strip_tags($post->content), 120) }}
                </p>

                <div class="mt-auto">
                    <a href="{{ get_lazy_permalink($post) }}" class="text-xs font-black uppercase tracking-widest text-primary border-b-2 border-primary/20 hover:border-primary transition-all pb-1">
                        Read Story
                    </a>
                </div>
            </div>
        </article>
    @endforeach
</div>
