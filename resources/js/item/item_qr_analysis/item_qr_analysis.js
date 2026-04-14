import start_loading from '../../loading';

// 反映ボタンを押下した場合
$('.item_qr_analysis_result_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("商品QR解析結果の反映を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        // 反映対象の商品QR解析履歴IDを要素にセット
        $('#item_qr_analysis_history_id').val($(this).data('item-qr-analysis-history-id'));
        $("#item_qr_analysis_result_update_form").submit();
    }
});