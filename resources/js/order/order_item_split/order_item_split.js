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
    // クリックしたボタンのorder_item_idを取得
    splitOrderItemId = $(this).data('order-item-id');
    // 分割プレビューを取得
    $.get('/order_item_split/split_preview', { order_item_id: splitOrderItemId }, function (res) {
        // 確定時に使用する数量の配列を作成
        splitQuantities = res.quantities.map(q => q.quantity);
        // プレビューテーブルを作成
        const rows = `
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-white bg-black">
                        <th class="py-1 px-2">件目</th>
                        <th class="py-1 px-2 text-right">数量</th>
                        <th class="py-1 px-2 text-right">小計</th>
                    </tr>
                </thead>
                <tbody>
                    ${res.quantities.map((q, i) => `
                        <tr class="border-b">
                            <td class="py-1 px-2">${i + 1}件目</td>
                            <td class="py-1 px-2 text-right">${q.quantity.toLocaleString()}個</td>
                            <td class="py-1 px-2 text-right">¥${q.subtotal.toLocaleString()}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        // プレビューテーブルをモーダルに挿入
        $('#split_preview_content').html(rows);
        // 確定ボタンにorder_item_idをセット
        $('#split_confirm').data('order-item-id', splitOrderItemId);
        // モーダルを表示
        $('#split_preview_modal').removeClass('hidden').addClass('flex');
    });
});

// 確定ボタンを押下した場合
$('#split_confirm').on("click", function () {
    // 処理を実行するか確認
    const result = window.confirm("商品分割を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if (result === true) {
        // ローディングを開始
        start_loading();
        // order_item_idをフォームにセット
        $('#split_order_item_id').val($(this).data('order-item-id'));
        // 既存のquantities hidden inputを削除（二重送信防止）
        $('#order_item_split_form').find('.split_quantity_input').remove();
        // 分割数量をhidden inputとしてフォームに追加
        splitQuantities.forEach(function (q) {
            $('#order_item_split_form').append(
                `<input type="hidden" name="quantities[]" value="${q}" class="split_quantity_input">`
            );
        });
        // フォームをsubmit
        $("#order_item_split_form").submit();
    }
});