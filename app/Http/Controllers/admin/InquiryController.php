<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');

        $inquiries = Inquiry::with('car')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('buyer_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('car', function ($q) use ($search) {
                            $q->where('brand', 'like', "%{$search}%")
                                ->orWhere('model', 'like', "%{$search}%")
                                ->orWhere('stock_code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.inquiry.index', compact('inquiries', 'search', 'status'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load('car');
        return view('admin.inquiry.show', compact('inquiry'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Test Drive,Approved,Rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $inquiry->update($validated);

        if ($inquiry->car) {
            $reserving = ['Test Drive', 'Approved'];
            $releasing = ['Pending', 'Rejected'];

            if (in_array($validated['status'], $reserving)) {
                $inquiry->car->update(['status' => 'Reserved']);
            } elseif (in_array($validated['status'], $releasing)) {
                $inquiry->car->update(['status' => 'Available']);
            }
        }

        $statusLabels = [
            'Pending' => 'Pending',
            'Test Drive' => 'Test Drive scheduled',
            'Approved' => 'Approved',
            'Rejected' => 'Rejected',
        ];

        return redirect()->route('admin.inquiries.index')
            ->with('success', "Inquiry status updated to {$statusLabels[$validated['status']]} successfully.");
    }
}
