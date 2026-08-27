<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        $catPhone = Category::firstOrCreate(['slug' => 'dien-thoai'], ['name' => 'Điện thoại']);
        $catLaptop = Category::firstOrCreate(['slug' => 'laptop'], ['name' => 'Laptop']);

        $brandXiaomi = Brand::firstOrCreate(['slug' => 'xiaomi'], ['name' => 'Xiaomi']);
        $brandHP = Brand::firstOrCreate(['slug' => 'hp'], ['name' => 'HP']);
        $brandLenovo = Brand::firstOrCreate(['slug' => 'lenovo'], ['name' => 'Lenovo']);

        $phone = Product::updateOrCreate(
            ['slug' => 'xiaomi-redmi-17'],
            [
                'category_id' => $catPhone->id,
                'brand_id' => $brandXiaomi->id,
                'name' => 'Xiaomi Redmi 17 6GB 256GB',
                'price' => 7490000,
                'stock' => 50,
                'description' => 'Pin Silicon-Carbon 7500 mAh bền bỉ, sạc nhanh 45W. Màn hình 6.9 inch 120Hz mượt mà. Camera AI 50MP.',
            ]
        );

        $phoneVariants = [
            ['version_name' => '6GB 256GB', 'color_name' => 'Đen', 'price' => 7490000],
            ['version_name' => '6GB 256GB', 'color_name' => 'Xanh dương', 'price' => 7490000],
            ['version_name' => '6GB 256GB', 'color_name' => 'Tím', 'price' => 7490000],
            ['version_name' => '5G 4GB 128GB', 'color_name' => 'Đen', 'price' => 5990000],
            ['version_name' => '5G 4GB 128GB', 'color_name' => 'Xanh dương', 'price' => 5990000],
        ];
        foreach ($phoneVariants as $v) {
            ProductVariant::updateOrCreate(['product_id' => $phone->id, 'version_name' => $v['version_name'], 'color_name' => $v['color_name']], array_merge($v, ['product_id' => $phone->id]));
        }

        $phoneSpecs = [
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Hệ điều hành khi ra mắt', 'spec_value' => 'MediaTek Helio G91 Ultra'],
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Chipset', 'spec_value' => 'Mali-G52 MC2'],
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Dung lượng RAM', 'spec_value' => '6 GB'],
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Bộ nhớ trong', 'spec_value' => '256 GB'],
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Loại CPU', 'spec_value' => '2 nhân 2.4 GHz & 6 nhân 2 GHz'],
            ['group_name' => 'Màn hình', 'spec_key' => 'Kích thước màn hình', 'spec_value' => '6.9 inches'],
            ['group_name' => 'Màn hình', 'spec_key' => 'Công nghệ màn hình', 'spec_value' => 'LCD, 120Hz, Kính Gorilla Glass 7i'],
            ['group_name' => 'Màn hình', 'spec_key' => 'Độ phân giải', 'spec_value' => '1600 x 720 pixels (HD+)'],
            ['group_name' => 'Camera', 'spec_key' => 'Camera sau', 'spec_value' => '50 MP'],
            ['group_name' => 'Camera', 'spec_key' => 'Camera trước', 'spec_value' => '8 MP'],
            ['group_name' => 'Pin & Tiện ích', 'spec_key' => 'Pin & Sạc', 'spec_value' => '7500 mAh, Sạc nhanh 45W'],
            ['group_name' => 'Pin & Tiện ích', 'spec_key' => 'Hỗ trợ mạng', 'spec_value' => '4G / 2 Nano-SIM / NFC'],
        ];
        foreach ($phoneSpecs as $s) {
            ProductSpecification::updateOrCreate(['product_id' => $phone->id, 'spec_key' => $s['spec_key']], array_merge($s, ['product_id' => $phone->id]));
        }

        $hpLaptop = Product::updateOrCreate(
            ['slug' => 'hp-pavilion-16-intel-core-ultra-5'],
            [
                'category_id' => $catLaptop->id,
                'brand_id' => $brandHP->id,
                'name' => 'Laptop HP Pavilion 16 Intel Core Ultra 5 225U',
                'price' => 21490000,
                'stock' => 20,
                'description' => 'Trang bị vi xử lý Intel Core Ultra 5 thế hệ mới tích hợp chip AI Boost, RAM 16GB LPDDR5X bus 7467MHz cực nhanh.',
            ]
        );

        $hpSpecs = [
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Chip AI', 'spec_value' => 'Intel AI Boost'],
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Loại card đồ họa', 'spec_value' => 'Intel Graphics'],
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Loại CPU', 'spec_value' => 'Intel Core Ultra 5 225U (12 lõi) - Max 4.80 GHz'],
            ['group_name' => 'Cấu hình & Bộ nhớ', 'spec_key' => 'Hệ điều hành khi ra mắt', 'spec_value' => 'Windows 11 Home + Office Home 2024'],
            ['group_name' => 'Bộ nhớ RAM, Ổ cứng', 'spec_key' => 'Dung lượng RAM', 'spec_value' => '16GB LPDDR5X/ 7467MHz Onboard'],
            ['group_name' => 'Bộ nhớ RAM, Ổ cứng', 'spec_key' => 'Ổ cứng', 'spec_value' => '512GB SSD PCIe (M.2 2280)'],
            ['group_name' => 'Màn hình', 'spec_key' => 'Kích thước màn hình', 'spec_value' => '16 inches, Tấm nền IPS chống chói'],
            ['group_name' => 'Màn hình', 'spec_key' => 'Độ phân giải', 'spec_value' => '1920 x 1200 pixels (WUXGA)'],
            ['group_name' => 'Âm thanh & Tiện ích', 'spec_key' => 'Âm thanh', 'spec_value' => 'Realtek High Definition Audio'],
            ['group_name' => 'Âm thanh & Tiện ích', 'spec_key' => 'Tiện ích khác', 'spec_value' => 'Wi-Fi 6, Nhận diện khuôn mặt'],
            ['group_name' => 'Cổng giao tiếp & Pin', 'spec_key' => 'Pin', 'spec_value' => '3 Cell Int (59.16Wh)'],
            ['group_name' => 'Cổng giao tiếp & Pin', 'spec_key' => 'Cổng kết nối', 'spec_value' => '2 x USB Type-C, 2 x USB Type-A, HDMI, Giắc tai nghe 3.5mm'],
        ];
        foreach ($hpSpecs as $s) {
            ProductSpecification::updateOrCreate(['product_id' => $hpLaptop->id, 'spec_key' => $s['spec_key']], array_merge($s, ['product_id' => $hpLaptop->id]));
        }

        $lenovoLaptop = Product::updateOrCreate(
            ['slug' => 'lenovo-loq-essential-rtx-3050'],
            [
                'category_id' => $catLaptop->id,
                'brand_id' => $brandLenovo->id,
                'name' => 'Laptop Lenovo LOQ Essential Ryzen 5 7535HS RTX 3050',
                'price' => 19990000,
                'stock' => 15,
                'description' => 'Cỗ máy chiến game quốc dân với RTX 3050 6GB GDDR6 TGP 65W, màn hình 15.6 inch 100% sRGB hỗ trợ AMD FreeSync.',
            ]
        );

        $lenovoSpecs = [
            ['group_name' => 'Cấu hình & Đồ họa', 'spec_key' => 'Loại card đồ họa', 'spec_value' => 'NVIDIA GeForce RTX 3050 6GB GDDR6, Boost Clock 990MHz, TGP 65W'],
            ['group_name' => 'Cấu hình & Đồ họa', 'spec_key' => 'Loại CPU', 'spec_value' => 'AMD Ryzen 5 7535HS (6 lõi / 12 luồng, 3.3 / 4.55GHz, 16MB L3)'],
            ['group_name' => 'Cấu hình & Đồ họa', 'spec_key' => 'Hệ điều hành', 'spec_value' => 'Windows 11 Home Single Language, English'],
            ['group_name' => 'Bộ nhớ RAM, Ổ cứng', 'spec_key' => 'Dung lượng RAM', 'spec_value' => '16GB SODIMM DDR5-4800 (2 khe nâng cấp tối đa 32GB)'],
            ['group_name' => 'Bộ nhớ RAM, Ổ cứng', 'spec_key' => 'Ổ cứng', 'spec_value' => '512GB SSD M.2 2242 PCIe 4.0x4 NVMe (Hỗ trợ 2 khe M.2)'],
            ['group_name' => 'Màn hình', 'spec_key' => 'Kích thước màn hình', 'spec_value' => '15.6 inches Full HD (1920 x 1080)'],
            ['group_name' => 'Màn hình', 'spec_key' => 'Công nghệ màn hình', 'spec_value' => '300 nits chống chói, 100% sRGB, AMD FreeSync, 144Hz'],
            ['group_name' => 'Cổng giao tiếp & Pin', 'spec_key' => 'Pin & Sạc', 'spec_value' => '57.5Wh, 135W Slim Tip'],
            ['group_name' => 'Cổng giao tiếp & Pin', 'spec_key' => 'Cổng giao tiếp', 'spec_value' => '2 x USB-A, 1 x USB-C (PD 65-100W), HDMI 2.1, Jack 3.5mm, LAN RJ-45'],
        ];
        foreach ($lenovoSpecs as $s) {
            ProductSpecification::updateOrCreate(['product_id' => $lenovoLaptop->id, 'spec_key' => $s['spec_key']], array_merge($s, ['product_id' => $lenovoLaptop->id]));
        }

        $remainingProducts = Product::whereNotIn('id', [$phone->id, $hpLaptop->id, $lenovoLaptop->id])->get();

        foreach ($remainingProducts as $item) {
            // 1. Tạo biến thể màu mặc định
            ProductVariant::firstOrCreate(
                ['product_id' => $item->id, 'version_name' => 'Tiêu Chuẩn', 'color_name' => 'Đen'],
                ['price' => $item->price, 'stock' => $item->stock ?? 20]
            );
            ProductVariant::firstOrCreate(
                ['product_id' => $item->id, 'version_name' => 'Tiêu Chuẩn', 'color_name' => 'Xám Bạc'],
                ['price' => $item->price, 'stock' => 15]
            );

            // 2. Tạo thông số kỹ thuật cơ bản theo danh mục
            $specs = [
                ['group_name' => 'Cấu hình chung', 'spec_key' => 'Thương hiệu', 'spec_value' => $item->brand?->name ?? 'Chính hãng'],
                ['group_name' => 'Cấu hình chung', 'spec_key' => 'Tình trạng', 'spec_value' => 'Mới 100%, Nguyên seal'],
                ['group_name' => 'Bảo hành & Hỗ trợ', 'spec_key' => 'Chính sách bảo hành', 'spec_value' => '12 tháng chính hãng tại TTBH ủy quyền'],
                ['group_name' => 'Bảo hành & Hỗ trợ', 'spec_key' => 'Đổi trả', 'spec_value' => '1 đổi 1 trong 30 ngày nếu có lỗi phần cứng'],
            ];

            foreach ($specs as $s) {
                ProductSpecification::firstOrCreate(
                    ['product_id' => $item->id, 'spec_key' => $s['spec_key']],
                    array_merge($s, ['product_id' => $item->id])
                );
            }
        }
    }
}