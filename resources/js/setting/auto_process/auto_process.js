import start_loading from '../../loading';

// 追加ボタンが押下されたら
$('#auto_process_create_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("自動処理を追加しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#auto_process_create_form").submit();
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
        if(result == true) {
            start_loading();
            $("#auto_process_update_form").submit();
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
        if(result == true) {
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
    // 配送方法を変更の場合
    if(selected === 'shipping_method_change'){
        // 表示/非表示を切り替え
        $('#action_value_text_wrapper').hide();
        $('#action_value_delivery_company_wrapper').show();
        $('#action_value_order_item_create_wrapper').hide();
        // disabled属性を切り替え
        $('#action_value_text').prop('disabled', true);
        $('#action_value_delivery_company').prop('disabled', false);
    // 配送方法を変更の場合
    }else if(selected === 'order_item_create'){
        // 表示/非表示を切り替え
        $('#action_value_text_wrapper').hide();
        $('#action_value_delivery_company_wrapper').hide();
        $('#action_value_order_item_create_wrapper').show();
        // disabled属性を切り替え
        $('#action_value_text').prop('disabled', true);
        $('#action_value_delivery_company').prop('disabled', true);
    // 配送方法を変更以外の場合
    }else{
        // 表示/非表示を切り替え
        $('#action_value_text_wrapper').show();
        $('#action_value_delivery_company_wrapper').hide();
        $('#action_value_order_item_create_wrapper').hide();
        // disabled属性を切り替え
        $('#action_value_text').prop('disabled', false);
        $('#action_value_delivery_company').prop('disabled', true);
    }
});

// 初期化（リロード対策）
$('#action_type').trigger('change');