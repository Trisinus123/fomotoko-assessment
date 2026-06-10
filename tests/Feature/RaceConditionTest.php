<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RaceConditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_race_condition_flash_sale()
    {
        // Buat produk dengan stok 5
        $product = Product::create([
            'name' => 'Flash Sale Product',
            'description' => 'Test product',
            'price' => 100000,
            'flash_sale_price' => 50000,
            'stock' => 5,
        ]);

        // Simulasi 10 order bersamaan
        $responses = [];
        for ($i = 0; $i < 10; $i++) {
            $responses[] = $this->postJson('/api/orders', [
                'customer_name' => "Customer {$i}",
                'items' => [
                    [
                        'product_id' => $product->id,
                        'quantity' => 1,
                    ]
                ]
            ]);
        }

        // Hitung berapa yang berhasil
        $successCount = 0;
        $failCount = 0;
        foreach ($responses as $response) {
            if ($response->status() === 201) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        // Hanya 5 yang boleh berhasil (sesuai stok)
        $this->assertEquals(5, $successCount);
        $this->assertEquals(5, $failCount);

        // Pastikan stok tidak minus
        $product->refresh();
        $this->assertEquals(0, $product->stock);
    }
}