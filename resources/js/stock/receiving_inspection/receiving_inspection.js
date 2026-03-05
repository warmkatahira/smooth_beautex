import audio_play from '../../audio';
import start_loading from '../../loading';

// 画面読み込み時の処理
$(document).ready(function() {
    // スキャン前の準備
    item_scan_ready();
});

// スキャン前の準備
function item_scan_ready(){
    // フォーカスをセット
    $('#item_id_code').focus();
    // 入力欄をクリア
    $('#item_id_code').val(null);
}

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
                    // エラーがある場合
                    if(data['error_message']) {
                        // エラーを返す
                        throw new Error(data['error_message']);
                    }
                    // エラーがある場合
                    if(data['exp_check_result']) {
                        // エラーを返す
                        throw new Error(data['exp_check_result']);
                    }
                    // trueであれば新しくスキャンされた商品なので、行を追加
                    if(data['add']){
                        // 新しい行を作成
                        const newRow = $('<tr>').attr('id', data['item_id']).addClass('text-xs');
                        // buttonタグを作成
                        const updateButton = $('<button>')
                                                .attr('type', 'button')
                                                .addClass('btn item_change_modal_open bg-btn-enter text-white py-1 px-2')
                                                .attr('data-item-jan-code', data['item_jan_code'])
                                                .attr('data-item-id', data['item_id'])
                                                .text('変更');
                        // buttonタグを作成
                        const deleteButton = $('<button>')
                                                .attr('type', 'button')
                                                .addClass('btn item_delete_enter bg-btn-cancel text-white py-1 px-2')
                                                .attr('data-item-id', data['item_id'])
                                                .text('削除');
                        // divタグを作成
                        const buttonWrapper = $('<div>')
                                                .addClass('flex flex-row justify-between')
                                                .append(updateButton, deleteButton);
                        // 新しいセルを追加
                        const newCell1 = $('<td>')
                                            .addClass('py-1 px-2 border')
                                            .append(buttonWrapper);
                        const newCell2 = $('<td>').text(data['item_code']).addClass('py-1 px-2 border');
                        const newCell3 = $('<td>').text(data['item_jan_code']).addClass('py-1 px-2 border');
                        const newCell4 = $('<td>').text(data['item_name']).addClass('py-1 px-2 border');
                        const newCell5 = $('<td>').text(data['quantity']).addClass('inspection_quantity py-1 px-2 border text-right').attr('id', data['item_id'] + '_quantity');
                        // セルを行に追加
                        newRow.append(newCell1);
                        newRow.append(newCell2);
                        newRow.append(newCell3);
                        newRow.append(newCell4);
                        newRow.append(newCell5);
                        // 行をテーブルのtbodyに追加
                        $('#receiving_complete_table tbody').append(newRow);
                    }
                    // falseであれば既にスキャンされていた商品なので、数量を更新
                    if(!data['add']){
                        $('#' + data['item_id'] + '_quantity').text(data['quantity']);
                    }
                    // 検品数量合計を更新
                    update_inspection_quantity_total();
                    // 音を再生
                    audio_play('proc');
                    // スキャン前の準備
                    item_scan_ready();
                } catch (e) {
                    // 音を再生
                    audio_play('ng');
                    // モーダルを開く
                    $('#item_id_code_alert_modal').removeClass('hidden');
                    // エラーをメッセージに出力
                    $('#error_message').html(e.message);
                    // この要素にフォーカスをあてて、モーダル表示中にスキャンが進まないようにしている
                    $('#item_id_code_alert_modal_focus_element').focus();
                }
            },
            error: function(){
                alert('失敗');
            }
        });
    }
});

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
        item_scan_ready();
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

// 削除ボタンを押下した場合
$(document).on("click", ".item_delete_enter", function() {
    // 処理を実行するか確認
    const result = window.confirm("削除を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        // AJAX通信のURLをセット
        const ajax_url = '/receiving_inspection/ajax_delete_item_id';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: ajax_url,
            type: 'POST',
            data: {
                item_id: $(this).attr('data-item-id'),
            },
            dataType: 'json',
            success: function(data){
                // 指定したIDの要素を削除（trタグが削除される）
                $("#" + data['item_id']).remove();
                // 検品数量合計を更新
                update_inspection_quantity_total();
                // スキャン前の準備
                item_scan_ready();
            },
            error: function(){
                alert('失敗');
            }
        });
    }
});

// 変更ボタンを押下した場合
$(document).on("click", ".item_change_modal_open", function() {
    // 変更しようとしている商品IDをセット
    $('#item_change_enter').attr('data-item-id', $(this).attr('data-item-id'));
    // AJAX通信のURLをセット
    const ajax_url = '/receiving_inspection/ajax_get_item_id_change_target';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: ajax_url,
        type: 'POST',
        data: {
            item_jan_code: $(this).attr('data-item-jan-code'),
        },
        dataType: 'json',
        success: function(data){
            try {
                // 取得した商品情報が1以下の場合
                if(data['items'].length <= 1){
                    throw new Error('変更できる商品の候補がありません。');
                }
                // 現在の要素を削除
                $('#item_select_div').empty();
                // labelタグを作成
                const $label = $('<label>')
                    .attr('for', 'change_item_id')
                    .addClass('w-56 bg-black text-white py-2.5 pl-3 relative')
                    .text('変更先商品')
                    .append(
                        $('<span>')
                            .addClass('absolute right-2 top-1/2 -translate-y-1/2 bg-white text-red-600 text-xs px-1.5 py-0.5 rounded')
                            .text('必須')
                    );
                // selectタグを作成
                const $select = $('<select>')
                                    .attr('id', 'change_item_id')
                                    .attr('name', 'change_item_id')
                                    .addClass('text-xs w-96 border px-2 py-1');
                // optionタグをループで追加
                data['items'].forEach(item => {
                    const $option = $('<option>')
                        .val(item.item_id)
                        .text(item.item_name);
                    $select.append($option);
                });
                // 追加
                $('#item_select_div').append($label, $select);
                // モーダルを開く
                $('#item_change_modal').removeClass('hidden');
            } catch (e) {
                alert(e.message);
            }
        },
        error: function(){
            alert('失敗');
        }
    });
});

// 変更が押下されたら
$('#item_change_enter').on("click",function(){
    try {
        // 変更先の商品IDが既に存在する場合
        if($('#' + $('#change_item_id').val()).length){
            throw new Error('変更しようとしている商品が既に存在します。');
        }
        // 処理を実行するか確認
        const result = window.confirm("変更を実行しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result === true){
            // AJAX通信のURLをセット
            const ajax_url = '/receiving_inspection/ajax_change_item_id';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: ajax_url,
                type: 'POST',
                data: {
                    item_id: $(this).attr('data-item-id'),   // 変更元
                    change_item_id: $('#change_item_id').val(), // 変更先
                },
                dataType: 'json',
                success: function(data){
                    // 対象の <tr> を取得
                    const $row = $('#' + data['item_id']);
                    // 各 <td> を取得（0-index）
                    $row.find('td').eq(1).text(data['item']['item_code']);                      // item_code
                    $row.find('td').eq(2).text(data['item']['item_jan_code']);                  // item_jan_code
                    $row.find('td').eq(3).text(data['item']['item_name']);                      // item_name
                    $row.find('td').eq(4).attr('id', data['item']['item_id'] + '_quantity');    // quantity
                    // <tr> 内のボタンすべて（data-item-idを持つ）を対象に更新
                    $row.find('button[data-item-id]').each(function () {
                        $(this).attr('data-item-id', data['item']['item_id']);
                    });
                    // <tr> の id を新しい item_id に変更
                    $row.attr('id', data['item']['item_id']);
                    // モーダルを閉じる
                    $('#item_change_modal').addClass('hidden');
                    // スキャン前の準備
                    item_scan_ready();
                },
                error: function(){
                    alert('失敗');
                }
            });
        };
    } catch (e) {
        alert(e.message);
    }
});

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('item_change_modal_close') === true){
        $('#item_change_modal').addClass('hidden');
    }
});