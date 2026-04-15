import start_loading from '../../loading';

// 追加ボタンを押下した場合
$('#user_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#user_form").submit();
    }
});

// 更新ボタンを押下した場合
$('#user_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#user_form").submit();
    }
});

// 削除ボタンを押下した場合
$('.password_reset_enter').on("click",function(){
    // 確認のためのインプットボックスを表示
    const input = prompt("パスワードをリセットしますか？\n続行するには「reset」と入力してください。");
    // インプットボックスに「delete」と入力された場合のみ処理を実行
    if (input === 'reset') {
        // リセット対象のユーザーNoを要素にセット
        $('#user_no').val($(this).data('user-no'));
        $("#password_reset_form").submit();
    } else {
        alert("パスワードリセットはキャンセルされました。");
    }
});