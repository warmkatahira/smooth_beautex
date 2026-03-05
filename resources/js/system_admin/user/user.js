import start_loading from '../../loading';

// 更新ボタンを押下した場合
$('#user_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#user_update_form").submit();
    }
});