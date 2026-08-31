<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function message(Request $request)
    {
        $message = trim($request->input('message', ''));
        if (empty($message)) {
            return response()->json([
                'reply' => 'Chào bạn! Mình là **TechBot** - Trợ lý công nghệ TechZone. Bạn cần mình tư vấn thiết bị nào hoặc hỗ trợ thông tin gì nè?',
                'suggestions' => ['Laptop Gaming', 'Điện thoại mới nhất', 'Voucher giảm giá', 'Kiểm tra đơn hàng']
            ]);
        }

        $lower = mb_strtolower($message, 'UTF-8');

        // =========================================================================
        // 1. NHẬN DIỆN CÁC CÂU NGOÀI LỀ / SẢN PHẨM SHOP KHÔNG KINH DOANH
        // =========================================================================
        $outOfScopeKeywords = [
            'quần áo', 'váy', 'giày', 'dép', 'mỹ phẩm', 'son', 'phấn', 'trang điểm',
            'đồ ăn', 'thức ăn', 'trà sữa', 'cà phê', 'nước ngọt', 'bánh mì', 'cơm',
            'xe máy', 'ô tô', 'xe đạp', 'bất động sản', 'nhà đất', 'thuê phòng',
            'thời tiết', 'tình yêu', 'bói toán', 'xổ số', 'lô đề'
        ];

        foreach ($outOfScopeKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return response()->json([
                    'reply' => "Dạ tiếc là mặt hàng hoặc chủ đề liên quan đến **{$kw}** hiện tại **TechZone không có bán** nè! 😅\n\nShop mình chuyên về các thiết bị điện máy & công nghệ chính hãng như: **Laptop, Smartphone, Tablet, Smart TV, Gia dụng thông minh và Phụ kiện âm thanh**. Bạn có muốn tham khảo các sản phẩm công nghệ này không?",
                    'suggestions' => ['Xem Laptop Gaming', 'Xem Điện thoại', 'Voucher giảm giá']
                ]);
            }
        }

        // =========================================================================
        // 2. CHÀO HỎI / XÃ GIAO
        // =========================================================================
        if (in_array($lower, ['hi', 'hello', 'chào', 'alo', 'ê', 'bot ơi', 'shop ơi', 'hey', 'có ai không'])) {
            $userName = Auth::check() ? Auth::user()->name : 'bạn';
            return response()->json([
                'reply' => "Dạ TechBot chào {$userName}! Mình có thể hỗ trợ bạn tìm kiếm máy tính, điện thoại, tra cứu đơn hàng hoặc săn mã voucher khuyến mãi nè. Bạn đang quan tâm dòng sản phẩm nào?",
                'suggestions' => ['Laptop Gaming', 'Điện thoại Flagship', 'Voucher khuyến mãi']
            ]);
        }

        // =========================================================================
        // 3. TRA CỨU ĐƠN HÀNG (THEO MÃ HOẶC USER HIỆN TẠI)
        // =========================================================================
        if (preg_match('/(ord[-\s0-9]+)/i', $message, $matches) || str_contains($lower, 'đơn hàng') || str_contains($lower, 'kiểm tra đơn') || str_contains($lower, 'tra cứu đơn')) {
            if (isset($matches[1])) {
                $code = strtoupper(str_replace(' ', '', $matches[1]));
                $order = Order::where('order_code', 'LIKE', "%{$code}%")->first();
                if ($order) {
                    return response()->json([
                        'reply' => "📦 **Chi tiết đơn hàng #{$order->order_code}**\n- Trạng thái: **{$order->status}**\n- Phương thức thanh toán: **{$order->payment_method} ({$order->payment_status})**\n- Tổng giá trị: **" . number_format($order->total_price, 0, ',', '.') . "₫**\n- Địa chỉ nhận hàng: {$order->shipping_address}",
                        'suggestions' => ['Xem lịch sử mua hàng', 'Chính sách đổi trả']
                    ]);
                }
            }

            if (Auth::check()) {
                $latestOrder = Order::where('user_id', Auth::id())->latest()->first();
                if ($latestOrder) {
                    return response()->json([
                        'reply' => "📦 **Đơn hàng gần nhất của bạn:**\n- Mã đơn: **#{$latestOrder->order_code}**\n- Trạng thái: **{$latestOrder->status}**\n- Tổng tiền thanh toán: **" . number_format($latestOrder->total_price, 0, ',', '.') . "₫**\n- Thanh toán: **{$latestOrder->payment_method} ({$latestOrder->payment_status})**",
                        'suggestions' => ['Lịch sử đơn hàng trong Profile', 'Chính sách bảo hành']
                    ]);
                }
            }

            return response()->json([
                'reply' => 'Bạn vui lòng cung cấp mã đơn hàng (ví dụ: `ORD-2026...`) để TechBot tra cứu tiến trình giao hàng ngay lập tức nhé!',
                'suggestions' => ['Xem đơn hàng trong Profile']
            ]);
        }

        // =========================================================================
        // 4. CHÍNH SÁCH BẢO HÀNH & ĐỔI TRẢ HÀNG
        // =========================================================================
        if (str_contains($lower, 'bảo hành') || str_contains($lower, 'đổi trả') || str_contains($lower, 'hỏng') || str_contains($lower, 'lỗi') || str_contains($lower, 'sửa chữa')) {
            return response()->json([
                'reply' => "🛡️ **Chính sách Bảo Hành & Đổi Trả tại TechZone:**\n• **1 đổi 1 trong vòng 30 ngày** đầu tiên nếu phát sinh lỗi phần cứng từ nhà sản xuất.\n• Bảo hành chính hãng từ **12 đến 24 tháng** trên toàn quốc.\n• Bạn có thể gửi yêu cầu đổi/trả trực tiếp ngay tại trang **Đổi / Trả của tôi** trong Profile.",
                'suggestions' => ['Xem danh sách đơn để đổi trả', 'Phí vận chuyển']
            ]);
        }

        // =========================================================================
        // 5. MÃ GIẢM GIÁ & VOUCHER KHUYẾN MÃI
        // =========================================================================
        if (str_contains($lower, 'voucher') || str_contains($lower, 'mã giảm') || str_contains($lower, 'khuyến mãi') || str_contains($lower, 'ưu đãi') || str_contains($lower, 'coupon') || str_contains($lower, 'giảm giá')) {
            $activeCoupons = Coupon::where('is_active', true)->take(4)->get();
            $reply = "🎁 **Các mã ưu đãi HOT đang mở tại TechZone:**\n";
            foreach ($activeCoupons as $cp) {
                $valText = $cp->type === 'percent' ? "{$cp->value}%" : number_format($cp->value, 0, ',', '.') . "₫";
                $target = str_starts_with($cp->code, 'SHIP') ? 'Ship Siêu Tốc' : 'Đơn hàng';
                $reply .= "• Mã **`{$cp->code}`**: Giảm {$valText} cho {$target} (Đơn từ " . number_format($cp->min_order_value, 0, ',', '.') . "₫)\n";
            }
            $reply .= "\n💡 *Bạn có thể áp dụng cùng lúc 2 voucher (1 Đơn hàng + 1 Ship Siêu Tốc) tại Giỏ hàng!*";

            return response()->json([
                'reply' => $reply,
                'suggestions' => ['Xem giỏ hàng', 'Hạng thành viên']
            ]);
        }

        // =========================================================================
        // 6. HẠNG THÀNH VIÊN TECHZONE (M-CLUB)
        // =========================================================================
        if (str_contains($lower, 'hạng') || str_contains($lower, 'rank') || str_contains($lower, 'thành viên') || str_contains($lower, 'm-club') || str_contains($lower, 'smember') || str_contains($lower, 'tích lũy')) {
            return response()->json([
                'reply' => "💳 **Đặc quyền Hạng thành viên TechZone (M-Club):**\n• **M-NULL** (< 3tr): Giảm 5% ship hỏa tốc.\n• **M-NEW** (3tr - 15tr): Giảm 10% ship hỏa tốc + Voucher 50K.\n• **M-MEM** (15tr - 50tr): Giảm 20% ship hỏa tốc + Voucher 100K + Sinh nhật 200K.\n• **M-VIP** (> 50tr): Giảm 30% ship hỏa tốc + Voucher 300K + Sinh nhật 500K + Freeship không giới hạn.",
                'suggestions' => ['Xem hạng của tôi', 'Voucher giảm giá']
            ]);
        }

        // =========================================================================
        // 7. PHÍ VẬN CHUYỂN & GIAO NHẬN
        // =========================================================================
        if (str_contains($lower, 'ship') || str_contains($lower, 'vận chuyển') || str_contains($lower, 'giao hàng') || str_contains($lower, 'phí ship') || str_contains($lower, 'hỏa tốc')) {
            return response()->json([
                'reply' => "🚚 **Bảng phí vận chuyển TechZone:**\n• **Giao Tiêu Chuẩn (1-2 ngày):** 30.000₫ (🔥 **Miễn phí 100%** cho mọi đơn hàng từ **300.000₫**).\n• **Giao Siêu Tốc (Trong 2 Giờ):** 120.000₫ (Được giảm giá trực tiếp từ 5% - 30% tùy hạng thành viên và có thể áp thêm mã voucher `SHIPFAST50K`).",
                'suggestions' => ['Tìm Laptop', 'Xem giỏ hàng']
            ]);
        }

        // =========================================================================
        // 8. TÌM KIẾM THEO NHU CẦU CÔNG NGHỆ TỰ NHIÊN
        // =========================================================================
        $query = Product::with(['category', 'brand']);

        // Nhu cầu Gaming / Chơi game
        if (str_contains($lower, 'chơi game') || str_contains($lower, 'gaming') || str_contains($lower, 'game') || str_contains($lower, 'máy để chơi game') || str_contains($lower, 'rtx') || str_contains($lower, 'loq') || str_contains($lower, 'zephyrus')) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', '%gaming%')
                  ->orWhere('name', 'LIKE', '%RTX%')
                  ->orWhere('name', 'LIKE', '%LOQ%')
                  ->orWhere('name', 'LIKE', '%Zephyrus%')
                  ->orWhere('name', 'LIKE', '%ROG%')
                  ->orWhere('name', 'LIKE', '%Legion%')
                  ->orWhereHas('category', fn($c) => $c->where('slug', 'laptop'));
            })->orderBy('price', 'desc');

            $products = $query->take(3)->get();
            return $this->formatProductResponse($products, "🔥 Đây là những dòng máy cấu hình mạnh mẽ, chuyên dụng để **chiến game mượt mà & đồ họa nặng** tại TechZone nè:");
        }

        // Nhu cầu Văn phòng / Học tập / Mỏng nhẹ
        if (str_contains($lower, 'văn phòng') || str_contains($lower, 'học tập') || str_contains($lower, 'mỏng nhẹ') || str_contains($lower, 'sinh viên') || str_contains($lower, 'macbook') || str_contains($lower, 'pavilion')) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', '%MacBook%')
                  ->orWhere('name', 'LIKE', '%Pavilion%')
                  ->orWhere('name', 'LIKE', '%Air%')
                  ->orWhere('name', 'LIKE', '%XPS%')
                  ->orWhere('name', 'LIKE', '%Ultrabook%');
            });

            $products = $query->take(3)->get();
            return $this->formatProductResponse($products, "💻 Gợi ý các dòng Laptop mỏng nhẹ, pin trâu, phục vụ xuất sắc cho **học tập và công việc văn phòng**:");
        }

        // Nhu cầu Điện thoại / Smartphone / iPhone / Chụp ảnh
        if (str_contains($lower, 'điện thoại') || str_contains($lower, 'smartphone') || str_contains($lower, 'iphone') || str_contains($lower, 'samsung') || str_contains($lower, 'redmi') || str_contains($lower, 'chụp ảnh')) {
            $query->whereHas('category', fn($c) => $c->where('slug', 'like', '%dien-thoai%')->orWhere('name', 'like', '%điện thoại%'))
                  ->orWhere('name', 'LIKE', '%iPhone%')
                  ->orWhere('name', 'LIKE', '%Redmi%')
                  ->orWhere('name', 'LIKE', '%Galaxy%');

            $products = $query->take(3)->get();
            return $this->formatProductResponse($products, "📱 Danh sách các dòng điện thoại thông minh bán chạy nhất tại shop:");
        }

        // Nhu cầu Smart TV / Tivi
        if (str_contains($lower, 'tivi') || str_contains($lower, 'tv') || str_contains($lower, 'smart tv') || str_contains($lower, '4k') || str_contains($lower, 'qled')) {
            $query->whereHas('category', fn($c) => $c->where('slug', 'like', '%tv%')->orWhere('name', 'like', '%TV%'))
                  ->orWhere('name', 'LIKE', '%QLED%')
                  ->orWhere('name', 'LIKE', '%TV%');

            $products = $query->take(3)->get();
            return $this->formatProductResponse($products, "📺 Danh sách Smart TV 4K màn hình lớn, hình ảnh sắc nét cho gia đình:");
        }

        // Tìm theo từ khóa tổng quát trong Database
        $generalProducts = Product::with(['category', 'brand'])
            ->where(function ($q) use ($lower) {
                $q->where('name', 'LIKE', "%{$lower}%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$lower}%"))
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'LIKE', "%{$lower}%"));
            })
            ->take(3)
            ->get();

        if ($generalProducts->count() > 0) {
            return $this->formatProductResponse($generalProducts, "🔍 TechBot tìm thấy các sản phẩm phù hợp với yêu cầu của bạn:");
        }

        // =========================================================================
        // 9. NẾU KHÔNG TÌM THẤY & KHÔNG HIỂU Ý
        // =========================================================================
        return response()->json([
            'reply' => "TechBot chưa hiểu rõ câu hỏi hoặc hiện tại shop **chưa có sản phẩm chính xác** như bạn vừa mô tả. 🤖\n\nBạn có thể thử tìm theo tên hãng (*Apple, Asus, Lenovo, Samsung...*) hoặc các dòng sản phẩm: **Laptop, Điện thoại, Smart TV, Tablet, Thiết bị gia dụng** xem sao nhé!",
            'suggestions' => ['Tìm Laptop Gaming', 'Tìm Điện thoại Flagship', 'Voucher giảm giá', 'Kiểm tra đơn hàng']
        ]);
    }

    private function formatProductResponse($products, $introText)
    {
        if ($products->count() === 0) {
            $products = Product::latest()->take(3)->get();
            $introText = "Rất tiếc sản phẩm bạn tìm tạm thời hết hàng, bạn tham khảo các dòng sản phẩm nổi bật này nhé:";
        }

        $reply = "{$introText}\n\n";
        $productData = [];
        foreach ($products as $p) {
            $reply .= "• **{$p->name}**\n  ➥ Giá ưu đãi: `" . number_format($p->price, 0, ',', '.') . "₫` (Kho: {$p->stock} máy)\n";
            $productData[] = [
                'name' => $p->name,
                'price' => number_format($p->price, 0, ',', '.') . '₫',
                'slug' => $p->slug,
                'image' => $p->image ? asset('storage/' . $p->image) : null,
            ];
        }

        return response()->json([
            'reply' => $reply,
            'products' => $productData,
            'suggestions' => ['Voucher giảm giá', 'Phí vận chuyển', 'Chính sách bảo hành']
        ]);
    }
}