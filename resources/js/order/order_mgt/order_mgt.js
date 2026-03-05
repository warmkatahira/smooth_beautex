import start_loading from '../../loading';
import get_checkbox from '../../checkbox';

// 引当処理ボタンを押下した場合
$('#allocate_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("引当処理を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#allocate_form").submit();
    } else {
        alert("引当処理はキャンセルされました。");
    }
});

// 受注削除を押下したら
$('#order_delete').on("click",function(){
    try {
        // チェックボックス要素関連の情報を取得
        const [chk, count, all] = get_checkbox();
        // 対象が1つ以上選択されているか
        if(count == 0){
            throw new Error('対象が選択されていません。');
        }
        // 確認のためのインプットボックスを表示
        const input = prompt(count + "件の受注を削除しますか？\n続行するには「delete」と入力してください。");
        // インプットボックスに「delete」と入力された場合のみ処理を実行
        if (input === 'delete') {
            // formタグのactionを変更
            $('#operation_div_form').attr('action', '/order_delete/delete');
            $("#operation_div_form").submit();
        } else {
            alert("削除はキャンセルされました。");
        }
    } catch (e) {
        alert(e.message);
    }
});

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('shipping_work_start_modal_close') === true){
        $('#shipping_work_start_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'shipping_work_start_modal_open'){
        try {
            // チェックボックス要素関連の情報を取得
            const [chk, count, all] = get_checkbox();
            // 対象が1つ以上選択されているか
            if(count == 0){
                throw new Error('出荷対象が選択されていません。');
            }
            // 選択件数をセット
            $('#chk_count').val(count);
            // テキストボックスをNullに変更
            $('#shipping_group_name').val(null);
            // 現在の日付をセット
            $('#estimated_shipping_date').val(new Date().toISOString().split('T')[0]);
            // モーダルを開く
            $('#shipping_work_start_modal').removeClass('hidden');
            // フォーカスをあてる
            $('#shipping_group_name').focus();
        } catch (e) {
            alert(e.message);
        }
    }
});

// 作業開始を押下したら
$('#shipping_work_start_enter').on("click",function(){
    try {
        // 作成する出荷グループの入力があるか
        if(!$('#shipping_group_name').val()){
            throw new Error('出荷グループを入力して下さい。');
        }
        // 21文字以上入力されていないか
        if($('#shipping_group_name').val().length > 20){
            throw new Error('出荷グループは20文字以内で入力して下さい。');
        }
        // 出荷予定日がセットされているか
        if(!$('#estimated_shipping_date').val()){
            throw new Error('出荷予定日を入力して下さい。');
        }
        // 処理を実行するか確認
        const result = window.confirm("以下の内容で出荷作業を開始しますか？\n\n" + 
                                        '出荷件数：' + $('#chk_count').val() + "件\n" + 
                                        '出荷グループ名：' + $('#shipping_group_name').val() + "\n" + 
                                        '出荷予定日：' + $('#estimated_shipping_date').val());
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            // formタグのactionを変更
            $('#operation_div_form').attr('action', '/shipping_work_start/enter');
            $("#operation_div_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});