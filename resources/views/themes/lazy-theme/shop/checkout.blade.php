@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-white py-12 min-h-screen font-sans">
    <div class="max-w-[1140px] mx-auto px-4">
        
        <h1 class="text-[28px] font-bold text-[#2c3338] mb-8">Checkout</h1>

        @if(count($cart) > 0)
        <!-- Coupon Bar -->
        <div class="mb-8 bg-[#f7f6f7] border-t-[3px] border-[#1363df] p-4 text-[14px] text-[#515151]">
            <p>
                <i data-lucide="info" class="w-4 h-4 inline-block mr-1 text-[#1363df]"></i>
                Have a coupon? <button onclick="document.getElementById('coupon-form').classList.toggle('hidden')" class="text-[#1363df] hover:underline">Click here to enter your code</button>
            </p>
        </div>

        <div id="coupon-form" class="hidden mb-8 border border-[#d3ced2] p-6 rounded-[3px]">
            <p class="text-[14px] text-[#515151] mb-4">If you have a coupon code, please apply it below.</p>
            <div class="flex gap-2 max-w-md">
                <input type="text" placeholder="Coupon code" class="flex-grow border border-[#d3ced2] px-4 py-2.5 text-[14px] outline-none focus:border-[#1363df]">
                <button class="bg-[#ebe9eb] text-[#515151] px-6 py-2.5 rounded-[3px] font-bold text-[14px] hover:bg-[#dfdcde] transition-colors">Apply coupon</button>
            </div>
        </div>

        <form action="{{ route('shop.place-order') }}" method="POST">
            @csrf
            
            <div class="flex flex-col md:flex-row gap-12 mb-12">
                <!-- Left Column: Billing Details -->
                <div class="w-full md:w-1/2">
                    <h2 class="text-[20px] font-bold text-[#2c3338] border-b border-[#eee] pb-4 mb-6 uppercase tracking-tight">Billing details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-[#2c3338]">First name <span class="text-red-600">*</span></label>
                            <input type="text" name="billing_first_name" value="{{ old('billing_first_name') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-[#2c3338]">Last name <span class="text-red-600">*</span></label>
                            <input type="text" name="billing_last_name" value="{{ old('billing_last_name') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                        </div>
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">Company name (optional)</label>
                        <input type="text" name="billing_company" value="{{ old('billing_company') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">Country / Region <span class="text-red-600">*</span></label>
                        <select name="billing_country" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] bg-white focus:border-[#1363df] outline-none cursor-pointer">
                            <option value="US" {{ old('billing_country') == 'US' ? 'selected' : '' }}>United States (US)</option>
                            <option value="UK" {{ old('billing_country') == 'UK' ? 'selected' : '' }}>United Kingdom (UK)</option>
                            <option value="CA" {{ old('billing_country') == 'CA' ? 'selected' : '' }}>Canada (CA)</option>
                            <option value="BD" {{ old('billing_country') == 'BD' ? 'selected' : (old('billing_country') == '' ? 'selected' : '') }}>Bangladesh (BD)</option>
                            <option value="IN" {{ old('billing_country') == 'IN' ? 'selected' : '' }}>India (IN)</option>
                        </select>
                    </div>

                    <div class="space-y-2 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">Street address <span class="text-red-600">*</span></label>
                        <input type="text" name="billing_address_1" value="{{ old('billing_address_1') }}" placeholder="House number and street name" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none mb-2">
                        <input type="text" name="billing_address_2" value="{{ old('billing_address_2') }}" placeholder="Apartment, suite, unit, etc. (optional)" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">Town / City <span class="text-red-600">*</span></label>
                        <input type="text" name="billing_city" value="{{ old('billing_city') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">State <span class="text-red-600">*</span></label>
                        <select name="billing_state" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] bg-white focus:border-[#1363df] outline-none cursor-pointer">
                            <option value="California" {{ old('billing_state') == 'California' ? 'selected' : '' }}>California</option>
                            <option value="New York" {{ old('billing_state') == 'New York' ? 'selected' : '' }}>New York</option>
                            <option value="Texas" {{ old('billing_state') == 'Texas' ? 'selected' : '' }}>Texas</option>
                            <option value="Florida" {{ old('billing_state') == 'Florida' ? 'selected' : '' }}>Florida</option>
                        </select>
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">ZIP Code <span class="text-red-600">*</span></label>
                        <input type="text" name="billing_postcode" value="{{ old('billing_postcode') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">Phone <span class="text-red-600">*</span></label>
                        <input type="text" name="billing_phone" value="{{ old('billing_phone') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <label class="text-[14px] font-bold text-[#2c3338]">Email address <span class="text-red-600">*</span></label>
                        <input type="email" name="billing_email" value="{{ old('billing_email') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                    </div>
                </div>

                <!-- Right Column: Shipping Details -->
                <div class="w-full md:w-1/2">
                    <div class="mb-6">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" id="ship-different" name="ship_to_different_address" value="1" {{ old('ship_to_different_address') ? 'checked' : '' }} onchange="document.getElementById('shipping-form').classList.toggle('hidden')" class="w-4 h-4 border-[#ddd] rounded-sm text-[#1363df] focus:ring-0">
                            <span class="text-[20px] font-bold text-[#2c3338] uppercase tracking-tight">Ship to a different address?</span>
                        </label>
                    </div>

                    <div id="shipping-form" class="{{ old('ship_to_different_address') ? '' : 'hidden' }} space-y-4 mb-8 border-t border-[#eee] pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[14px] font-bold text-[#2c3338]">First name <span class="text-red-600">*</span></label>
                                <input type="text" name="shipping_first_name" value="{{ old('shipping_first_name') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[14px] font-bold text-[#2c3338]">Last name <span class="text-red-600">*</span></label>
                                <input type="text" name="shipping_last_name" value="{{ old('shipping_last_name') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-[#2c3338]">Country / Region <span class="text-red-600">*</span></label>
                            <select name="shipping_country" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] bg-white focus:border-[#1363df] outline-none">
                                <option value="US" {{ old('shipping_country') == 'US' ? 'selected' : '' }}>United States (US)</option>
                                <option value="BD" {{ old('shipping_country') == 'BD' ? 'selected' : '' }}>Bangladesh (BD)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[14px] font-bold text-[#2c3338]">Street address <span class="text-red-600">*</span></label>
                            <input type="text" name="shipping_address_1" value="{{ old('shipping_address_1') }}" placeholder="House number and street name" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none mb-2">
                            <input type="text" name="shipping_address_2" value="{{ old('shipping_address_2') }}" placeholder="Apartment, suite, unit, etc. (optional)" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-[#2c3338]">Town / City <span class="text-red-600">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-[#2c3338]">State <span class="text-red-600">*</span></label>
                            <select name="shipping_state" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] bg-white focus:border-[#1363df] outline-none cursor-pointer">
                                <option value="California" {{ old('shipping_state') == 'California' ? 'selected' : '' }}>California</option>
                                <option value="New York" {{ old('shipping_state') == 'New York' ? 'selected' : '' }}>New York</option>
                                <option value="Texas" {{ old('shipping_state') == 'Texas' ? 'selected' : '' }}>Texas</option>
                                <option value="Florida" {{ old('shipping_state') == 'Florida' ? 'selected' : '' }}>Florida</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-[#2c3338]">ZIP Code <span class="text-red-600">*</span></label>
                            <input type="text" name="shipping_postcode" value="{{ old('shipping_postcode') }}" class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none">
                        </div>
                    </div>

                    <div class="space-y-2 mt-6">
                        <h2 class="text-[16px] font-bold text-[#2c3338] mb-4">Order notes (optional)</h2>
                        <textarea name="order_comments" rows="3" placeholder="Notes about your order, e.g. special notes for delivery." class="w-full border border-[#ddd] rounded-sm px-3 py-2 text-[14px] focus:border-[#1363df] outline-none resize-none">{{ old('order_comments') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Full Width Order Section -->
            <div class="mt-12">
                <h2 class="text-[20px] font-bold text-[#2c3338] mb-6 uppercase tracking-tight">Your order</h2>
                
                <div class="border border-[#eee] bg-white">
                    <table class="w-full border-collapse text-[14px]">
                        <thead>
                            <tr class="bg-[#fcfcfc] border-b border-[#eee]">
                                <th class="text-left p-4 font-bold text-[#2c3338]">Product</th>
                                <th class="text-right p-4 font-bold text-[#2c3338]">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach($cart as $item)
                                @php 
                                    $price = $item['sale_price'] ?? $item['price'];
                                    $itemSubtotal = $price * $item['quantity'];
                                    $subtotal += $itemSubtotal;
                                @endphp
                                <tr class="border-b border-[#eee]">
                                    <td class="p-4 text-[#515151]">
                                        {{ $item['name'] }} <span class="font-bold text-[#2c3338]">× {{ $item['quantity'] }}</span>
                                    </td>
                                    <td class="p-4 text-right font-medium text-[#2c3338]">
                                        ${{ number_format($itemSubtotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            
                            @php
                                $shipping = (float) get_cms_option('shop_flat_rate_cost', 0);
                                $taxRate = (float) get_cms_option('shop_tax_rate', 0);
                                $freeShippingThreshold = (float) get_cms_option('shop_free_shipping_threshold', 0);
                                
                                if ($freeShippingThreshold > 0 && $subtotal >= $freeShippingThreshold) {
                                    $shipping = 0;
                                }
                                
                                $tax = $subtotal * ($taxRate / 100);
                                $total = $subtotal + $shipping + $tax;
                            @endphp

                            <tr class="border-b border-[#eee]">
                                <th class="text-left p-4 font-bold text-[#2c3338]">Subtotal</th>
                                <td class="text-right p-4 font-bold text-[#2c3338]">${{ number_format($subtotal, 2) }}</td>
                            </tr>

                            <tr class="border-b border-[#eee]">
                                <th class="text-left p-4 font-bold text-[#2c3338]">Shipping</th>
                                <td class="text-right p-4 text-[#515151]">
                                    Flat rate: <span class="font-bold text-[#2c3338]">${{ number_format($shipping, 2) }}</span>
                                </td>
                            </tr>

                            <tr class="bg-[#fcfcfc]">
                                <th class="text-left p-4 font-bold text-[#2c3338]">Total</th>
                                <td class="text-right p-4 text-[18px] font-bold text-[#2c3338]">${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Payment Section -->
                    <div class="p-8 border-t border-[#eee]">
                        <div class="max-w-4xl">
                            <div class="bg-[#f7f6f7] p-6 mb-8 relative">
                                <div class="absolute -top-3 left-6 w-0 h-0 border-l-[12px] border-l-transparent border-r-[12px] border-r-transparent border-b-[12px] border-b-[#f7f6f7]"></div>
                                
                                <div class="space-y-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="payment_method" value="cod" checked class="w-4 h-4 text-[#1363df] focus:ring-0">
                                        <span class="text-[14px] font-bold text-[#2c3338]">Cash on delivery</span>
                                    </label>
                                    <div class="bg-[#f4f4f4] p-4 text-[14px] text-[#515151]">
                                        Pay with cash upon delivery.
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-[13px] text-[#777] mb-8 leading-relaxed max-w-2xl">
                                Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#" class="text-[#1363df] hover:underline">privacy policy</a>.
                            </p>

                            <button type="submit" class="bg-[#1363df] text-white px-10 py-4 rounded-sm font-bold text-[16px] hover:bg-[#005ba6] transition-all uppercase">
                                Place order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @else
        <div class="bg-white p-20 text-center border border-[#eee]">
            <h2 class="text-[24px] font-bold text-[#2c3338] mb-4">Your cart is empty</h2>
            <p class="text-[#777] mb-8">Add products to your cart before checking out.</p>
            <a href="{{ url('/product') }}" class="inline-block bg-[#1363df] text-white px-8 py-3 rounded-sm font-bold hover:bg-[#005ba6] transition-colors uppercase">Return to shop</a>
        </div>
        @endif
    </div>
</div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.querySelector('form[action="{{ route('shop.place-order') }}"]');
    const shipDifferentCheckbox = document.getElementById('ship-different');
    const shippingForm = document.getElementById('shipping-form');

    // Ensure shipping form visibility based on checkbox state on load
    if (shipDifferentCheckbox && shipDifferentCheckbox.checked) {
        shippingForm.classList.remove('hidden');
    }

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = checkoutForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            
            // Show loading state
            submitBtn.innerText = 'Processing...';
            submitBtn.disabled = true;
            
            const formData = new FormData(checkoutForm);
            
            fetch(checkoutForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok && response.status !== 422) {
                    throw new Error('Server error');
                }
                return response.json();
            })
            .then(data => {
                if (data.errors) {
                    let errorList = '<ul class="text-left list-disc pl-5 space-y-1">';
                    Object.keys(data.errors).forEach(key => {
                        errorList += `<li>${data.errors[key][0]}</li>`;
                    });
                    errorList += '</ul>';

                    Swal.fire({
                        title: 'Validation Error',
                        html: errorList,
                        icon: 'error',
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#1363df'
                    });

                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Fallback redirect if success but no specific redirect URL
                    window.location.href = "{{ url('/order-confirmation') }}/" + (data.order_id || '');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong while processing your order. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#1363df'
                });
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>
@endpush
