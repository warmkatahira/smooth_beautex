<div id="navigation-bar">
    <div id="navigation_top_div" class="flex flex-col sticky top-0 z-[999] bg-theme-sub">
        <!-- ロゴ -->
        <img id="logo" src="{{ asset('image/smooth_logo.svg') }}">
        <!-- 顧客名 -->
        <p class="text-center pt-2 cursor-default">{{ SystemEnum::CUSTOMER_NAME_JP }}</p>
        <!-- システム名 -->
        <p class="text-center cursor-default">{{ SystemEnum::SYSTEM_NAME_JP }}</p>
    </div>
    <div class="flex flex-col gap-3 pt-7 pl-5">
        <!-- ダッシュボード -->
        <x-navigation-btn route="dashboard.index" label="ダッシュボード" icon="las la-home" isRightMargin="true" />
        <!-- 受注 -->
        <div class="flex flex-col gap-0.5">
            <x-navigation-btn label="受注" icon="las la-shopping-cart" openCloseKey="order" />
             <div class="navigation-content hidden">
                <x-navigation-btn route="order_import.index" label="受注取込" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="order_mgt.index" label="受注管理" isLeftMargin="true" isRightMargin="true" />
            </div>
        </div>
        <!-- 出荷 -->
        <div class="flex flex-col gap-0.5">
            <x-navigation-btn label="出荷" icon="las la-truck" openCloseKey="shipping" />
             <div class="navigation-content hidden">
                <x-navigation-btn route="shipping_mgt.index" label="出荷管理" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="shipping_inspection.index" label="出荷検品" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="shipping_work_end.index" label="出荷完了" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="shipping_work_end_history.index" label="出荷完了履歴" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="shipping_history.index" label="出荷履歴" isLeftMargin="true" isRightMargin="true" />
            </div>
        </div>
        <!-- 商品 -->
        <div class="flex flex-col gap-0.5">
            <x-navigation-btn label="商品" icon="las la-tshirt" openCloseKey="item" />
             <div class="navigation-content hidden">
                <x-navigation-btn route="item.index" label="商品" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="item_upload.index" label="商品アップロード" isLeftMargin="true" isRightMargin="true" />
            </div>
        </div>
        <!-- 在庫 -->
        <div class="flex flex-col gap-0.5">
            <x-navigation-btn label="在庫" icon="las la-boxes" openCloseKey="stock" />
             <div class="navigation-content hidden">
                <x-navigation-btn route="stock.index_by_item" label="在庫" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="receiving_inspection.index" label="入庫検品" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="input_stock_operation.index" label="入力在庫数操作" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="stock_history.index" label="在庫履歴" isLeftMargin="true" isRightMargin="true" />
            </div>
        </div>
        <!-- 設定 -->
        <div class="flex flex-col gap-0.5">
            <x-navigation-btn label="設定" icon="las la-cog" openCloseKey="setting" />
            <div class="navigation-content hidden">
                <x-navigation-btn route="shipping_base.index" label="出荷倉庫" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="base_shipping_method.index" label="倉庫別配送方法" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="shipper.index" label="荷送人" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="order_category.index" label="受注区分" isLeftMargin="true" isRightMargin="true" />
                <x-navigation-btn route="auto_process.index" label="自動処理" isLeftMargin="true" isRightMargin="true" />
            </div>
        </div>
        @can('admin_check')
            <!-- システム管理 -->
             <div class="flex flex-col gap-0.5">
                <x-navigation-btn label="システム管理" icon="las la-robot" openCloseKey="system_admin" />
                <div class="navigation-content hidden">
                    <x-navigation-btn route="base.index" label="倉庫" isLeftMargin="true" isRightMargin="true" />
                    <x-navigation-btn route="user.index" label="ユーザー" isLeftMargin="true" isRightMargin="true" />
                    <x-navigation-btn route="operation_log.index" label="操作ログ" isLeftMargin="true" isRightMargin="true" />
                    <x-navigation-btn route="holiday.index" label="祝日" isLeftMargin="true" isRightMargin="true" />
                </div>
            </div>
        @endcan
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nav = document.getElementById('navigation-bar');
        const navScrollPosition = 'navScrollPosition';
        // ページを離れる前にスクロール位置を保存
        window.addEventListener('beforeunload', () => {
            localStorage.setItem(navScrollPosition, nav.scrollTop);
        });
        // ページロード後にスクロール位置を復元
        const saved = localStorage.getItem(navScrollPosition);
        if(saved !== null){
            nav.scrollTop = saved;
        }
    });
    const navOpenStates = 'navOpenStates';
    // ページ読み込み時：保存されている開閉状態を復元
    const openStates = JSON.parse(localStorage.getItem(navOpenStates)) || {};
    for(const key in openStates){
        if(openStates[key]){
            const $section = $(`.navigation-open-close[data-open-close-key="${key}"]`);
            const $content = $section.closest('.navigation-btn-div').next('.navigation-content');
            const $arrow = $section.find('.arrow');

            $content.removeClass('hidden');
            $arrow.addClass('open');
        }
    }
    // クリック時
    $('.navigation-open-close').on('click', function() {
        // 要素を取得
        const $content = $(this).closest('.navigation-btn-div').next('.navigation-content');
        const $arrow = $(this).find('.arrow');
        const key = $(this).data('open-close-key');
        // クラスの付け外し
        $content.toggleClass('hidden');
        $arrow.toggleClass('open');
        // 現在の開閉状態を保存
        const updatedStates = JSON.parse(localStorage.getItem(navOpenStates)) || {};
        updatedStates[key] = !$content.hasClass('hidden');
        localStorage.setItem(navOpenStates, JSON.stringify(updatedStates));
    });
</script>