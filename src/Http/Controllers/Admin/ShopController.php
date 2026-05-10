<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\Order;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function orders(Request $request)
    {
        $query = Order::with('items.product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('s')) {
            $query->where('order_number', 'like', '%' . $request->s . '%')
                  ->orWhere('first_name', 'like', '%' . $request->s . '%')
                  ->orWhere('last_name', 'like', '%' . $request->s . '%');
        }

        $orders = $query->latest()->paginate(20);
        return view('cms-dashboard::admin.shop.orders.index', compact('orders'));
    }

    public function orderShow($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('cms-dashboard::admin.shop.orders.show', compact('order'));
    }

    public function orderUpdateStatus(Request $request, $id)
    {
        $order = Order::with('items.product.shopData')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // Inventory Logic
        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            // Decrement Stock
            foreach ($order->items as $item) {
                if ($item->product && $item->product->shopData) {
                    $shopData = $item->product->shopData;
                    if ($shopData->manage_stock) {
                        $shopData->decrement('stock_quantity', $item->quantity);
                    }
                }
            }
        } elseif ($oldStatus === 'completed' && in_array($newStatus, ['cancelled', 'refunded', 'failed'])) {
            // Restock
            foreach ($order->items as $item) {
                if ($item->product && $item->product->shopData) {
                    $shopData = $item->product->shopData;
                    if ($shopData->manage_stock) {
                        $shopData->increment('stock_quantity', $item->quantity);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function settings()
    {
        return view('cms-dashboard::admin.shop.settings');
    }

    public function saveSettings(Request $request)
    {
        // Save shop settings to options table
        $settings = $request->except('_token');
        foreach ($settings as $key => $value) {
            update_cms_option('shop_' . $key, $value);
        }

        return redirect()->back()->with('success', 'Shop settings saved successfully.');
    }
}
