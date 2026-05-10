@extends($activeTheme . '.layouts.app')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Shopping Cart</h1>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-8 text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Your cart is empty</h2>
                <p class="text-slate-500 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="/" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <form action="{{ route('shop.cart.update') }}" method="POST" id="cart-form">
                        @csrf
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                            <ul class="divide-y divide-slate-100">
                                @foreach($cart as $key => $item)
                                    <li class="p-6 flex items-center">
                                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-lg border border-slate-100 bg-slate-50">
                                            <img src="{{ asset($item['thumbnail'] ?: 'assets/images/placeholder.jpg') }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center">
                                        </div>

                                        <div class="ml-6 flex flex-1 flex-col">
                                            <div class="flex justify-between text-base font-bold text-slate-900">
                                                <h3>{{ $item['name'] }}</h3>
                                                <p class="ml-4">{{ get_cms_option('shop_currency_symbol', '$') }}{{ number_format(($item['sale_price'] ?? $item['price']) * $item['quantity'], 2) }}</p>
                                            </div>
                                            <p class="mt-1 text-sm text-slate-500">{{ $item['sku'] ?? 'N/A' }}</p>
                                            
                                            <div class="flex flex-1 items-end justify-between text-sm mt-4">
                                                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                                    <button type="button" onclick="this.nextElementSibling.stepDown(); this.closest('form').submit();" class="px-3 py-1 hover:bg-slate-100 text-slate-600 font-bold">-</button>
                                                    <input type="number" name="quantity[{{ $key }}]" value="{{ $item['quantity'] }}" min="1" class="w-12 text-center bg-transparent border-none focus:ring-0 text-slate-900 font-semibold" onchange="this.closest('form').submit()">
                                                    <button type="button" onclick="this.previousElementSibling.stepUp(); this.closest('form').submit();" class="px-3 py-1 hover:bg-slate-100 text-slate-600 font-bold">+</button>
                                                </div>

                                                <div class="flex">
                                                    <a href="{{ route('shop.cart.remove', $key) }}" class="font-semibold text-rose-600 hover:text-rose-500 transition-colors flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Remove
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 sticky top-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">Order Summary</h2>
                        
                        <div class="flow-root">
                            <dl class="-my-4 divide-y divide-slate-100">
                                @php
                                    $subtotal = 0;
                                    foreach($cart as $item) {
                                        $price = $item['sale_price'] ?? $item['price'];
                                        $subtotal += $price * $item['quantity'];
                                    }
                                    $shipping = (float) get_cms_option('shop_flat_rate_cost', 0);
                                    $freeShippingThreshold = (float) get_cms_option('shop_free_shipping_threshold', 0);
                                    if ($freeShippingThreshold > 0 && $subtotal >= $freeShippingThreshold) {
                                        $shipping = 0;
                                    }
                                    $taxRate = (float) get_cms_option('shop_tax_rate', 0);
                                    $tax = $subtotal * ($taxRate / 100);
                                    $total = $subtotal + $shipping + $tax;
                                @endphp
                                
                                <div class="flex items-center justify-between py-4">
                                    <dt class="text-sm text-slate-500 font-medium">Subtotal</dt>
                                    <dd class="text-sm font-bold text-slate-900">{{ get_cms_option('shop_currency_symbol', '$') }}{{ number_format($subtotal, 2) }}</dd>
                                </div>
                                
                                <div class="flex items-center justify-between py-4">
                                    <dt class="text-sm text-slate-500 font-medium">Shipping</dt>
                                    <dd class="text-sm font-bold text-slate-900">{{ $shipping > 0 ? get_cms_option('shop_currency_symbol', '$') . number_format($shipping, 2) : 'Free' }}</dd>
                                </div>

                                <div class="flex items-center justify-between py-4">
                                    <dt class="text-sm text-slate-500 font-medium">Tax ({{ $taxRate }}%)</dt>
                                    <dd class="text-sm font-bold text-slate-900">{{ get_cms_option('shop_currency_symbol', '$') }}{{ number_format($tax, 2) }}</dd>
                                </div>

                                <div class="flex items-center justify-between py-6">
                                    <dt class="text-base font-extrabold text-slate-900">Total</dt>
                                    <dd class="text-2xl font-black text-indigo-600">{{ get_cms_option('shop_currency_symbol', '$') }}{{ number_format($total, 2) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="mt-8">
                            <a href="{{ route('shop.checkout') }}" class="w-full flex items-center justify-center px-6 py-4 border border-transparent text-base font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                                Proceed to Checkout
                            </a>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-xs text-slate-400">Secure Checkout — No hidden fees</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
