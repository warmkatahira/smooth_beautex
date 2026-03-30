import start_loading from '../../loading';

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('order_item_create_modal_close') === true){
        $('#order_item_create_modal').addClass('hidden');
        $('#order_item_create_modal').removeClass('flex');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'order_item_create_modal_open'){
        $('#order_item_create_modal').removeClass('hidden');
        $('#order_item_create_modal').addClass('flex');
        resetModal();
        document.getElementById('item_search_input').focus();
    }
});

// 検索ボックスの表示をリセットする処理
function resetModal() {
    const searchInput = document.getElementById('item_search_input');
    const resultsArea = document.getElementById('item_search_results');
    const inputArea   = document.getElementById('item_input_area');
    searchInput.value = '';
    resultsArea.innerHTML = '<p class="p-2 text-gray-400">キーワードを入力してください</p>';
    inputArea.classList.add('hidden');
    document.getElementById('order_item_code').value = '';
    document.getElementById('order_item_unit_price').value = '';
    document.getElementById('shipping_quantity').value = '';
}

// HTMLの読み込みが完了したタイミングで実行
document.addEventListener('DOMContentLoaded', function () {
    const searchInput       = document.getElementById('item_search_input');
    const resultsArea       = document.getElementById('item_search_results');
    const inputArea         = document.getElementById('item_input_area');
    // ── 検索（デバウンス300ms）────────────────────
    let debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        if (q.length < 1) {
            resultsArea.innerHTML = '<p class="p-2 text-gray-400">キーワードを入力してください</p>';
            inputArea.classList.add('hidden');
            return;
        }
        debounceTimer = setTimeout(() => {
            resultsArea.innerHTML = '<p class="p-2 text-gray-400">検索中...</p>';
            fetch(`/order_item_create/search?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(items => renderResults(items));
        }, 300);
    });
    // ── 検索結果描画 ──────────────────────────────
    function renderResults(items) {
        if (items.length === 0) {
            resultsArea.innerHTML = '<p class="p-2 text-gray-400">該当する商品がありません</p>';
            return;
        }
        resultsArea.innerHTML = items.map(item => `
            <div class="flex items-center gap-2 p-2 border-b hover:bg-gray-100 cursor-pointer item_row"
                data-code="${item.item_code}"
                data-name="${item.item_name}">
                <span class="w-44 text-gray-500">${item.item_code}</span>
                <span class="w-28 text-gray-500">${item.item_jan_code ?? ''}</span>
                <span class="w-full">${item.item_name}</span>
            </div>
        `).join('');
        resultsArea.querySelectorAll('.item_row').forEach(row => {
            row.addEventListener('click', () => selectItem(row.dataset));
        });
    }
    // ── 商品選択 ──────────────────────────────────
    function selectItem(data) {
        document.getElementById('order_item_code').value = data.code;
        document.getElementById('selected_item_label').textContent = `[${data.code}] ${data.name}`;
        document.getElementById('shipping_quantity').value = 1;
        inputArea.classList.remove('hidden');
    }
});

// 更新ボタンを押下した場合
$('#order_item_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("商品追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#order_item_create_form").submit();
    }
});