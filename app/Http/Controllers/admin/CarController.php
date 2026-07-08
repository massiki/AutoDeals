<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function create()
    {
        $lastCar = Car::latest('id')->first();
        $nextId = $lastCar ? $lastCar->id + 1 : 1;
        $stockCode = 'AUT-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('car.create', compact('stockCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vin'          => 'nullable|string|max:17|unique:cars,vin',
            'brand'        => 'required|string|max:100',
            'model'        => 'required|string|max:100',
            'year'         => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'price'        => 'required|numeric|min:0',
            'kilometer'    => 'required|integer|min:0',
            'color'        => 'required|string|max:50',
            'transmission' => 'required|string|in:Manual,Automatic,CVT',
            'fuel_type'    => 'required|string|in:Bensin,Diesel,Hybrid,Electric',
            'engine_cc'    => 'nullable|integer|min:0',
            'plate_number' => 'nullable|string|max:20',
            'condition'    => 'required|string|in:New,Excellent,Good,Fair,Poor',
            'description'  => 'nullable|string|max:5000',
            'photos'       => 'nullable|array|max:10',
            'photos.*'     => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $lastCar = Car::latest('id')->first();
        $nextId = $lastCar ? $lastCar->id + 1 : 1;
        $validated['stock_code'] = 'AUT-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'Available';

        if ($request->hasFile('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $paths[] = $photo->store('cars/photos', 'public');
            }
            $validated['photos'] = $paths;
        }

        Car::create($validated);

        return redirect()->route('dashboard')->with('success', 'Vehicle added successfully.');
    }

    public function edit(Car $car)
    {
        return view('car.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'vin'            => 'nullable|string|max:17|unique:cars,vin,' . $car->id,
            'brand'          => 'required|string|max:100',
            'model'          => 'required|string|max:100',
            'year'           => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'price'          => 'required|numeric|min:0',
            'kilometer'      => 'required|integer|min:0',
            'color'          => 'required|string|max:50',
            'transmission'   => 'required|string|in:Manual,Automatic,CVT',
            'fuel_type'      => 'required|string|in:Bensin,Diesel,Hybrid,Electric',
            'engine_cc'      => 'nullable|integer|min:0',
            'plate_number'   => 'nullable|string|max:20',
            'condition'      => 'required|string|in:New,Excellent,Good,Fair,Poor',
            'description'    => 'nullable|string|max:5000',
            'photos'         => 'nullable|array|max:10',
            'photos.*'       => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'deleted_photos' => 'nullable|string',
        ]);

        $existingPhotos = $car->photos ?? [];

        // Handle deleted existing photos
        if ($request->filled('deleted_photos')) {
            $deleted = json_decode($request->deleted_photos, true);
            foreach ($deleted as $path) {
                Storage::disk('public')->delete($path);
            }
            $existingPhotos = array_values(array_diff($existingPhotos, $deleted));
        }

        // Handle newly uploaded photos
        if ($request->hasFile('photos')) {
            $newPaths = [];
            foreach ($request->file('photos') as $photo) {
                $newPaths[] = $photo->store('cars/photos', 'public');
            }
            $existingPhotos = array_merge($existingPhotos, $newPaths);
        }

        $validated['photos'] = $existingPhotos;
        $car->update($validated);

        return redirect()->route('dashboard')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Car $car)
    {
        foreach ($car->photos ?? [] as $photo) {
            Storage::disk('public')->delete($photo);
        }

        $car->delete();

        return redirect()->route('dashboard')->with('success', 'Vehicle deleted successfully.');
    }
}
