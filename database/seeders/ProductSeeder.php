<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var array<string, array<int, array{name: string, description: string, price: int, stock: int}>> $productsByCategory */
        $productsByCategory = [
            'Elektronik' => [
                [
                    'name' => 'Smart TV 43 Inch 4K UHD',
                    'description' => 'Televisi pintar resolusi 4K dengan dukungan HDR10, konektivitas WiFi, dan sistem operasi Google TV.',
                    'price' => 3899000,
                    'stock' => 15,
                ],
                [
                    'name' => 'Wireless Noise Cancelling Headphone',
                    'description' => 'Headphone bluetooth dengan active noise cancelling (ANC), daya tahan baterai hingga 30 jam, dan audio resolusi tinggi.',
                    'price' => 1250000,
                    'stock' => 25,
                ],
                [
                    'name' => 'Portable Bluetooth Speaker IPX7',
                    'description' => 'Speaker nirkabel tahan air dengan bass mendalam dan baterai tahan hingga 12 jam pemutaran.',
                    'price' => 450000,
                    'stock' => 40,
                ],
            ],
            'Pakaian & Fashion' => [
                [
                    'name' => 'Kaos Polos Cotton Combed 30s',
                    'description' => 'Kaos berbahan katun combed 30s premium yang adem, nyaman dipakai sehari-hari, dan menyerap keringat.',
                    'price' => 65000,
                    'stock' => 100,
                ],
                [
                    'name' => 'Jaket Hoodie Fleece Oversize',
                    'description' => 'Hoodie bahan cotton fleece tebal dan lembut dengan saku kanguru serta tali serut berkualitas.',
                    'price' => 185000,
                    'stock' => 30,
                ],
                [
                    'name' => 'Celana Jeans Slim Fit Denim',
                    'description' => 'Celana panjang denim berkualitas dengan potongan slim fit yang fleksibel dan modis.',
                    'price' => 240000,
                    'stock' => 50,
                ],
            ],
            'Komputer & Aksesoris' => [
                [
                    'name' => 'Mechanical Keyboard RGB Wireless',
                    'description' => 'Keyboard mekanikal 75% hot-swappable dengan switch linear, konektivitas tri-mode (Bluetooth, 2.4GHz, Type-C).',
                    'price' => 750000,
                    'stock' => 20,
                ],
                [
                    'name' => 'Ergonomic Wireless Mouse Silent Click',
                    'description' => 'Mouse ergonomis nirkabel dengan klik senyap, sensor presisi tinggi, dan daya baterai tahan lama.',
                    'price' => 195000,
                    'stock' => 45,
                ],
                [
                    'name' => 'Monitor IPS 24 Inch 100Hz Full HD',
                    'description' => 'Monitor bezel tipis dengan panel IPS, refresh rate 100Hz, sRGB 99%, dan teknologi eye care.',
                    'price' => 1350000,
                    'stock' => 12,
                ],
            ],
            'Handphone & Tablet' => [
                [
                    'name' => 'Smartphone 5G 8GB/256GB AMOLED',
                    'description' => 'Smartphone layar AMOLED 120Hz, kamera utama 64MP OIS, baterai 5000mAh dengan pengisian cepat 67W.',
                    'price' => 3499000,
                    'stock' => 18,
                ],
                [
                    'name' => 'Tablet 11 Inch 2K Stylus Support',
                    'description' => 'Tablet layar 2K dengan quad speakers Dolby Atmos, mendukung stylus pen dan keyboard magnetik.',
                    'price' => 2999000,
                    'stock' => 10,
                ],
            ],
            'Gaming & Hobi' => [
                [
                    'name' => 'Gamepad Controller Wireless Multi-Platform',
                    'description' => 'Kontroler game nirkabel kompatibel PC, Android, Switch dengan hall effect trigger dan vibrasi ganda.',
                    'price' => 380000,
                    'stock' => 35,
                ],
                [
                    'name' => 'Gaming Mousepad Deskmat 900x400mm',
                    'description' => 'Mousepad ukuran besar dengan permukaan kain halus mikro, jahitan tepi kuat, dan dasar karet anti-slip.',
                    'price' => 85000,
                    'stock' => 60,
                ],
            ],
        ];

        foreach ($productsByCategory as $categoryName => $products) {
            $category = CategoryProduct::firstOrCreate(['name' => $categoryName]);

            foreach ($products as $productData) {
                Product::firstOrCreate(
                    [
                        'name' => $productData['name'],
                        'category_id' => $category->id,
                    ],
                    [
                        ...$productData,
                        'category_id' => $category->id,
                    ]
                );
            }
        }
    }
}
