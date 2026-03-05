<div class="flex flex-row ml-5 mt-1 items-start divide-x divide-black border border-black">
    <!-- 商品単位表示 -->
    <a href="{{ route('stock.index_by_item') }}" class="btn display_switch tippy_display_by_item px-2 py-1 {{ request()->routeIs('stock.index_by_item') ? 'bg-theme-sub' : 'bg-white' }}">
        <i class="las la-tshirt la-2x"></i>
    </a>
    <!-- 在庫単位表示 -->
    <a href="{{ route('stock.index_by_stock') }}" class="btn display_switch tippy_display_by_stock px-2 py-1 {{ request()->routeIs('stock.index_by_stock') ? 'bg-theme-sub' : 'bg-white' }}">
        <i class="las la-boxes la-2x"></i>
    </a>
</div>