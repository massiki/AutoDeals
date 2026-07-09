<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::where('email', auth()->user()->email)
            ->with('car')
            ->latest()
            ->paginate(10);

        return view('customer.inquiry.index', compact('inquiries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'buyer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'offer_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['inquiry_date'] = now();
        $validated['status'] = 'Pending';

        Inquiry::create($validated);

        return redirect()->route('customer.cars.show', $validated['car_id'])
            ->with('success', 'Your inquiry has been submitted successfully. Our team will contact you soon.');
    }
}
