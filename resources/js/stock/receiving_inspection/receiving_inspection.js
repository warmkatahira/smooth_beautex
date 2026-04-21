import audio_play from '../../audio';
import start_loading from '../../loading';

// 画面読み込み時の処理
$(document).ready(function() {
    // スキャン前の準備
    set_item_id_code();
});

// 商品識別コードが変更されたら
$('#item_id_code').on("change",function(){
    // 商品識別コードに値がある場合のみ処理する
    if($('#item_id_code').val()){
        // AJAX通信のURLをセット
        const ajax_url = '/receiving_inspection/ajax_check_item_id_code';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: ajax_url,
            type: 'GET',
            data: {
                item_id_code: $('#item_id_code').val(),
            },
            dataType: 'json',
            success: function(data){
                try {
                    console.log(data);
                    // エラーがある場合
                    if(data['error_message']) {
                        // エラーを返す
                        throw new Error(data['error_message']);
                    }
                    // エラーがある場合
                    if(data['exp_lot_check_result']) {
                        // エラーを返す
                        throw new Error(data['exp_lot_check_result']);
                    }
                    // JANコードで検品されているかつ、is_lot_managedがtrueの場合
                    if(data['item_id_type'] === 'JAN' && data['item']['is_lot_managed']){
                        // LOT入力モーダルを表示
                        $('#lot_exp_input_modal').removeClass('hidden');
                        $('#lot').val(null);
                        $('#exp').val(null);
                        $('#lot_length').text('(' + (data['item']['lot_1_length'] + data['item']['lot_2_length']) + '桁)');
                        $('#lot').focus();
                        return;
                    }
                    // 検品OK時の処理
                    inspection_ok(data);
                    // 検品数量合計を更新
                    update_inspection_quantity_total();
                    // 音を再生
                    audio_play('proc');
                    // スキャン前の準備
                    set_item_id_code();
                } catch (e) {
                    // 検品NG時の処理
                    inspection_ng(e.message);
                }
            },
            error: function(){
                alert('失敗');
            }
        });
    }
});

// 確定ボタンが押下されたら
$('#lot_exp_input_enter').on("click",function(){
    // LOTに値がある場合のみ処理する
    if($('#lot').val()){
        // モーダルを閉じる
        $('#lot_exp_input_modal').addClass('hidden');
        var ajax_url = '/receiving_inspection/ajax_check_lot_exp';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: ajax_url,
            type: 'POST',
            data: {
                lot: $('#lot').val(),
                exp: $('#exp').val(),
            },
            dataType: 'json',
            success: function(data){
                try {
                    set_item_id_code();
                    // エラーがある場合
                    if(data['error_message']) {
                        // エラーを返す
                        throw new Error(data['error_message']);
                    }
                    // 検品OK時の処理
                    inspection_ok(data);
                } catch (e) {
                    // 検品NG時の処理
                    inspection_ng(e.message);
                }
            },
            error: function(){
                alert('失敗');
            }
        });
    }
});

// 検品OK時の処理
function inspection_ok(data){
    // nullなら空文字を格納する
    const lot = data['lot'] ?? '';
    const exp = data['exp'] ?? '';
    const rowId = data['item']['item_id'] + lot + exp;
    // trueであれば新しくスキャンされた商品なので、行を追加
    if(data['add']){
        // 新しい行を作成
        const newRow = $('<tr>').attr('id', rowId).addClass('text-xs');
        // 新しいセルを追加
        const newCell1 = $('<td>').text(data['item']['item_code']).addClass('py-1 px-2 border');
        const newCell2 = $('<td>').text(data['item']['item_jan_code']).addClass('py-1 px-2 border');
        const newCell3 = $('<td>').text(data['item']['item_name']).addClass('py-1 px-2 border');
        const newCell4 = $('<td>').text(data['lot']).addClass('py-1 px-2 border text-center');
        const newCell5 = $('<td>').text(data['exp']).addClass('py-1 px-2 border text-center');
        const newCell6 = $('<td>').text(data['quantity']).addClass('inspection_quantity py-1 px-2 border text-right').attr('id', rowId + '_quantity');
        // セルを行に追加
        newRow.append(newCell1);
        newRow.append(newCell2);
        newRow.append(newCell3);
        newRow.append(newCell4);
        newRow.append(newCell5);
        newRow.append(newCell6);
        // 行をテーブルのtbodyに追加
        $('#receiving_complete_table tbody').append(newRow);
    }
    // falseであれば既にスキャンされていた商品なので、数量を更新
    if(!data['add']){
        $('#' + rowId + '_quantity').text(data['quantity']);
    }
}

// 検品NG時の処理
function inspection_ng(message){
    audio_play('ng');
    // モーダルを開く
    $('#item_id_code_alert_modal').removeClass('hidden');
    // エラーをメッセージに出力
    $('#error_message').html(message);
    // この要素にフォーカスをあてて、モーダル表示中にスキャンが進まないようにしている
    $('#item_id_code_alert_modal_focus_element').focus();
}

// 商品識別コードをクリアしてフォーカス
function set_item_id_code(){
    $('#item_id_code').val(null);
    $('#item_id_code').focus();
    return;
}

// 検品数量合計を更新
function update_inspection_quantity_total(){
    // 検品数量合計の変数を初期化
    let inspection_quantity_total = 0;
    // クラス名を元にループ処理
    $('.inspection_quantity').each(function(){
        // 検品数量を加算
        inspection_quantity_total += parseInt($(this).text());
    });
    // 出力
    $('#total_pcs').html(inspection_quantity_total);
}

// モーダル表示中にフォーカスがあたる要素が変更されたら
$('#item_id_code_alert_modal_focus_element').on("change",function(){
    audio_play('ng');
    $('#item_id_code_alert_modal_focus_element').val(null);
    $('#item_id_code_alert_modal_focus_element').focus();
});

const fixedFocusElement = $('#item_id_code_alert_modal_focus_element');

// フォーカスを強制的に固定する
fixedFocusElement.on('blur', function () {
    setTimeout(() => {
        fixedFocusElement.focus();
    }, 0); // フォーカスが外れた瞬間に再フォーカス
});

// クリックイベント
$(document).on('click', function(e) {
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('item_id_code_alert_modal_close') == true){
        // モーダルを閉じる
        $('#item_id_code_alert_modal').addClass('hidden');
        // スキャン前の準備
        set_item_id_code();
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('lot_exp_input_modal_close') == true){
        // モーダルを閉じる
        $('#lot_exp_input_modal').addClass('hidden');
        // スキャン前の準備
        set_item_id_code();
    }
});

// 入庫検品確定が押下されたら
$('#receiving_inspection_enter').on("click",function(){
    try {
        // 合計PCSが1以上なければエラーを返す
        if($('#total_pcs').html() == 0 || !$.isNumeric($('#total_pcs').html())){
            throw new Error('商品がスキャンされていません。');
        }
        // 入庫倉庫が選択されていない場合
        if($('#base_id').val() === ''){
            throw new Error('入庫倉庫が選択されていません。');
        }
        // コメントを入力
        const comment = prompt("今回の入庫に対してコメントを入力して下さい。\n※何もコメントを残さない場合は、このまま「OK」を押して下さい。");
        // キャンセルされた場合
        if(comment === null){
            throw new Error('キャンセルしました。');
        }
        // コメントが255文字以内であるか確認
        if(comment.length > 255){
            throw new Error('コメントは255文字以内で入力して下さい。');
        }
        // 処理を実行するか確認
        const result = window.confirm("入庫検品確定を実行しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#comment").val(comment);
            $("#receiving_inspection_enter_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});