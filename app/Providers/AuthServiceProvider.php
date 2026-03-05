<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// モデル
use App\Models\User;
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseServiceProvider;

class AuthServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // 権限が「admin」の場合のみ許可
        Gate::define('admin_check', function (User $user){
            return ($user->role_id === 'admin');
        });
        // 会社が「warm」の場合のみ許可
        Gate::define('warm_check', function (User $user){
            return ($user->company_id === 'warm');
        });
        // 出荷検品実績削除が可能な受注である場合のみ許可
        // 会社IDがwarmかつ、出荷検品済みかつ、注文ステータスが作業中である
        Gate::define('shipping_inspection_actual_deletable_check', function (User $user, Order $order){
            return $user->company_id === 'warm' &&
                   $order->is_shipping_inspection_complete === 1 &&
                   $order->order_status_id === OrderStatusEnum::SAGYO_CHU;
        });
    }
}
