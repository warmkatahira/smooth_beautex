import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 共通
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/sass/theme.scss',
                'resources/js/loading.js',
                'resources/js/navigation.js',
                'resources/js/search.js',
                'resources/js/file_select.js',
                'resources/js/search_date.js',
                'resources/sass/loading.scss',
                'resources/sass/navigation.scss',
                'resources/js/dropdown.js',
                'resources/js/image_fade_in.js',
                'resources/js/chart_color.js',
                'resources/js/checkbox.js',
                'resources/sass/dropdown.scss',
                'resources/sass/height_adjustment.scss',
                'resources/sass/welcome.scss',
                'resources/sass/common.scss',
                // 認証
                'resources/js/auth/register.js',
                // ダッシュボード
                'resources/js/dashboard/chart.js',
                // 受注
                'resources/js/order/order_import/order_import.js',
                'resources/js/order/order_mgt/order_mgt.js',
                'resources/js/order/order_detail/order_detail.js',
                // 出荷
                'resources/js/shipping/shipping_mgt/shipping_mgt.js',
                'resources/js/shipping/shipping_inspection/shipping_inspection.js',
                'resources/js/shipping/shipping_work_end/shipping_work_end.js',
                // 帳票
                'resources/sass/shipping/document/document_common.scss',
                'resources/sass/order/document/hikiatemachi_list.scss',
                'resources/sass/shipping/document/total_picking_list.scss',
                'resources/sass/shipping/document/delivery_note.scss',
                // 商品
                'resources/js/item/item/item.js',
                'resources/js/item/item_upload/item_upload.js',
                'resources/js/item/item_qr_analysis/item_qr_analysis.js',
                // 在庫
                'resources/js/stock/stock/stock.js',
                'resources/js/stock/input_stock_operation/input_stock_operation.js',
                'resources/js/stock/receiving_inspection/receiving_inspection.js',
                'resources/sass/stock/receiving_inspection/receiving_inspection.scss',
                // 設定
                'resources/js/setting/shipping_base/shipping_base.js',
                'resources/js/setting/base_shipping_method/base_shipping_method.js',
                'resources/js/setting/shipper/shipper.js',
                'resources/js/setting/order_category/order_category.js',
                'resources/js/setting/auto_process/auto_process.js',
                'resources/js/setting/auto_process/auto_process_condition.js',
                // システム管理
                'resources/js/system_admin/base/base.js',
                'resources/js/system_admin/user/user.js',
                'resources/js/system_admin/system_document/system_document.js',
                // プロフィール
                'resources/js/profile/profile.js',
                'resources/sass/profile/profile.scss',
                'resources/sass/profile/profile_image.scss',
            ],
            refresh: true,
        }),
    ],
});
