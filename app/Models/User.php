<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'birthday',
    ];

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function productReviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class);
    }

    public function orderReviews()
    {
        return $this->hasMany(\App\Models\OrderReview::class);
    }
    
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class)->latest();
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function wishlists()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'wishlists');
    }

    public function getMemberRankAttribute(): string
    {
        $totalSpent = $this->orders()
            ->whereIn('status', ['Đã giao', 'Đã nhận hàng'])
            ->whereDoesntHave('returnRequest', function ($q) {
                $q->whereIn('status', ['Đã hoàn tiền', 'Đã đổi/trả']);
            })
            ->sum('total_price');

        if ($totalSpent >= 50000000) {
            return 'M-VIP';
        } elseif ($totalSpent >= 15000000) {
            return 'M-MEM';
        } elseif ($totalSpent >= 3000000) {
            return 'M-NEW';
        }

        return 'M-NULL';
    }

    public function getExpressShippingDiscountPercentAttribute(): int
    {
        return match ($this->member_rank) {
            'M-VIP' => 30,
            'M-MEM' => 20,
            'M-NEW' => 10,
            default => 5,
        };
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
}
