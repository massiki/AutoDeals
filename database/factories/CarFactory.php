<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    private static int $counter = 1;

    protected $model = Car::class;

    public function definition(): array
    {
        $brands = ['Toyota', 'Honda', 'Mitsubishi', 'Suzuki', 'Daihatsu', 'BMW', 'Mercedes-Benz', 'Nissan'];
        $models = [
            'Toyota' => ['Camry', 'Corolla', 'Fortuner', 'Innova', 'Yaris', 'Avanza', 'Rush'],
            'Honda' => ['Civic', 'CR-V', 'HR-V', 'Accord', 'Brio', 'Jazz'],
            'Mitsubishi' => ['Pajero', 'Xpander', 'Outlander', 'Lancer'],
            'Suzuki' => ['Ertiga', 'Swift', 'Jimny', 'Baleno'],
            'Daihatsu' => ['Sigra', 'Terios', 'Xenia', 'Ayla'],
            'BMW' => ['320i', 'X5', '530i', 'X3'],
            'Mercedes-Benz' => ['C200', 'E300', 'GLC', 'A200'],
            'Nissan' => ['X-Trail', 'Serena', 'Navara', 'Livina'],
        ];
        $transmissions = ['Manual', 'Automatic', 'CVT'];
        $fuelTypes = ['Bensin', 'Diesel', 'Hybrid', 'Electric'];
        $conditions = ['New', 'Excellent', 'Good', 'Fair', 'Poor'];
        $colors = ['Putih', 'Hitam', 'Silver', 'Abu-abu', 'Merah', 'Biru', 'Hijau', 'Coklat'];

        $brand = $this->faker->randomElement($brands);
        $model = $this->faker->randomElement($models[$brand]);
        $transmission = $this->faker->randomElement($transmissions);

        return [
            'stock_code' => 'MOB-'.str_pad(self::$counter++, 4, '0', STR_PAD_LEFT),
            'brand' => $brand,
            'model' => $model,
            'year' => $this->faker->numberBetween(2015, 2026),
            'price' => $this->faker->numberBetween(50000000, 800000000),
            'kilometer' => $this->faker->numberBetween(0, 200000),
            'color' => $this->faker->randomElement($colors),
            'transmission' => $transmission,
            'fuel_type' => $this->faker->randomElement($fuelTypes),
            'engine_cc' => $this->faker->optional()->numberBetween(1000, 3000),
            'plate_number' => $this->faker->optional()->regexify('[A-Z]{1,2} \d{1,4} [A-Z]{1,3}'),
            'condition' => $this->faker->randomElement($conditions),
            'vin' => $this->faker->unique()->regexify('[A-HJ-NPR-Z0-9]{17}'),
            'description' => $this->faker->optional()->sentence(10),
            'photos' => $this->faker->optional()->randomElements([
                'cars/photo1.jpg', 'cars/photo2.jpg', 'cars/photo3.jpg',
            ], $this->faker->numberBetween(1, 3)),
            'status' => $this->faker->randomElement(['Available', 'Available', 'Available', 'Reserved', 'Sold']),
        ];
    }
}
