import audio_play from '../../audio';
import start_loading from '../../loading';

// 画面読み込み時の処理
$(document).ready(function() {
    // 受注管理IDにフォーカス
    $('#order_control_id').focus();
});

// 受注管理IDが変更されたら
$('#order_control_id').on("change",function(){
    // 受注管理IDに値がある場合のみ処理する
    if($('#order_control_id').val()){
        const ajax_url = '/shipping_inspection/ajax_check_order_control_id';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: ajax_url,
            type: 'POST',
            data: {
                order_control_id: $('#order_control_id').val(),
            },
            dataType: 'json',
            success: function(data){
                try {
                    // エラーがある場合
                    if (data['error_message']) {
                        // 受注管理IDをクリアしてフォーカス
                        $('#order_control_id').val(null);
                        $('#order_control_id').focus();
                        // エラーを返す
                        throw new Error(data['error_message']);
                    }
                    // 受注管理IDをロックして背景をグレーに
                    $('#order_control_id').prop("disabled", true);
                    $('#order_control_id').addClass('bg-gray-200');
                    // 配送伝票番号にフォーカスして、メッセージをクリア
                    $('#tracking_no').focus();
                    $('#message').html(null);
                    audio_play('proc');
                } catch (e) {
                    audio_play('ng');
                    // エラーをメッセージに出力
                    $('#message').html(e.message);
                }
            },
            error: function(){
                alert('失敗');
            }
        });
    }
});

// 配送伝票番号が変更されたら
$('#tracking_no').on("change",function(){
    // 配送伝票番号に値がある場合のみ処理する
    if($('#tracking_no').val()){
        const ajax_url = '/shipping_inspection/ajax_check_tracking_no';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: ajax_url,
            type: 'POST',
            data: {
                order_control_id: $('#order_control_id').val(),
                tracking_no: $('#tracking_no').val(),
            },
            dataType: 'json',
            success: function(data){
                try {
                    // エラーがある場合
                    if (data['error_message']) {
                        // 配送伝票番号をクリアしてフォーカス
                        $('#tracking_no').val(null);
                        $('#tracking_no').focus();
                        // エラーを返す
                        throw new Error(data['error_message']);
                    }
                    // 配送伝票番号をロックして背景をグレーに
                    $('#tracking_no').prop("disabled", true);
                    $('#tracking_no').addClass('bg-gray-200');
                    // 商品識別コードにフォーカスして、メッセージをクリア
                    set_item_id_code();
                    $('#message').html(null);
                    // 検品対象一覧に検品情報をセット
                    inspection_target_table_set(data['inspection_targets']);
                    // 残PCSをセット
                    set_remaining_pcs();
                    audio_play('proc');
                } catch (e) {
                    audio_play('ng');
                    // エラーをメッセージに出力
                    $('#message').html(e.message);
                }
            },
            error: function(){
                alert('失敗');
            }
        });
    }
});

// 検品対象一覧に検品情報をセット
function inspection_target_table_set(inspection_targets){
    // レコード分だけループ処理
    for (let inspection_target of inspection_targets) {
        // 新しい行を作成
        let newRow = $("<tr id='" + inspection_target['order_item_id'] + "' class='text-left hover:bg-theme-sub cursor-default whitespace-nowrap'>");
        // 新しい行にセルを追加
        newRow.append($("<td class='py-1 px-2 border'>").text(inspection_target['item_jan_code']));
        newRow.append($("<td class='py-1 px-2 border'>").text(inspection_target['model_jan_code']));
        newRow.append($("<td class='py-1 px-2 border'>").text(inspection_target['item_name']));
        newRow.append($("<td class='order_quantity py-1 px-2 border text-right'>").text(inspection_target['order_quantity']));
        newRow.append($("<td class='inspection_quantity py-1 px-2 border text-right'>").text(0));
        // テーブルの末尾に新しい行を追加
        $('#inspection_target_table').append(newRow);
    }
}

// 商品識別コードが変更されたら
$('#item_id_code').on("change",function(){
    // 商品識別コードに値がある場合のみ処理する
    if($('#item_id_code').val()){
        const ajax_url = '/shipping_inspection/ajax_check_item_id_code';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: ajax_url,
            type: 'POST',
            data: {
                order_control_id: $('#order_control_id').val(),
                item_id_code: $('#item_id_code').val(),
            },
            dataType: 'json',
            success: function(data){
                try {
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
                    // JANコードで検品されているかつ、inspectionがfalse(検品数をカウントアップしていない)の場合
                    if(data['item_id_type'] === 'JAN' && !data['inspection']){
                        // LOT入力モーダルを表示
                        $('#lot_input_modal').removeClass('hidden');
                        $('#lot').val(null);
                        $('#lot_length').text('(' + (data['progress'][data['order_item_id']]['lot_1_length'] + data['progress'][data['order_item_id']]['lot_2_length']) + '桁)');
                        $('#lot').focus();
                        return;
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
    // 検品数がカウントアップされていたら
    if(data['inspection']){
        set_item_id_code();
        // メッセージをクリア
        $('#message').html(null);
        // 検品数を更新する
        $('#' + data['order_item_id'] + ' .inspection_quantity').text(data['inspection_quantity']);
        // 残PCSをセット
        set_remaining_pcs();
        // 検品が完了していたら、検品対象一覧から検品完了一覧に移動
        if(data['inspection_complete']){
            $('#' + data['order_item_id']).appendTo($('#inspection_complete_table'));
        }
        // 全ての検品が完了していたら、次の検品ができるように準備
        if(data['inspection_complete_order']){
            start_loading();
            // 商品識別コードをロックして背景をグレーに
            $('#item_id_code').prop("disabled", true);
            $('#item_id_code').addClass('bg-gray-200');
            // 完了音の再生が終了したら
            audio_play('complete', function () {
                $('#order_control_id').prop("disabled", false);
                $('#shipping_inspection_form').submit();
            });
        }
        audio_play('proc');
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

// LOTが変更されたら
$('#lot').on("change",function(){
    // LOTに値がある場合のみ処理する
    if($('#lot').val()){
        // モーダルを閉じる
        $('#lot_input_modal').addClass('hidden');
        var ajax_url = '/shipping_inspection/ajax_check_lot';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: ajax_url,
            type: 'POST',
            data: {
                order_control_id: $('#order_control_id').val(),
                lot: $('#lot').val(),
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

// 商品識別コードをクリアしてフォーカス
function set_item_id_code(){
    $('#item_id_code').val(null);
    $('#item_id_code').focus();
    return;
}

// 残PCSをセット
function set_remaining_pcs(){
    // 出荷数と検品数の合計を取得
    let total_order_quantity = 0;
    let total_inspection_quantity = 0;
    $('.order_quantity').each(function(){
        total_order_quantity += parseInt($(this).text());
    });
    $('.inspection_quantity').each(function(){
        total_inspection_quantity += parseInt($(this).text());
    });
    // 残PCSをセット
    $('#remaining_pcs').html(total_order_quantity - total_inspection_quantity);
    return;
}

// クリックイベント
$(document).on('click', function(e) {
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('item_id_code_alert_modal_close') == true){
        // モーダルを閉じる
        $('#item_id_code_alert_modal').addClass('hidden');
        set_item_id_code();
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if (e.target.classList.contains('lot_input_modal_close') == true) {
        // モーダルを閉じる
        $('#lot_input_modal').addClass('hidden');
        set_item_id_code();
        $('#message').html('LOT入力が中断されました。');
        audio_play('ng');
    }
});