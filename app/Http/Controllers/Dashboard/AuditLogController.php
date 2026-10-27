<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $logs = OrderStatusHistory::with(['order', 'user'])
            ->when($status, fn ($q) => $q->where('to_status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.audit_log.index', compact('logs', 'status'));
    }
}
