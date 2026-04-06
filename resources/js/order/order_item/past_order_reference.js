import start_loading from '../../loading';

let selectedOrderControlId = null;

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('past_order_reference_modal_close') === true){
        $('#past_order_reference_modal').addClass('hidden');
        $('#past_order_reference_modal').removeClass('flex');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'past_order_reference_modal_open'){
        $('#past_order_reference_modal').removeClass('hidden');
        $('#past_order_reference_modal').addClass('flex');
        resetModal();
    }
});

// 検索
document.getElementById('past_order_search_btn')?.addEventListener('click', async () => {
    const order_no = document.getElementById('past_order_no_input').value;
    if(!order_no) return;

    const response = await fetch(`/past_order_item/search?order_no=${order_no}`);
    const data = await response.json();

    const tbody = document.getElementById('past_order_search_result_tbody');
    const result = document.getElementById('past_order_search_result');
    const empty = document.getElementById('past_order_search_empty');

    tbody.innerHTML = '';
    result.classList.add('hidden');
    empty.classList.add('hidden');

    if(data.length === 0){
        empty.classList.remove('hidden');
        return;
    }

    data.forEach(order => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="py-1 px-2 border">${order.order_no}</td>
            <td class="py-1 px-2 border">${order.shipping_date}</td>
            <td class="py-1 px-2 border text-right">${order.order_items_count}点</td>
            <td class="py-1 px-2 border text-center">
                <button type="button" class="btn bg-btn-enter text-white px-2 py-0.5 rounded-md select_past_order" data-order-control-id="${order.order_control_id}">選択</button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    result.classList.remove('hidden');

    // 選択ボタン
    document.querySelectorAll('.select_past_order').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const result = window.confirm("現在の商品情報を削除し、選択した注文の商品情報に書き換えます。よろしいですか？");
            if(result === true){
                start_loading();
                document.getElementById('past_order_control_id_input').value = e.target.dataset.orderControlId;
                document.getElementById('past_order_item_reference_form').submit();
            }
        });
    });
});

function resetModal(){
    document.getElementById('past_order_no_input').value = '';
    document.getElementById('past_order_search_result').classList.add('hidden');
    document.getElementById('past_order_search_empty').classList.add('hidden');
    document.getElementById('past_order_search_result_tbody').innerHTML = '';
    selectedOrderControlId = null;
}