<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChefApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChefApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $applications = ChefApplication::with('user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('admin.chef_applications.index', compact('applications', 'status'));
    }

    public function approve(ChefApplication $application): RedirectResponse
    {
        $application->update(['status' => 'approved']);

        if ($application->user) {
            $user = $application->user;
            $user->role = 'chef';
            if (! $user->phone && $application->phone) {
                $user->phone = $application->phone;
            }
            if (! $user->kitchen_name && $application->kitchen_name) {
                $user->kitchen_name = $application->kitchen_name;
            }
            $user->save();
        }

        return back()->with('success', __('Chef application approved successfully. User promoted to Chef!'));
    }

    public function reject(Request $request, ChefApplication $application): RedirectResponse
    {
        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', __('Chef application has been rejected.'));
    }
}
