<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $brand = $request->input('brand', '');

        $cars = Car::where('status', 'Available')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('stock_code', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($brand, fn($q) => $q->where('brand', $brand))
            ->latest()
            ->paginate(10);

        $brands = Car::where('status', 'Available')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        return view('customer.car.index', compact('cars', 'search', 'brand', 'brands'));
    }

    public function show(Car $car)
    {

        $photos = [];
        if (!empty($car->photos)) {
            foreach ($car->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    $photos[] = Storage::url($photo);
                }
            }
        }

        $inquiries = $car->inquiries()->latest()->get();

        return view('customer.car.show', compact('car', 'photos', 'inquiries'));
    }
}
