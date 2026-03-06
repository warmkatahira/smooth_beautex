import start_loading from '../../loading';

// 追加ボタンが押下されたら
$('#auto_process_create_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("自動処理を追加しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            $("#auto_process_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 更新ボタンが押下されたら
$('#auto_process_update_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("自動処理を更新しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            $("#auto_process_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 削除ボタンが押下されたら
$('.auto_process_delete_enter').on("click",function(){
    // 削除ボタンが押下された要素の親のtrタグを取得
    const tr = $(this).closest('tr');
    // 削除しようとしている要素の自動処理名を取得
    const auto_process_name = tr.find('.auto_process_name').text();
    try {
        // 処理を実行するか確認
        const result = window.confirm("自動処理を削除しますか？\n\n" + '自動処理名：' + auto_process_name);
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            // 削除対象の自動処理IDを要素にセット
            $('#auto_process_id').val($(this).data('auto-process-id'));
            $("#auto_process_delete_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// アクション区分を変更した場合
$('#action_type').on('change', function () {
    // 選択されたアクション区分を取得
    const selected = $(this).val();
    // 各アクションごとの設定
    const config = {
        shipping_method_update: {
            show: ['#action_value_delivery_company_wrapper'],
            enable: ['#action_value_delivery_company']
        },
        desired_delivery_time_update: {
            show: ['#action_value_desired_delivery_time_wrapper'],
            enable: ['#action_value_desired_delivery_time']
        },
        shipping_base_update: {
            show: ['#action_value_shipping_base_id_wrapper'],
            enable: ['#action_value_shipping_base_id']
        },
        order_item_create: {
            show: ['#action_value_order_item_create_wrapper'],
            enable: ['#action_value_order_item_id', '#action_value_shipping_quantity']
        },
        default: {
            show: ['#action_value_text_wrapper'],
            enable: ['#action_value_text']
        }
    };
    // まず全部非表示＆無効化
    $('#action_value_text_wrapper, #action_value_delivery_company_wrapper, #action_value_order_item_create_wrapper, #action_value_desired_delivery_time_wrapper, #action_value_shipping_base_id_wrapper')
        .hide();
    $('#action_value_text, #action_value_delivery_company, #action_value_order_item_id, #action_value_shipping_quantity, #action_value_desired_delivery_time, #action_value_shipping_base_id')
        .prop('disabled', true);
    // 選択された設定を適用
    const current = config[selected] || config.default;
    current.show.forEach(id => $(id).show());
    current.enable.forEach(id => $(id).prop('disabled', false));
});

// 初期化（リロード対策）
$('#action_type').trigger('change');