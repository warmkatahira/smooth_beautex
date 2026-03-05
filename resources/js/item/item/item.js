import start_loading from '../../loading';

// 選択したファイル名を表示する
$('#image_file').on("change",function(){
    const file = this.files[0];
    if(file){
        $('#image_file_name').text(file.name);
    }
})

// 更新ボタンを押下した場合
$('#item_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#item_update_form").submit();
    }
});

// 削除ボタンを押下した場合
$('.item_delete_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("削除を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        // 削除対象の商品IDを要素にセット
        $('#item_id').val($(this).data('item-id'));
        $("#item_delete_form").submit();
    }
});