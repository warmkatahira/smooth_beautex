import start_loading from '../../loading';

// 更新ボタンが押下されたら
$('#order_category_update_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("受注区分を更新しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#order_category_update_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 選択したファイル名を表示する
$('#image_file').on("change",function(){
    const file = this.files[0];
    if(file){
        $('#image_file_name').text(file.name);
    }
})