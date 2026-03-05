import start_loading from '../../loading';

// アップロードでファイルが選択されたら
$('.select_file input[type=file]').on("change",function(){
    // 処理を実行するか確認
    const result = window.confirm(
        "以下のアップロードを実行しますか？\n\n" +
        "対象：" + $('#upload_target option:selected').text() + "\n" +
        "タイプ：" + $('#upload_type option:selected').text()
    );
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#item_upload_form").submit();
    }
    // 要素をクリア
    $('.select_file').val(null);
});