<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\Coupon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Phân quyền & Tạo tài khoản Admin / User
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@techzone.vn'],
            [
                'name' => 'TechZone Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        $demoUser = User::updateOrCreate(
            ['email' => 'user@techzone.vn'],
            [
                'name' => 'Nguyễn Văn A',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $demoUser->assignRole($userRole);

        // 2. Tạo Danh mục ngành hàng
        $categoriesData = [
            ['name' => 'Điện thoại', 'slug' => 'dien-thoai'],
            ['name' => 'Laptop', 'slug' => 'laptop'],
            ['name' => 'Tablet', 'slug' => 'tablet'],
            ['name' => 'Smart TV', 'slug' => 'smart-tv'],
            ['name' => 'Gia dụng điện tử', 'slug' => 'gia-dung-dien-tu'],
            ['name' => 'Âm thanh & Phụ kiện', 'slug' => 'am-thanh-phu-kien'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::firstOrCreate(['slug' => $c['slug']], ['name' => $c['name']]);
        }

        // 3. Tạo Thương hiệu
        $brandsData = [
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'Asus', 'slug' => 'asus'],
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'LG', 'slug' => 'lg'],
            ['name' => 'Philips', 'slug' => 'philips'],
            ['name' => 'JBL', 'slug' => 'jbl'],
        ];

        $brands = [];
        foreach ($brandsData as $b) {
            $brands[$b['slug']] = Brand::firstOrCreate(['slug' => $b['slug']], ['name' => $b['name']]);
        }

        // 4. Danh sách Sản phẩm mẫu đa dạng từng mục
        $productsData = [
            // --- ĐIỆN THOẠI ---
            [
                'name' => 'iPhone 16 Pro Max 256GB',
                'slug' => 'iphone-16-pro-max-256gb',
                'category' => 'dien-thoai',
                'brand' => 'apple',
                'price' => 34990000,
                'stock' => 50,
                'specs' => [
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'Màn hình', 'spec_value' => '6.9 inch Super Retina XDR OLED'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'CPU', 'spec_value' => 'Apple A18 Pro 6 nhân'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'RAM', 'spec_value' => '8GB'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'Bộ nhớ trong', 'spec_value' => '256GB'],
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB',
                'slug' => 'samsung-galaxy-s24-ultra-512gb',
                'category' => 'dien-thoai',
                'brand' => 'samsung',
                'price' => 31990000,
                'stock' => 40,
                'specs' => [
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'Màn hình', 'spec_value' => '6.8 inch Dynamic AMOLED 2X 120Hz'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'CPU', 'spec_value' => 'Snapdragon 8 Gen 3 for Galaxy'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'RAM', 'spec_value' => '12GB'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'Bộ nhớ trong', 'spec_value' => '512GB'],
                ]
            ],
            [
                'name' => 'Xiaomi 14 Ultra 5G 16GB/512GB',
                'slug' => 'xiaomi-14-ultra-5g-512gb',
                'category' => 'dien-thoai',
                'brand' => 'xiaomi',
                'price' => 27990000,
                'stock' => 30,
                'specs' => [
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'Màn hình', 'spec_value' => '6.73 inch LTPO AMOLED WQHD+'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'CPU', 'spec_value' => 'Snapdragon 8 Gen 3'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'RAM', 'spec_value' => '16GB'],
                    ['group_name' => 'Cấu hình chung', 'spec_key' => 'Bộ nhớ trong', 'spec_value' => '512GB'],
                ]
            ],

            // --- LAPTOP GAMING & VĂN PHÒNG ---
            [
                'name' => 'Laptop Gaming Asus ROG Zephyrus G16 RTX 4070 32GB',
                'slug' => 'asus-rog-zephyrus-g16-rtx-4070',
                'category' => 'laptop',
                'brand' => 'asus',
                'price' => 54990000,
                'stock' => 20,
                'specs' => [
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'CPU', 'spec_value' => 'Intel Core Ultra 9 185H'],
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'RAM', 'spec_value' => '32GB LPDDR5X 7467MHz'],
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'Ổ cứng SSD', 'spec_value' => '1TB NVMe PCIe 4.0'],
                    ['group_name' => 'Đồ họa', 'spec_key' => 'Card đồ họa GPU', 'spec_value' => 'NVIDIA GeForce RTX 4070 8GB GDDR6'],
                ]
            ],
            [
                'name' => 'Laptop Dell XPS 14 9440 Intel Core Ultra 7 16GB/512GB',
                'slug' => 'dell-xps-14-9440-ultra-7',
                'category' => 'laptop',
                'brand' => 'dell',
                'price' => 46990000,
                'stock' => 25,
                'specs' => [
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'CPU', 'spec_value' => 'Intel Core Ultra 7 155H'],
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'RAM', 'spec_value' => '16GB LPDDR5X'],
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'Ổ cứng SSD', 'spec_value' => '512GB SSD M.2 PCIe'],
                    ['group_name' => 'Màn hình', 'spec_key' => 'Màn hình', 'spec_value' => '14.5 inch OLED 3.2K 120Hz Touch'],
                ]
            ],
            [
                'name' => 'MacBook Air M3 13 inch 16GB/256GB',
                'slug' => 'macbook-air-m3-13-inch-16gb',
                'category' => 'laptop',
                'brand' => 'apple',
                'price' => 28990000,
                'stock' => 35,
                'specs' => [
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'CPU', 'spec_value' => 'Apple M3 8-core CPU'],
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'RAM', 'spec_value' => '16GB Unified Memory'],
                    ['group_name' => 'Hiệu năng', 'spec_key' => 'Ổ cứng SSD', 'spec_value' => '256GB SSD'],
                ]
            ],

            // --- TABLET ---
            [
                'name' => 'iPad Pro M4 11 inch Wi-Fi 256GB',
                'slug' => 'ipad-pro-m4-11-inch-256gb',
                'category' => 'tablet',
                'brand' => 'apple',
                'price' => 27490000,
                'stock' => 30,
                'specs' => [
                    ['group_name' => 'Cấu hình', 'spec_key' => 'CPU', 'spec_value' => 'Apple M4 Chip 9-core'],
                    ['group_name' => 'Cấu hình', 'spec_key' => 'RAM', 'spec_value' => '8GB'],
                    ['group_name' => 'Cấu hình', 'spec_key' => 'Bộ nhớ trong', 'spec_value' => '256GB'],
                    ['group_name' => 'Màn hình', 'spec_key' => 'Màn hình', 'spec_value' => '11 inch Ultra Retina XDR OLED'],
                ]
            ],
            [
                'name' => 'Samsung Galaxy Tab S9 Ultra 12GB/256GB',
                'slug' => 'samsung-galaxy-tab-s9-ultra',
                'category' => 'tablet',
                'brand' => 'samsung',
                'price' => 25990000,
                'stock' => 20,
                'specs' => [
                    ['group_name' => 'Cấu hình', 'spec_key' => 'CPU', 'spec_value' => 'Snapdragon 8 Gen 2 for Galaxy'],
                    ['group_name' => 'Cấu hình', 'spec_key' => 'RAM', 'spec_value' => '12GB'],
                    ['group_name' => 'Cấu hình', 'spec_key' => 'Bộ nhớ trong', 'spec_value' => '256GB'],
                ]
            ],

            // --- SMART TV ---
            [
                'name' => 'Smart Tivi OLED Sony Bravia 4K 65 inch XR-65A80L',
                'slug' => 'sony-bravia-oled-4k-65-inch-xr-65a80l',
                'category' => 'smart-tv',
                'brand' => 'sony',
                'price' => 45900000,
                'stock' => 15,
                'specs' => [
                    ['group_name' => 'Hiển thị', 'spec_key' => 'Độ phân giải', 'spec_value' => '4K Ultra HD (3840 x 2160)'],
                    ['group_name' => 'Hiển thị', 'spec_key' => 'Công nghệ hình ảnh', 'spec_value' => 'Cognitive Processor XR, XR OLED Contrast Pro'],
                ]
            ],
            [
                'name' => 'Smart Tivi LG OLED evo 4K 55 inch OLED55C3PSA',
                'slug' => 'smart-tivi-lg-oled-55-inch-c3',
                'category' => 'smart-tv',
                'brand' => 'lg',
                'price' => 29490000,
                'stock' => 18,
                'specs' => [
                    ['group_name' => 'Hiển thị', 'spec_key' => 'Độ phân giải', 'spec_value' => '4K OLED 120Hz'],
                    ['group_name' => 'Bộ xử lý', 'spec_key' => 'Bộ xử lý', 'spec_value' => 'Bộ xử lý α9 Gen6 AI 4K'],
                ]
            ],

            // --- GIA DỤNG ĐIỆN TỬ ---
            [
                'name' => 'Nồi chiên không dầu điện tử Philips XXL HD9860/90',
                'slug' => 'noi-chien-khong-dau-philips-hd9860',
                'category' => 'gia-dung-dien-tu',
                'brand' => 'philips',
                'price' => 7490000,
                'stock' => 40,
                'specs' => [
                    ['group_name' => 'Thông số', 'spec_key' => 'Công suất', 'spec_value' => '2225W'],
                    ['group_name' => 'Thông số', 'spec_key' => 'Dung tích', 'spec_value' => '7.3 lít (1.4 kg thực phẩm)'],
                ]
            ],
            [
                'name' => 'Máy lọc không khí Xiaomi Smart Air Purifier 4 Pro',
                'slug' => 'may-loc-khong-khi-xiaomi-4-pro',
                'category' => 'gia-dung-dien-tu',
                'brand' => 'xiaomi',
                'price' => 4890000,
                'stock' => 50,
                'specs' => [
                    ['group_name' => 'Thông số', 'spec_key' => 'Diện tích sử dụng', 'spec_value' => '35 - 60 m²'],
                    ['group_name' => 'Thông số', 'spec_key' => 'Bộ lọc', 'spec_value' => 'HEPA 3 lớp lọc sạch 99.97% bụi mịn PM2.5'],
                ]
            ],

            // --- ÂM THANH & PHỤ KIỆN ---
            [
                'name' => 'Tai nghe chống ồn Sony WH-1000XM5',
                'slug' => 'tai-nghe-chong-on-sony-wh-1000xm5',
                'category' => 'am-thanh-phu-kien',
                'brand' => 'sony',
                'price' => 7990000,
                'stock' => 45,
                'specs' => [
                    ['group_name' => 'Âm thanh', 'spec_key' => 'Thời lượng pin', 'spec_value' => '30 giờ liên tục'],
                    ['group_name' => 'Tính năng', 'spec_key' => 'Chống ồn', 'spec_value' => 'Chống ồn chủ động ANC chuẩn Hi-Res Audio'],
                ]
            ],
            [
                'name' => 'Loa Bluetooth di động JBL Charge 5',
                'slug' => 'loa-bluetooth-jbl-charge-5',
                'category' => 'am-thanh-phu-kien',
                'brand' => 'jbl',
                'price' => 3690000,
                'stock' => 60,
                'specs' => [
                    ['group_name' => 'Âm thanh', 'spec_key' => 'Công suất', 'spec_value' => '40W RMS'],
                    ['group_name' => 'Độ bền', 'spec_key' => 'Kháng nước', 'spec_value' => 'Chuẩn IP67 chống nước và chống bụi'],
                ]
            ],
        ];

        // 5. Lưu sản phẩm & thông số vào Database
        foreach ($productsData as $item) {
            $cat = $categories[$item['category']] ?? null;
            $br = $brands[$item['brand']] ?? null;

            if ($cat && $br) {
                $product = Product::updateOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'category_id' => $cat->id,
                        'brand_id' => $br->id,
                        'price' => $item['price'],
                        'stock' => $item['stock'],
                        'description' => "Sản phẩm chính hãng {$item['name']} phân phối bởi TechZone Việt Nam. Đầy đủ hóa đơn VAT, bảo hành 12 tháng chính hãng toàn quốc.",
                    ]
                );

                // Lưu Specifications để bộ lọc RAM/ROM/CPU hoạt động chuẩn
                if (isset($item['specs'])) {
                    foreach ($item['specs'] as $sp) {
                        ProductSpecification::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'spec_key' => $sp['spec_key']
                            ],
                            [
                                'group_name' => $sp['group_name'],
                                'spec_value' => $sp['spec_value']
                            ]
                        );
                    }
                }
            }
        }

        // 6. Tạo các mã Giảm giá / Voucher mẫu
        Coupon::updateOrCreate(
            ['code' => 'TECHZONE50K'],
            [
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 500000,
                'expires_at' => now()->addMonths(3),
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'FREESHIP'],
            [
                'type' => 'shipping',
                'value' => 30000,
                'min_order_amount' => 200000,
                'expires_at' => now()->addMonths(3),
            ]
        );
    }
}