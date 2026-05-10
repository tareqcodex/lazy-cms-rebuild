<?php

namespace Acme\CmsDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Acme\CmsDashboard\Models\Post;
use Acme\CmsDashboard\Models\Product;
use Acme\CmsDashboard\Models\Order;
use Acme\CmsDashboard\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopFrontendController extends Controller
{
    protected function resolveThemeView($view)
    {
        $activeTheme = get_cms_option('active_theme', 'lazy-theme');
        $appView = "themes.{$activeTheme}.ecommerce.{$view}";
        if (view()->exists($appView)) return $appView;

        $packageView = "cms-dashboard::themes.{$activeTheme}.ecommerce.{$view}";
        if (view()->exists($packageView)) return $packageView;

        return "cms-dashboard::themes.lazy-theme.ecommerce.{$view}";
    }

    public function cart()
    {
        $cart = Session::get('lazy_cart', []);
        return view($this->resolveThemeView('cart'), compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $variationId = $request->input('variation_id');

        $product = Product::with('shopData')->findOrFail($productId);
        $shopData = $product->shopData;

        // Inventory Check
        if ($shopData && $shopData->manage_stock) {
            $cart = Session::get('lazy_cart', []);
            $cartKey = $variationId ? "{$productId}_{$variationId}" : $productId;
            $currentInCart = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
            
            if (($currentInCart + $quantity) > $shopData->stock_quantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sorry, only ' . $shopData->stock_quantity . ' items available in stock.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Sorry, only ' . $shopData->stock_quantity . ' items available in stock.');
            }
        }

        $cart = Session::get('lazy_cart', []);

        $cartKey = $variationId ? "{$productId}_{$variationId}" : $productId;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->title,
                'slug' => $product->slug,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'quantity' => $quantity,
                'thumbnail' => $product->featured_image,
                'variation_id' => $variationId,
                'sku' => $product->sku
            ];
        }

        Session::put('lazy_cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => get_lazy_cart_count()
            ]);
        }

        return redirect()->route('shop.cart')->with('success', 'Product added to cart!');
    }

    public function updateCart(Request $request)
    {
        $cart = Session::get('lazy_cart', []);
        $quantities = $request->input('quantity', []);

        foreach ($quantities as $key => $qty) {
            if (isset($cart[$key])) {
                if ($qty <= 0) {
                    unset($cart[$key]);
                } else {
                    $cart[$key]['quantity'] = $qty;
                }
            }
        }

        Session::put('lazy_cart', $cart);

        if ($request->ajax()) {
            $item_subtotals = [];
            foreach ($cart as $key => $item) {
                $price = $item['sale_price'] ?? $item['price'];
                $item_subtotals[$key] = number_format($price * $item['quantity'], 2);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart updated!',
                'cart_count' => get_lazy_cart_count(),
                'item_subtotals' => $item_subtotals,
                'subtotal' => number_format(get_lazy_cart_subtotal(), 2),
                'shipping' => number_format(get_lazy_cart_shipping(), 2),
                'total' => number_format(get_lazy_cart_total(), 2)
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function applyCoupon(Request $request)
    {
        $code = $request->input('coupon_code');
        
        // Basic logic for demonstration, we can expand this with a real Coupon model later
        if (strtoupper($code) === 'LAZY10') {
            Session::put('lazy_coupon', ['code' => $code, 'discount' => 10, 'type' => 'fixed']);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon applied successfully!',
                    'discount' => 10,
                    'cart_count' => get_lazy_cart_count(),
                    'shipping' => number_format(get_lazy_cart_shipping(), 2),
                    'total' => number_format(get_lazy_cart_total(), 2)
                ]);
            }
            return redirect()->back()->with('success', 'Coupon applied!');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.'
            ], 422);
        }
        return redirect()->back()->with('error', 'Invalid coupon code.');
    }

    public function removeFromCart(Request $request, $key)
    {
        $cart = Session::get('lazy_cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            Session::put('lazy_cart', $cart);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'cart_count' => get_lazy_cart_count(),
                'subtotal' => number_format(get_lazy_cart_subtotal(), 2),
                'shipping' => number_format(get_lazy_cart_shipping(), 2),
                'total' => number_format(get_lazy_cart_total(), 2)
            ]);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function checkout()
    {
        $cart = Session::get('lazy_cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.index')->with('error', 'Your cart is empty!');
        }
        return view($this->resolveThemeView('checkout'), compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $rules = [
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_phone' => 'required',
            'billing_address_1' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_postcode' => 'required',
            'billing_country' => 'required',
            'payment_method' => 'required',
        ];

        if ($request->has('ship_to_different_address')) {
            $rules['shipping_first_name'] = 'required';
            $rules['shipping_last_name'] = 'required';
            $rules['shipping_address_1'] = 'required';
            $rules['shipping_city'] = 'required';
            $rules['shipping_state'] = 'required';
            $rules['shipping_postcode'] = 'required';
            $rules['shipping_country'] = 'required';
        }

        $attributes = [
            'billing_first_name' => 'Billing First Name',
            'billing_last_name' => 'Billing Last Name',
            'billing_email' => 'Billing Email',
            'billing_phone' => 'Billing Phone',
            'billing_address_1' => 'Billing Street Address',
            'billing_city' => 'Billing City',
            'billing_state' => 'Billing State',
            'billing_postcode' => 'Billing ZIP Code',
            'billing_country' => 'Billing Country',
            'payment_method' => 'Payment Method',
            'shipping_first_name' => 'Shipping First Name',
            'shipping_last_name' => 'Shipping Last Name',
            'shipping_address_1' => 'Shipping Street Address',
            'shipping_city' => 'Shipping City',
            'shipping_state' => 'Shipping State',
            'shipping_postcode' => 'Shipping ZIP Code',
            'shipping_country' => 'Shipping Country',
        ];

        $request->validate($rules, [], $attributes);

        $cart = Session::get('lazy_cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
        }

        // Apply shipping and tax logic here (from shop settings)
        $shipping = (float) get_cms_option('shop_flat_rate_cost', 0);
        $taxRate = (float) get_cms_option('shop_tax_rate', 0);
        
        $freeShippingThreshold = (float) get_cms_option('shop_free_shipping_threshold', 0);
        if ($freeShippingThreshold > 0 && $subtotal >= $freeShippingThreshold) {
            $shipping = 0;
        }

        $tax = $subtotal * ($taxRate / 100);
        $total = $subtotal + $shipping + $tax;

        $orderData = [
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping_total' => $shipping,
            'tax_total' => $tax,
            'total' => $total,
            'first_name' => $request->billing_first_name,
            'last_name' => $request->billing_last_name,
            'customer_email' => $request->billing_email,
            'customer_phone' => $request->billing_phone,
            'address_line_1' => $request->billing_address_1,
            'address_line_2' => $request->billing_address_2,
            'city' => $request->billing_city,
            'state' => $request->billing_state,
            'postcode' => $request->billing_postcode,
            'country' => $request->billing_country,
            'payment_method' => $request->payment_method,
            'customer_note' => $request->order_comments,
        ];

        if ($request->has('ship_to_different_address')) {
            $orderData['shipping_first_name'] = $request->shipping_first_name;
            $orderData['shipping_last_name'] = $request->shipping_last_name;
            $orderData['shipping_address_line_1'] = $request->shipping_address_1;
            $orderData['shipping_address_line_2'] = $request->shipping_address_2;
            $orderData['shipping_city'] = $request->shipping_city;
            $orderData['shipping_state'] = $request->shipping_state;
            $orderData['shipping_postcode'] = $request->shipping_postcode;
            $orderData['shipping_country'] = $request->shipping_country;
        }

        $order = Order::create($orderData);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => ($item['sale_price'] ?? $item['price']) * $item['quantity'],
            ]);
        }

        Session::forget('lazy_cart');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'redirect' => route('shop.confirmation', $order->id),
                'order_id' => $order->id
            ]);
        }

        return redirect()->route('shop.confirmation', $order->id)->with('success', 'Order placed successfully!');
    }

    public function confirmation($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view($this->resolveThemeView('confirmation'), compact('order'));
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:shop_reviews,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $userId = auth()->id();
        $email = auth()->check() ? auth()->user()->email : ($validated['email'] ?? null);
        $name = auth()->check() ? auth()->user()->name : ($validated['name'] ?? 'Guest');

        // Check if this user/email already has at least one approved review (auto-approve logic)
        $isApproved = false;
        
        // Auto-approve if user is an admin
        if (auth()->check() && (auth()->user()->role && in_array(auth()->user()->role->slug, ['admin', 'super-admin']))) {
            $isApproved = true;
        } else {
            $query = \Acme\CmsDashboard\Models\Review::where('is_approved', true);
            if ($userId) {
                $isApproved = (clone $query)->where('user_id', $userId)->exists();
            } elseif ($email) {
                $isApproved = (clone $query)->where('email', $email)->exists();
            }
        }

        \Acme\CmsDashboard\Models\Review::create([
            'post_id' => $validated['post_id'],
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => $userId,
            'name' => $name,
            'email' => $email,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => $isApproved
        ]);

        $message = $isApproved ? 'Review posted successfully.' : 'Your review is awaiting moderation.';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
