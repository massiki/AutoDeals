<?php

namespace App\Models;

use Database\Factories\CarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'stock_code', 'brand', 'model', 'year', 'price', 'kilometer',
    'color', 'transmission', 'fuel_type', 'engine_cc', 'plate_number',
    'condition', 'vin', 'description', 'photos', 'status',
])]
class Car extends Model
{
    /** @use HasFactory<CarFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'price' => 'decimal:2',
            'kilometer' => 'integer',
            'engine_cc' => 'integer',
            'photos' => 'array',
        ];
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }
}
