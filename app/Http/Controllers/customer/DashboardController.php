<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userEmail = auth()->user()->email;

        $totalAvailable = Car::where('status', 'Available')->count();
        $myInquiries = Inquiry::where('email', $userEmail)->count();
        $activeInquiries = Inquiry::where('email', $userEmail)
            ->whereIn('status', ['Pending', 'Test Drive'])
            ->count();

        $recentCars = Car::where('status', 'Available')
            ->latest()
            ->take(6)
            ->get();

        $recentInquiries = Inquiry::where('email', $userEmail)
            ->with('car')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'totalAvailable', 'myInquiries', 'activeInquiries',
            'recentCars', 'recentInquiries'
        ));
    }
}
