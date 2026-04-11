<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['product_name', 'price'])]
#[Hidden(['created_at', 'updated_at'])]
class Product extends Model
{
    use HasFactory;
    
    // Many-to-Many relationship pabalik sa Order
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_details')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}