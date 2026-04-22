<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\BlockedIp;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403);
        }
        $blockedIps = BlockedIp::latest()->paginate(20);
        return view('cms-dashboard::admin.blacklist.index', compact('blockedIps'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403);
        }
        $blockedIp = BlockedIp::findOrFail($id);
        $blockedIp->delete();

        return redirect()->route('admin.blacklist.index')->with('success', 'IP unblocked successfully.');
    }
}
