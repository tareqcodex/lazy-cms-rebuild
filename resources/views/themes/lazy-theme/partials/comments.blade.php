<div class="mt-20 pt-10 border-t border-gray-100">
    <h3 class="text-3xl font-black mb-10 tracking-tighter text-gray-900">
        Comments ({{ $post->comments->count() }})
    </h3>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 text-green-700 rounded-2xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Comment List -->
    <div class="space-y-10 mb-16">
        @forelse($post->comments as $comment)
            <div class="flex gap-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-primary font-black text-xl">
                    {{ substr($comment->name, 0, 1) }}
                </div>
                <div class="flex-grow">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-gray-900">{{ $comment->name }}</h4>
                        <span class="text-xs text-gray-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        {{ $comment->comment }}
                    </p>
                    
                    @foreach($comment->replies as $reply)
                        <div class="mt-8 pl-8 border-l-2 border-gray-50 flex gap-6">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-black text-lg">
                                {{ substr($reply->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-bold text-gray-900">{{ $reply->name }}</h4>
                                    <span class="text-xs text-gray-400 font-medium">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-600 leading-relaxed">
                                    {{ $reply->comment }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-gray-400 italic">No comments yet. Be the first to share your thoughts!</p>
        @endforelse
    </div>

    <!-- Comment Form -->
    <div class="bg-gray-50 rounded-[2.5rem] p-8 lg:p-12">
        <h4 class="text-2xl font-bold mb-8 text-gray-900">Leave a Reply</h4>
        <form action="{{ route('frontend.comment.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @guest
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Your Name</label>
                        <input type="text" name="name" required class="w-full bg-white border-transparent focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl px-6 py-4 transition-all outline-none text-gray-600" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-white border-transparent focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl px-6 py-4 transition-all outline-none text-gray-600" placeholder="john@example.com">
                    </div>
                @else
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Logged in as <span class="font-bold text-gray-900">{{ auth()->user()->name }}</span></p>
                    </div>
                @endguest
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Comment</label>
                <textarea name="comment" rows="5" required class="w-full bg-white border-transparent focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-[2rem] px-6 py-4 transition-all outline-none text-gray-600" placeholder="What's on your mind?"></textarea>
            </div>

            <button type="submit" class="bg-primary text-white px-10 py-5 rounded-full text-sm font-bold shadow-xl shadow-blue-500/20 hover:scale-105 transition transform active:scale-95">
                Post Comment
            </button>
        </form>
    </div>
</div>
