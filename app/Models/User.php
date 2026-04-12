<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The "booted" method of the model.
     * Dito natin ilalagay yung automatic deletion.
     */

    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->customer) {
                
                // 1. I-delete ang lahat ng orders ng customer na ito
                $user->customer->orders()->delete();
                
                // 2. I-delete ang profile ng customer 
                if ($user->customer->profile) {
                    $user->customer->profile()->delete();
                }
                
                // 3. Panghuli, i-delete ang customer record mismo
                $user->customer()->delete();
            }
        });
    }

    /**
     * Relationship: Isang User ay pwedeng magkaroon ng maraming Orders
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, Customer::class);
    }

    /**
     * Relationship: Isang User ay may isang Customer ID / Profile
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'email', 'email');
    }    
}
