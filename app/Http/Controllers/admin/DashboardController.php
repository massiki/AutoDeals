<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $brand = $request->input('brand');

        $cars = Car::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('stock_code', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->when($brand, function ($query, $brand) {
                $query->where('brand', $brand);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Car::count(),
            'available' => Car::where('status', 'Available')->count(),
            'reserved' => Car::where('status', 'Reserved')->count(),
            'sold' => Car::where('status', 'Sold')->count(),
            'sold_this_month' => Car::where('status', 'Sold')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
            'total_value' => Car::sum('price'),
        ];

        $brands = Car::select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('admin.dashboard', compact('cars', 'stats', 'brands', 'search', 'brand'));
    }

    public static function carPhotoUrl($car): string
    {
        $photos = $car->photos;

        if (!empty($photos) && isset($photos[0])) {
            $path = $photos[0];
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }

        return asset('assets/images/image-600x400.png');
    }
}
