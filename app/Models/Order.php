<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['customer_id', 'order_date'])]
#[Hidden(['created_at', 'updated_at'])]
class Order extends Model
{
    // Pabalik sa Customer na umorder
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Many-to-Many relationship papunta sa Product gamit ang Pivot Table
    public function products(): BelongsToMany
    {
        // Gagamitin natin ang 'order_details' na table at kukunin pati ang 'quantity'
        return $this->belongsToMany(Product::class, 'order_details')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}