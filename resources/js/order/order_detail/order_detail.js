import start_loading from '../../loading';

// 出荷検品実績削除ボタンを押下した場合
$('#shipping_inspection_actual_delete').on("click",function(){
    // 確認のためのインプットボックスを表示
    const input = prompt("出荷検品実績削除を実行しますか？\n続行するには「delete」と入力してください。");
    // インプットボックスに「delete」と入力された場合のみ処理を実行
    if (input === 'delete') {
        start_loading();
        $("#shipping_inspection_actual_delete_form").submit();
    } else {
        alert("出荷検品実績削除はキャンセルされました。");
    }
});

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('shipping_method_update_modal_close') === true){
        $('#shipping_method_update_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'shipping_method_update_modal_open'){
        // セレクトボックスの選択済みを現在の値に変更
        $('#shipping_method_id').val($('#current_shipping_method_id').val());
        $('#shipping_method_update_modal').removeClass('hidden');
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('shipping_base_update_modal_close') === true){
        $('#shipping_base_update_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'shipping_base_update_modal_open'){
        // セレクトボックスの選択済みを現在の値に変更
        $('#shipping_base_id').val($('#current_shipping_base_id').val());
        $('#shipping_base_update_modal').removeClass('hidden');
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('tracking_no_update_modal_close') === true){
        $('#tracking_no_update_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'tracking_no_update_modal_open'){
        // テキストボックスの値を現在の値に変更
        $('#tracking_no').val($('#current_tracking_no').val());
        $('#tracking_no_update_modal').removeClass('hidden');
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('order_memo_update_modal_close') === true){
        $('#order_memo_update_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'order_memo_update_modal_open'){
        // テキストボックスを現在の値に変更
        $('#order_memo').val($('#current_order_memo').val());
        $('#order_memo_update_modal').removeClass('hidden');
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('shipping_work_memo_update_modal_close') === true){
        $('#shipping_work_memo_update_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'shipping_work_memo_update_modal_open'){
        // テキストボックスを現在の値に変更
        $('#shipping_work_memo').val($('#current_shipping_work_memo').val());
        $('#shipping_work_memo_update_modal').removeClass('hidden');
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('desired_delivery_date_update_modal_close') === true){
        $('#desired_delivery_date_update_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'desired_delivery_date_update_modal_open'){
        // テキストボックスを現在の値に変更
        $('#desired_delivery_date').val($('#current_desired_delivery_date').val());
        $('#desired_delivery_date_update_modal').removeClass('hidden');
    }
});

// 出荷倉庫の更新ボタンを押下した場合
$('#shipping_base_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("出荷倉庫の更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#shipping_base_update_form").submit();
    }
});

// 配送方法の更新ボタンを押下した場合
$('#shipping_method_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("配送方法の更新を実行しますか？\n※同時に配送伝票番号がクリアされます。");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#shipping_method_update_form").submit();
    }
});

// 配送伝票番号の更新ボタンを押下した場合
$('#tracking_no_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("配送伝票番号の更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#tracking_no_update_form").submit();
    }
});

// 受注メモの更新ボタンを押下した場合
$('#order_memo_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("受注メモの更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#order_memo_update_form").submit();
    }
});

// 出荷作業メモの更新ボタンを押下した場合
$('#shipping_work_memo_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("出荷作業メモの更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#shipping_work_memo_update_form").submit();
    }
});

// 配送希望日の更新ボタンを押下した場合
$('#desired_delivery_date_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("配送希望日の更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#desired_delivery_date_update_form").submit();
    }
});

// 出荷倉庫更新モーダルのツールチップ
tippy('.tippy_shipping_base_update', {
    content: "出荷倉庫を更新する場合は、<br>こちらをクリックして下さい",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 配送方法更新モーダルのツールチップ
tippy('.tippy_shipping_method_update', {
    content: "配送方法を更新する場合は、<br>こちらをクリックして下さい",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 配送伝票番号更新モーダルのツールチップ
tippy('.tippy_tracking_no_update', {
    content: "配送伝票番号を更新する場合は、<br>こちらをクリックして下さい",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 受注メモ更新モーダルのツールチップ
tippy('.tippy_order_memo_update', {
    content: "受注メモを更新する場合は、<br>こちらをクリックして下さい",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 出荷作業メモ更新モーダルのツールチップ
tippy('.tippy_shipping_work_memo_update', {
    content: "出荷作業メモを更新する場合は、<br>こちらをクリックして下さい",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 配送希望日更新モーダルのツールチップ
tippy('.tippy_desired_delivery_date_update', {
    content: "配送希望日を更新する場合は、<br>こちらをクリックして下さい",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 配送伝票番号のツールチップ
tippy('.tippy_tracking_no_url', {
    content: "クリックすると運送会社の追跡ページが開きます。",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 受注メモのツールチップ
tippy('.tippy_order_memo', {
    content: "受注管理で使用するメモです。<br>このページ以外では表示されません。",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 出荷作業メモのツールチップ
tippy('.tippy_shipping_work_memo', {
    content: "出荷作業者に対して使用するメモです。<br>個別ピッキングリストに印字されます。",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 領収書宛名のツールチップ
tippy('.tippy_receipt_name', {
    content: "領収書宛名として印字される内容です。",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});