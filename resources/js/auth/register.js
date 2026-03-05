import start_loading from '../loading';

// 登録ボタンを押下した場合
$('#register_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("ユーザー登録を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#register_form").submit();
    }
});