<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition(): array
    {
        return [
            'car_id' => Car::factory(),
            'buyer_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'inquiry_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'offer_price' => $this->faker->optional()->numberBetween(40000000, 750000000),
            'status' => $this->faker->randomElement(['Pending', 'Pending', 'Test Drive', 'Approved', 'Rejected']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
