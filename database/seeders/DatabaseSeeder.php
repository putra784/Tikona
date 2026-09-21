<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            */

            $admin1 = User::create([
                'name' => 'Admin Tikona',
                'email' => 'admin@tikona.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);

            $admin2 = User::create([
                'name' => 'Kasir Tikona',
                'email' => 'kasir@tikona.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);

            $customers = collect([
                [
                    'name' => 'I Gede Putra',
                    'email' => 'putra@gmail.com',
                ],
                [
                    'name' => 'Komang Ayu',
                    'email' => 'ayu@gmail.com',
                ],
                [
                    'name' => 'Made Aditya',
                    'email' => 'aditya@gmail.com',
                ],
                [
                    'name' => 'Kadek Dimas',
                    'email' => 'dimas@gmail.com',
                ],
                [
                    'name' => 'Putu Citra',
                    'email' => 'citra@gmail.com',
                ],
            ])->map(function ($customer) {
                return User::create([
                    'name' => $customer['name'],
                    'email' => $customer['email'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                ]);
            });

            /*
            |--------------------------------------------------------------------------
            | CATEGORIES
            |--------------------------------------------------------------------------
            */

            $coffee = Category::create([
                'name' => 'Coffee',
            ]);

            $signature = Category::create([
                'name' => 'Signature Coffee',
            ]);

            $manualBrew = Category::create([
                'name' => 'Manual Brew',
            ]);

            /*
            |--------------------------------------------------------------------------
            | PRODUCTS
            |--------------------------------------------------------------------------
            */

            $products = collect([
                [
                    'category_id' => $coffee->id,
                    'name' => 'Americano',
                    'description' => 'Espresso dengan tambahan air yang memiliki rasa ringan dan clean.',
                    'price' => 18000,
                    'image' => 'products/americano.png',
                    'is_available' => true,
                ],
                [
                    'category_id' => $coffee->id,
                    'name' => 'Cafe Latte',
                    'description' => 'Espresso dengan steamed milk yang creamy dan lembut.',
                    'price' => 22000,
                    'image' => 'products/cafe-latte.png',
                    'is_available' => true,
                ],
                [
                    'category_id' => $signature->id,
                    'name' => 'Tikona Aren Coffee',
                    'description' => 'Signature coffee Tikona dengan perpaduan espresso dan gula aren.',
                    'price' => 25000,
                    'image' => 'products/tikona-aren.png',
                    'is_available' => true,
                ],
                [
                    'category_id' => $manualBrew->id,
                    'name' => 'V60',
                    'description' => 'Kopi single origin yang diseduh menggunakan metode V60.',
                    'price' => 28000,
                    'image' => 'products/v60.png',
                    'is_available' => true,
                ],
                [
                    'category_id' => $signature->id,
                    'name' => 'Caramel Macchiato',
                    'description' => 'Espresso dengan susu dan sentuhan rasa caramel.',
                    'price' => 27000,
                    'image' => 'products/caramel-macchiato.png',
                    'is_available' => true,
                ],
            ])->map(function ($product) {
                return Product::create($product);
            });

            /*
            |--------------------------------------------------------------------------
            | TRANSACTIONS + TRANSACTION DETAILS
            |--------------------------------------------------------------------------
            */

            $transactions = collect();

            // Hanya customer yang dapat membuat transaksi
            foreach (range(1, 5) as $index) {

                $customer = $customers->random();

                $transaction = Transaction::create([
                    'user_id' => $customer->id,
                    'order_type' => fake()->randomElement([
                        'dine_in',
                        'takeaway',
                    ]),
                    'total_price' => 0,
                    'status' => fake()->randomElement([
                        'pending',
                        'confirmed',
                        'completed',
                    ]),
                ]);

                /*
                 * Setiap transaksi mendapatkan
                 * 1 sampai 3 produk yang berbeda.
                 */
                $selectedProducts = $products
                    ->shuffle()
                    ->take(rand(1, 3));

                $totalPrice = 0;

                foreach ($selectedProducts as $product) {

                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $subtotal = $price * $quantity;

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    $totalPrice += $subtotal;
                }

                $transaction->update([
                    'total_price' => $totalPrice,
                ]);

                $transactions->push($transaction->fresh());
            }

            /*
            |--------------------------------------------------------------------------
            | PAYMENTS
            |--------------------------------------------------------------------------
            */

            foreach ($transactions as $transaction) {

                $isPaid = in_array(
                    $transaction->status,
                    ['confirmed', 'completed']
                );

                Payment::create([
                    'transaction_id' => $transaction->id,

                    'midtrans_order_id' =>
                        'TIKONA-' . $transaction->id . '-' . fake()->unique()->numerify('######'),

                    'payment_method' => fake()->randomElement([
                        'qris',
                        'gopay',
                        'bank_transfer',
                        'credit_card',
                    ]),

                    'amount' => $transaction->total_price,

                    'status' => $isPaid ? 'paid' : 'pending',

                    'paid_at' => $isPaid ? now() : null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | REVIEWS
            |--------------------------------------------------------------------------
            */

            foreach (range(1, 5) as $index) {

                $customer = $customers->random();
                $product = $products->random();

                Review::create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'rating' => rand(3, 5),
                    'description' => fake()->randomElement([
                        'Kopinya enak dan cocok untuk menemani aktivitas.',
                        'Rasanya enak, pasti akan pesan lagi.',
                        'Kopi cukup strong dan aromanya bagus.',
                        'Pelayanannya bagus dan kopinya sesuai ekspektasi.',
                        'Tempatnya nyaman dan minumannya enak.',
                    ]),
                ]);
            }
        });
    }
}