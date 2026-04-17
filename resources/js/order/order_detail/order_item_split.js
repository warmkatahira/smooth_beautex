import start_loading from '../../loading';

let splitOrderItemId = null;
let splitQuantities = [];

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('split_preview_modal_close') === true){
        $('#split_preview_modal').addClass('hidden');
        $('#split_preview_modal').removeClass('flex');
    }
});

// 分割ボタン押下
$(document).on('click', '.split_preview_modal_open', function () {
    splitOrderItemId = $(this).data('order-item-id');
    $.get('/order_item_split/split_preview', { order_item_id: splitOrderItemId }, function (res) {
        splitQuantities = res.quantities.map(q => q.quantity); // 確定時は数量だけ渡す
        const rows = res.quantities.map((q, i) =>
            `<div class="flex justify-between py-1 border-b">
                <span>${i + 1}件目</span>
                <span>${q.quantity.toLocaleString()}個</span>
                <span>¥${q.subtotal.toLocaleString()}</span>
            </div>`
        ).join('');
        $('#split_preview_content').html(rows);
        // 確定ボタンにorder_item_idをセット
        $('#split_confirm').data('order-item-id', splitOrderItemId);
        $('#split_preview_modal').removeClass('hidden');
        $('#split_preview_modal').addClass('flex');
    });
});

// 確定ボタンを押下した場合
$('#split_confirm').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("商品分割を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $('#split_order_item_id').val($(this).data('order-item-id'));
        // 既存のquantities hidden inputを削除してから追加
        $('#order_item_split_form').find('.split_quantity_input').remove();
        splitQuantities.forEach(function (q) {
            $('#order_item_split_form').append(
                `<input type="hidden" name="quantities[]" value="${q}" class="split_quantity_input">`
            );
        });
        $("#order_item_split_form").submit();
    }
});