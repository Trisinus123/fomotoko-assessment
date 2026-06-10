<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Get all orders
    public function index()
    {
        $orders = Order::with('orderItems.product')->get();
        return response()->json($orders, 200);
    }

    // Get single order
    public function show($id)
    {
        $order = Order::with('orderItems.product')->find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order, 200);
    }

    // Create order (with race condition handling)
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                $totalPrice = 0;

                // Create order
                $order = Order::create([
                    'customer_name' => $request->customer_name,
                    'status' => 'pending',
                    'total_price' => 0,
                ]);

                foreach ($request->items as $item) {
                    // Lock product row to prevent race condition
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    if (!$product) {
                        throw new \Exception('Product not found');
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }

                    // Determine price (flash sale or normal)
                    $price = $product->flash_sale_price ?? $product->price;

                    // Deduct stock
                    $product->stock -= $item['quantity'];
                    $product->save();

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $price,
                    ]);

                    $totalPrice += $price * $item['quantity'];
                }

                // Update total price
                $order->total_price = $totalPrice;
                $order->status = 'success';
                $order->save();

                return $order->load('orderItems.product');
            });

            return response()->json($order, 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}