import start_loading from '../../loading';

// 更新ボタンを押下した場合
$('.order_item_lot_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("出荷検品ロットの更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        // 押されたボタンの order_item_id を取得して対応するフォームを特定
        const orderItemId = $(this).data('order-item-id');
        start_loading();
        $(`#order_item_lot_update_form_${orderItemId}`).submit();
    }
});