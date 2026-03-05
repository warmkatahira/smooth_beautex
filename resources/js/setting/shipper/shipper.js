import start_loading from '../../loading';

// 更新ボタンが押下されたら
$('#shipper_update_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("荷送人を更新しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#shipper_update_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});