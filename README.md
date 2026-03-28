# OrderFlow — E-Commerce Order Management System

OrderFlow is a lightweight Laravel-based order and inventory management system focused on modeling Customers, Profiles, Orders, Products, and the relationships between them. The documentation below describes the database architecture and provides example Eloquent models and migration `up()` methods.

## Entity Relationship Diagram (ERD)

![ERD Diagram](public/images/entity_relationship_diagram.drawio.png)

Text summary of relationships (at-a-glance):

- **Customer to Customer_Profile (1:1):** Each `Customer` has one `CustomerProfile`. The `customer_profiles` table stores `customer_id` as a foreign key referencing `customers.id` (cascade on delete).
- **Customer to Order (1:N):** A `Customer` can have many `Order` records. Each `orders.customer_id` references `customers.id` (cascade on delete).
- **Order to Product (M:N):** Orders and Products have a many-to-many relationship resolved via the `order_details` pivot/junction table. `order_details` stores `order_id`, `product_id` and `quantity` (pivot column).

## Laravel Models

Below are example Eloquent models for `Customer`, `CustomerProfile`, `Order`, and `Product`. They use PHP 8 attribute-style declarations for fillable/hidden (#[Fillable], #[Hidden]) as requested — note this requires either custom attribute classes or a package that maps these attributes to Eloquent behavior.

Note: `order_details` is a pivot (junction) table and does not require a dedicated Eloquent model; relationships use `belongsToMany(...)->withPivot('quantity')`.

### Customer (app/Models/Customer.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Attributes\Fillable;
use App\Attributes\Hidden;

#[Fillable(['name', 'email'])]
#[Hidden([])]
class Customer extends Model
{
    public function profile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
```

### CustomerProfile (app/Models/CustomerProfile.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Attributes\Fillable;
use App\Attributes\Hidden;

#[Fillable(['customer_id', 'shipping_address', 'phone_number'])]
#[Hidden([])]
class CustomerProfile extends Model
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
```

### Order (app/Models/Order.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Attributes\Fillable;
use App\Attributes\Hidden;

#[Fillable(['customer_id', 'order_date'])]
#[Hidden([])]
class Order extends Model
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_details', 'order_id', 'product_id')
                    ->withPivot('quantity');
    }
}
```

### Product (app/Models/Product.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Attributes\Fillable;
use App\Attributes\Hidden;

#[Fillable(['product_name', 'price'])]
#[Hidden([])]
class Product extends Model
{
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_details', 'product_id', 'order_id')
                    ->withPivot('quantity');
    }
}
```

## Database Migrations (ordered `up()` methods)

Run these migrations in the order below to avoid FK constraint errors.

### 1) `customers` migration — up()
```php
public function up()
{
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email');
    });
}
```

### 2) `customer_profiles` migration — up()
```php
public function up()
{
    Schema::create('customer_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
        $table->string('shipping_address')->nullable();
        $table->string('phone_number')->nullable();
    });
}
```

### 3) `orders` migration — up()
```php
public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
        $table->dateTime('order_date');
    });
}
```

### 4) `products` migration — up()
```php
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('product_name');
        $table->decimal('price', 10, 2);
    });
}
```

### 5) `order_details` migration — up()
```php
public function up()
{
    Schema::create('order_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
        $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
        $table->unsignedInteger('quantity')->default(1);
    });
}
```

---

If you want, I can also:

- Add the corresponding `down()` methods for each migration.
- Generate the actual migration files with these `up()` methods inserted (matching your timestamps).
- Create attribute classes (`App\Attributes\Fillable`, `App\Attributes\Hidden`) or show a package that provides this behavior.

Created by: OrderFlow project scaffolding
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
