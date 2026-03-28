<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email'])]
#[Hidden(['created_at', 'updated_at'])]
class Customer extends Model
{

    // One-to-One relationship sa Customer_Profile
    public function profile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class, 'customer_id');
    }

    // One-to-Many relationship sa Order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}