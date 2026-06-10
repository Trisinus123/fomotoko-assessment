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

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

# Fomotoko Assessment
> Fullstack Engineer Assessment Test - PT Fomo Inovasi Teknologi

## Requirements
- PHP >= 8.1
- Composer
- MySQL

---

## Task 1: Online Store API

### Setup

1. Install dependencies:
```bash
composer install
```

2. Copy environment file:
```bash
cp .env.example .env
php artisan key:generate
```

3. Setup database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_fomotoko
DB_USERNAME=root
DB_PASSWORD=
```

4. Buat database:
```sql
CREATE DATABASE db_fomotoko;
```

5. Jalankan migration:
```bash
php artisan migrate
```

6. Jalankan server:
```bash
php artisan serve
```

---

### API Endpoints

#### Products
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/products | Get all products |
| GET | /api/products/{id} | Get single product |
| POST | /api/products | Create product |
| PUT | /api/products/{id} | Update product |
| DELETE | /api/products/{id} | Delete product |

#### Orders
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/orders | Get all orders |
| GET | /api/orders/{id} | Get single order |
| POST | /api/orders | Create order |

---

### Request & Response Examples

#### Create Product
**POST** `/api/products`
```json
{
    "name": "Produk Flash Sale",
    "description": "Produk untuk flash sale",
    "price": 100000,
    "flash_sale_price": 50000,
    "stock": 10
}
```

**Response 201:**
```json
{
    "id": 1,
    "name": "Produk Flash Sale",
    "description": "Produk untuk flash sale",
    "price": "100000.00",
    "flash_sale_price": "50000.00",
    "stock": 10,
    "created_at": "2026-06-10T05:37:38.000000Z",
    "updated_at": "2026-06-10T05:37:38.000000Z"
}
```

#### Create Order
**POST** `/api/orders`
```json
{
    "customer_name": "Budi Santoso",
    "items": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}
```

**Response 201:**
```json
{
    "id": 1,
    "customer_name": "Budi Santoso",
    "status": "success",
    "total_price": 100000,
    "order_items": [
        {
            "id": 1,
            "order_id": 1,
            "product_id": 1,
            "quantity": 2,
            "price": "50000.00"
        }
    ]
}
```

**Response 422 (stok habis):**
```json
{
    "message": "Insufficient stock for product: Produk Flash Sale"
}
```

---

### Race Condition Handling

API menggunakan **database-level locking** (`SELECT ... FOR UPDATE`) untuk mencegah race condition saat flash sale. Ketika banyak order dibuat secara bersamaan untuk produk yang sama, sistem memastikan:

- Stok tidak akan menjadi negatif
- Setiap transaksi diproses secara atomik
- Order yang melebihi stok akan ditolak dengan response 422

### Run Functional Test

```bash
php artisan test tests/Feature/RaceConditionTest.php
```

**Expected output:**
```
PASS  Tests\Feature\RaceConditionTest
✓ race condition flash sale

Tests:    1 passed (3 assertions)
```

---

## Task 2: Hidden Item

### Jalankan Program

```bash
php task2-hidden-item/hidden_item.php
```

### Grid Layout

```
########
#......#
#.###..#
#...#.##
#X#....#
########
```

**Legend:**
- `#` = obstacle (rintangan)
- `.` = clear path (jalur bebas)
- `X` = posisi awal pemain
- `$` = probable item location

### Input

| Input | Keterangan |
|-------|------------|
| A | Jumlah langkah ke Utara (North) |
| B | Jumlah langkah ke Timur (East) |
| C | Jumlah langkah ke Selatan (South) |

### Contoh Output

```
Enter A (steps North): 1
Enter B (steps East): 2
Enter C (steps South): 1

Grid:
########
#......#
#.###..#
#...#.##
#X#....#
########

Probable coordinates (row, col):
  -> row=4, col=3

Grid with probable locations ($):
########
#......#
#.###..#
#...#.##
#X#$...#
########

Legend: # obstacle  . clear path  X start  $ probable item