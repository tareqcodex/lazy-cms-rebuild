@extends($activeTheme . '.layouts.app')

@section('content')
<div class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex mb-8 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="/" class="hover:text-indigo-600">Home</a></li>
                <li>
                    <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                </li>
                <li><a href="/shop" class="hover:text-indigo-600">Shop</a></li>
                <li>
                    <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                </li>
                <li class="text-slate-900 truncate">{{ $post->title }}</li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
            <!-- Image gallery -->
            <div class="flex flex-col">
                <div class="w-full aspect-w-1 aspect-h-1 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 shadow-sm">
                    <img src="{{ asset($post->thumbnail ?: 'assets/images/placeholder.jpg') }}" alt="{{ $post->title }}" class="w-full h-full object-center object-cover">
                </div>
            </div>

            <!-- Product info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">{{ $post->title }}</h1>

                <div class="mt-6">
                    <h2 class="sr-only">Product information</h2>
                    <div class="flex items-center space-x-3">
                        @if($post->shopData && $post->shopData->sale_price)
                            <span class="text-3xl font-black text-indigo-600">{{ get_cms_option('shop_currency_symbol', '$') }}{{ number_format($post->shopData->sale_price, 2) }}</span>
                            <span class="text-xl font-medium text-slate-400 line-through">{{ get_cms_option('shop_currency_symbol', '$') }}{{ number_format($post->shopData->price, 2) }}</span>
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Sale</span>
                        @else
                            <span class="text-3xl font-black text-slate-900">{{ get_cms_option('shop_currency_symbol', '$') }}{{ number_format($post->shopData->price ?? 0, 2) }}</span>
                        @endif
                    </div>
                </div>

                <!-- SKU and Stock -->
                <div class="mt-4 flex items-center space-x-4 text-sm font-medium">
                    @if($post->shopData && $post->shopData->sku)
                        <span class="text-slate-500">SKU: <span class="text-slate-900">{{ $post->shopData->sku }}</span></span>
                    @endif

                    @if($post->shopData)
                        @if($post->shopData->manage_stock)
                            @if($post->shopData->stock_quantity > 0)
                                <span class="text-emerald-600 flex items-center">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                    {{ $post->shopData->stock_quantity }} in stock
                                </span>
                            @else
                                <span class="text-rose-600 flex items-center">
                                    <span class="w-2 h-2 bg-rose-500 rounded-full mr-2"></span>
                                    Out of stock
                                </span>
                            @endif
                        @else
                             <span class="text-emerald-600 flex items-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                In Stock
                            </span>
                        @endif
                    @endif
                </div>

                <div class="mt-8">
                    <div class="prose prose-slate prose-sm text-slate-600 leading-relaxed">
                        {!! $post->excerpt !!}
                    </div>
                </div>

                <form action="{{ route('shop.cart.add') }}" method="POST" class="mt-10">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $post->id }}">
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-slate-50 h-14">
                            <button type="button" onclick="this.nextElementSibling.stepDown();" class="px-4 py-2 hover:bg-slate-100 text-slate-600 font-bold transition-colors border-r border-slate-200">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ ($post->shopData && $post->shopData->manage_stock) ? $post->shopData->stock_quantity : 99 }}" class="w-16 text-center bg-transparent border-none focus:ring-0 text-slate-900 font-bold">
                            <button type="button" onclick="this.previousElementSibling.stepUp();" class="px-4 py-2 hover:bg-slate-100 text-slate-600 font-bold transition-colors border-l border-slate-200">+</button>
                        </div>

                        <button type="submit" 
                            @if($post->shopData && $post->shopData->manage_stock && $post->shopData->stock_quantity <= 0) disabled @endif
                            class="flex-1 bg-indigo-600 border border-transparent rounded-xl py-4 px-8 flex items-center justify-center text-base font-black text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-lg shadow-indigo-200 disabled:bg-slate-300 disabled:shadow-none disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Add to Cart
                        </button>
                    </div>
                </form>

                <!-- Features list -->
                <section aria-labelledby="details-heading" class="mt-12 border-t border-slate-100 pt-8">
                    <h2 id="details-heading" class="sr-only">Additional details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-600">Free shipping available</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-600">Secure payment processing</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Description Section -->
        <div class="mt-20 border-t border-slate-100 pt-16">
            <h2 class="text-2xl font-black text-slate-900 mb-8">Description</h2>
            <div class="prose prose-indigo max-w-none text-slate-600">
                {!! $post->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
