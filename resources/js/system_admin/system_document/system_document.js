// 追加ボタンが押下されたら
$('#system_document_create_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("システム資料を追加しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            $("#system_document_create_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 削除ボタンが押下されたら
$('.system_document_delete').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("システム資料を削除しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true) {
        $("#system_document_delete_form_" + $(this).val()).submit();
    }
});