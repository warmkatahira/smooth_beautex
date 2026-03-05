import start_loading from '../../loading';

// 処理ボタンが押下されたら
$('.input_stock_operation_enter').on("click",function(){
    try {
        // 処理タイプを取得
        const proc_type = $(this).data('proc-type');
        // 変数を初期化
        let ok_count = 0;
        let ng_count = 0;
        let minus_count = 0;
        // 数量の要素の分だけループ処理
        $('.quantity').each(function() {
            // 要素の値を取得
            let value = $(this).val();
            // null または 0 の場合は無視する
            if(value === "" || value === "0"){
                return;
            }
            // 整数の場合
            if(/^[-]?\d+$/.test(value)){
                ok_count++;
                // さらにマイナス整数かチェック
                if(parseInt(value, 10) < 0){
                    minus_count++;
                }
            }
            // 整数ではない場合
            if(!/^[-]?\d+$/.test(value)){
                ng_count++;
            }
        });
        // NGカウントがある場合
        if(ng_count > 0){
            throw new Error('数量に整数以外が存在しています。');
        }
        // OKカウントがない場合
        if(ok_count === 0){
            throw new Error('数量を入力して下さい。');
        }
        // 入庫処理でマイナス数量がある場合
        if(proc_type === '入庫' && minus_count > 0){
            throw new Error('入庫のため、数量にはマイナスを入力できません。');
        }
        // コメントを入力
        const comment = prompt("今回の処理に対してコメントを入力して下さい。\n※何もコメントを残さない場合は、このまま「OK」を押して下さい。");
        // キャンセルされた場合
        if(comment === null){
            throw new Error('キャンセルしました。');
        }
        // コメントが255文字以内であるか確認
        if(comment.length > 255){
            throw new Error('コメントは255文字以内で入力して下さい。');
        }
        // 処理を実行するか確認
        const result = window.confirm(proc_type + "処理を実行しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#comment").val(comment);
            $("#proc_type").val(proc_type);
            $("#input_stock_operation_enter_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 数量のツールチップ
tippy('.tippy_quantity', {
    content: "調整で減算する場合は、マイナス符号をつけて下さい。<br>",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});