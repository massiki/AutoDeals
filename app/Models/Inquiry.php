<?php

namespace App\Models;

use Database\Factories\InquiryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'car_id', 'buyer_name', 'phone', 'email',
    'inquiry_date', 'offer_price', 'status', 'notes',
])]
class Inquiry extends Model
{
    /** @use HasFactory<InquiryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'inquiry_date' => 'datetime',
            'offer_price' => 'decimal:2',
        ];
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
