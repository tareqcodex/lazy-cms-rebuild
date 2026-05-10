<x-cms-dashboard::layouts.admin title="Shop Settings" active-menu="shop">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-[23px] font-normal text-[#1d2327]">Shop Settings</h1>
    </div>

    @if(session('success'))
        <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-4 mb-5 text-[#2c3338] text-[13px]">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.shop.settings.save') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
            <!-- Tabs / Sidebar -->
            <div class="lg:col-span-1">
                <nav class="space-y-1">
                    <a href="#general" class="block px-3 py-2 bg-[#2271b1] text-white rounded font-semibold text-[13px]">General</a>
                    <a href="#payments" class="block px-3 py-2 text-[#2271b1] hover:bg-white rounded text-[13px]">Payments</a>
                    <a href="#shipping" class="block px-3 py-2 text-[#2271b1] hover:bg-white rounded text-[13px]">Shipping</a>
                    <a href="#tax" class="block px-3 py-2 text-[#2271b1] hover:bg-white rounded text-[13px]">Tax</a>
                </nav>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-3 space-y-5">
                <!-- General Settings -->
                <div id="general" class="wp-metabox">
                    <div class="wp-metabox-header">General Configuration</div>
                    <div class="wp-metabox-content space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[13px] font-semibold mb-1">Currency</label>
                                <select name="currency" class="wp-input w-full">
                                    <option value="USD" {{ get_cms_option('shop_currency') === 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                    <option value="EUR" {{ get_cms_option('shop_currency') === 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                    <option value="GBP" {{ get_cms_option('shop_currency') === 'GBP' ? 'selected' : '' }}>Pound Sterling (£)</option>
                                    <option value="BDT" {{ get_cms_option('shop_currency') === 'BDT' ? 'selected' : '' }}>Bangladeshi Taka (৳)</option>
                                    <option value="INR" {{ get_cms_option('shop_currency') === 'INR' ? 'selected' : '' }}>Indian Rupee (₹)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold mb-1">Currency Symbol</label>
                                <input type="text" name="currency_symbol" value="{{ get_cms_option('shop_currency_symbol', '$') }}" class="wp-input w-full" placeholder="$">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-[#f0f0f1] pt-4">
                            <div>
                                <label class="block text-[13px] font-semibold mb-1">Weight Unit</label>
                                <select name="weight_unit" class="wp-input w-full">
                                    <option value="kg" {{ get_cms_option('shop_weight_unit') === 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="g" {{ get_cms_option('shop_weight_unit') === 'g' ? 'selected' : '' }}>g</option>
                                    <option value="lbs" {{ get_cms_option('shop_weight_unit') === 'lbs' ? 'selected' : '' }}>lbs</option>
                                    <option value="oz" {{ get_cms_option('shop_weight_unit') === 'oz' ? 'selected' : '' }}>oz</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold mb-1">Dimension Unit</label>
                                <select name="dimension_unit" class="wp-input w-full">
                                    <option value="cm" {{ get_cms_option('shop_dimension_unit') === 'cm' ? 'selected' : '' }}>cm</option>
                                    <option value="mm" {{ get_cms_option('shop_dimension_unit') === 'mm' ? 'selected' : '' }}>mm</option>
                                    <option value="in" {{ get_cms_option('shop_dimension_unit') === 'in' ? 'selected' : '' }}>in</option>
                                    <option value="yd" {{ get_cms_option('shop_dimension_unit') === 'yd' ? 'selected' : '' }}>yd</option>
                                </select>
                            </div>
                        </div>

                        <div class="border-t border-[#f0f0f1] pt-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="enable_coupons" value="1" {{ get_cms_option('shop_enable_coupons') === '1' ? 'checked' : '' }} class="rounded border-[#8c8f94] text-[#2271b1]">
                                <span class="text-[13px]">Enable the use of coupon codes</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Shipping Settings (Simplified for now) -->
                <div id="shipping" class="wp-metabox">
                    <div class="wp-metabox-header">Shipping Settings</div>
                    <div class="wp-metabox-content space-y-4">
                        <div>
                            <label class="block text-[13px] font-semibold mb-1">Flat Rate Shipping Cost</label>
                            <input type="number" step="0.01" name="flat_rate_cost" value="{{ get_cms_option('shop_flat_rate_cost', '0') }}" class="wp-input w-full">
                            <p class="text-[11px] text-[#646970] mt-1">Default shipping cost if no other rules apply.</p>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold mb-1">Free Shipping Above</label>
                            <input type="number" step="0.01" name="free_shipping_threshold" value="{{ get_cms_option('shop_free_shipping_threshold', '0') }}" class="wp-input w-full">
                            <p class="text-[11px] text-[#646970] mt-1">Enter 0 to disable free shipping threshold.</p>
                        </div>
                    </div>
                </div>

                <!-- Tax Settings (Simplified for now) -->
                <div id="tax" class="wp-metabox">
                    <div class="wp-metabox-header">Tax Configuration</div>
                    <div class="wp-metabox-content space-y-4">
                        <div>
                            <label class="flex items-center space-x-2 cursor-pointer mb-4">
                                <input type="checkbox" name="calc_taxes" value="1" {{ get_cms_option('shop_calc_taxes') === '1' ? 'checked' : '' }} class="rounded border-[#8c8f94] text-[#2271b1]">
                                <span class="text-[13px] font-semibold">Enable tax calculations</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[13px] font-semibold mb-1">Tax Rate (%)</label>
                                <input type="number" step="0.01" name="tax_rate" value="{{ get_cms_option('shop_tax_rate', '0') }}" class="wp-input w-full">
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold mb-1">Tax Display</label>
                                <select name="tax_display" class="wp-input w-full">
                                    <option value="excl" {{ get_cms_option('shop_tax_display') === 'excl' ? 'selected' : '' }}>Excluding Tax</option>
                                    <option value="incl" {{ get_cms_option('shop_tax_display') === 'incl' ? 'selected' : '' }}>Including Tax</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="wp-btn-primary px-10 h-10 text-[14px]">Save Shop Settings</button>
                </div>
            </div>
        </div>
    </form>
</x-cms-dashboard::layouts.admin>
