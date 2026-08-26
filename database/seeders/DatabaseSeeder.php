<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Accounts
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin System', 'password' => bcrypt('12345678')]
        );
        $admin->assignRole($adminRole);

        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            ['name' => 'Khách Mua Hàng', 'password' => bcrypt('12345678')]
        );
        $user->assignRole($userRole);

        // 2. Danh mục
        $categories = [
            'Điện thoại' => ['iPhone 16 Pro Max', 'iPhone 15', 'Galaxy S24 Ultra', 'Xiaomi 14 Ultra', 'Redmi Note 13'],
            'Laptop' => ['MacBook Pro M3', 'MacBook Air M2', 'ROG Zephyrus G16', 'TUF Gaming A15', 'Dell XPS 13'],
            'Tablet' => ['iPad Pro M4', 'iPad Air 6', 'Galaxy Tab S9', 'Xiaomi Pad 6'],
            'Smart TV' => ['OLED evo C3 65 inch', 'Bravia XR OLED 55 inch', 'Neo QLED 4K 75 inch'],
            'Gia dụng điện tử' => ['Nồi chiên không dầu Airfryer', 'Máy hút bụi không dây', 'Robot hút bụi Deebot'],
            'Âm thanh & Phụ kiện' => ['Tai nghe WH-1000XM5', 'AirPods Pro 2', 'Loa Bluetooth Charge 5']
        ];

        // 3. Thương hiệu / Hãng
        $brandsList = ['Apple', 'Samsung', 'Xiaomi', 'Sony', 'Asus', 'Dell', 'LG', 'Philips', 'JBL'];
        $brandModels = [];
        foreach ($brandsList as $b) {
            $brandModels[$b] = Brand::firstOrCreate(
                ['slug' => Str::slug($b)],
                ['name' => $b]
            );
        }

        // Tạo danh mục và map sản phẩm đúng hãng
        $catModels = [];
        foreach (array_keys($categories) as $cName) {
            $catModels[$cName] = Category::firstOrCreate(
                ['slug' => Str::slug($cName)],
                ['name' => $cName]
            );
        }

        // 4. Tạo 1000 sản phẩm khớp Brand & Category
        $brandsKeys = array_keys($brandModels);
        $catKeys = array_keys($catModels);

        for ($i = 1; $i <= 1000; $i++) {
            $randomCatName = $catKeys[array_rand($catKeys)];
            $randomBrandName = $brandsKeys[array_rand($brandsKeys)];
            $sampleNameList = $categories[$randomCatName];
            $sampleName = $sampleNameList[array_rand($sampleNameList)];

            $productName = "[$randomBrandName] $sampleName #$i";

            Product::create([
                'category_id' => $catModels[$randomCatName]->id,
                'brand_id' => $brandModels[$randomBrandName]->id,
                'name' => $productName,
                'slug' => Str::slug($productName) . '-' . $i,
                'description' => "Hàng chính hãng phân phối bởi TechZone, bảo hành điện tử chính hãng $randomBrandName 12 tháng.",
                'price' => fake()->randomFloat(2, 50, 2500),
                'stock' => fake()->numberBetween(5, 200),
                'image' => null,
            ]);
        }
    }
}