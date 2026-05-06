<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\BlockedIp;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index(Request $request)
    {
        if (!lazy_has_permission(auth()->user(), 'manage_users')) {
            abort(403);
        }

        $query = BlockedIp::latest();

        if ($request->filled('s')) {
            $query->where('ip_address', 'like', '%' . $request->s . '%')
                  ->orWhere('country', 'like', '%' . $request->s . '%')
                  ->orWhere('reason', 'like', '%' . $request->s . '%');
        }

        $blockedIps = $query->paginate(10)->withQueryString();
        return view('cms-dashboard::admin.blacklist.index', compact('blockedIps'));
    }

    public function bulk(Request $request)
    {
        if (!lazy_has_permission(auth()->user(), 'manage_users')) {
            abort(403);
        }

        $action = $request->input('action') ?: $request->input('action2');
        $ids = $request->input('ids', []);

        if ($action === 'delete' && !empty($ids)) {
            BlockedIp::whereIn('id', $ids)->delete();
            return redirect()->route('admin.blacklist.index')->with('success', 'Selected IPs unblocked successfully.');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        if (!lazy_has_permission(auth()->user(), 'manage_users')) {
            abort(403);
        }
        $blockedIp = BlockedIp::findOrFail($id);
        $blockedIp->delete();

        return redirect()->route('admin.blacklist.index')->with('success', 'IP unblocked successfully.');
    }
}
