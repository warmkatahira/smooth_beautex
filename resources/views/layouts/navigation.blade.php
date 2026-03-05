<div id="navigation-bar">
    <!-- ロゴ -->
    <div class="navigation-btn">
        <a id="logo"><img src="{{ asset('image/navigation_logo.svg') }}"></a>
    </div>
    <!-- ダッシュボード -->
    <div class="navigation-btn">
        <a class="btn tippy_dashboard" href="{{ route('dashboard.index') }}"><i class="las la-home"></i></a>
    </div>
    <!-- 受注 -->
    <div class="navigation-btn">
        <a class="btn tippy_order" href="{{ route('order_menu.index') }}"><i class="las la-shopping-cart"></i></a>
    </div>
    <!-- 出荷 -->
    <div class="navigation-btn">
        <a class="btn tippy_shipping" href="{{ route('shipping_menu.index') }}"><i class="las la-truck"></i></a>
    </div>
    <!-- 商品 -->
    <div class="navigation-btn">
        <a class="btn tippy_item" href="{{ route('item_menu.index') }}"><i class="las la-tshirt"></i></a>
    </div>
    <!-- 在庫 -->
    <div class="navigation-btn">
        <a class="btn tippy_stock" href="{{ route('stock_menu.index') }}"><i class="las la-boxes"></i></a>
    </div>
    @can('warm_check')
        <!-- 設定 -->
        <div class="navigation-btn">
            <a class="btn tippy_setting" href="{{ route('setting_menu.index') }}"><i class="las la-cog"></i></a>
        </div>
    @endcan
    @can('warm_check')
        <!-- システム管理 -->
        <div class="navigation-btn">
            <a class="btn tippy_system_admin" href="{{ route('system_admin_menu.index') }}"><i class="las la-robot"></i></a>
        </div>
    @endcan
    <!-- プロフィール -->
    <div class="navigation-btn">
        <a class="btn tippy_profile" href="{{ route('profile.index') }}"><img id="profile" class="profile_image_navigation" src="{{ asset('storage/profile_images/' . Auth::user()->profile_image_file_name) }}"></a>
    </div>
</div>