// selectタグ / datalistタグ / inputタグのtype="date"が変更された場合
$(document).on('change', '.filter-row th select, .filter-row th input[list], .filter-row th input[type="date"]', function () {
    // 検索を実施
    submitSearch();
});

// inputタグでキーが押された場合
$(document).on('keydown', '.filter-row th input', function (e) {
    // エンターが押された場合
    if(e.key === 'Enter'){
        // 検索を実施
        submitSearch();
    }
});

// 検索を実施
function submitSearch() {
    // フィルター条件をURLパラメータに組み立てる
    const params = new URLSearchParams();
    // 対象テーブルを取得
    const table = $('#filter_table');
    // URLをdata属性から取得
    const url = table.data('search-url');
    // 追加パラメータをdata属性から取得
    const extraParams = table.data('extra-params') || {};
    // スクロール対象を取得
    const scrollTarget = table.data('scroll-target');
    // 各要素をループ処理
    $('.filter-row th input, .filter-row th select, .filter-row th input[list]').each(function () {
        // 要素のname属性と値を取得
        const name = $(this).attr('name');
        const value = $(this).val();
        // name属性と値がある場合
        if(name && value !== ''){
            // パラメータを追加
            params.set(name, value);
        }
    });
    // 固定パラメータを設定
    params.set('process_type', 'filter');
    // 追加パラメータを設定
    Object.entries(extraParams).forEach(([key, value]) => {
        // valueが空の場合はDOMから取得
        params.set(key, value || $('#' + key).val());
    });
    // 横スクロール位置を保存
    params.set('scroll_x', $(scrollTarget).scrollLeft());
    // クエリストリングを生成してページ遷移
    const qs = params.toString();
    window.location.href = url + (qs ? '?' + qs : '');
}

// ページロード時
$(document).ready(function () {
    // 各要素をループ処理
    $('.filter-row th input, .filter-row th select, .filter-row th input[list]').each(function () {
        // 値がある場合(フィルター条件がある場合)
        if($(this).val() !== ''){
            // フィルタークリアボタンを表示して、背景色を設定
            $(this).siblings('.filter_clear').removeClass('hidden');
            $(this).addClass('bg-theme-sub');
        }
    });
    // URLパラメータからスクロール位置(X軸)を取得
    const scrollX = new URLSearchParams(window.location.search).get('scroll_x');
    // X軸が取得されている場合
    if(scrollX){
        // 取得した値でテーブルの横スクロール位置を復元
        const scrollTarget = $('#filter_table').data('scroll-target');
        $(scrollTarget).scrollLeft(parseInt(scrollX));
    }
});

// フィルタークリアが押された場合
$(document).on('click', '.filter_clear', function () {
    // 押された要素のdata--targetの値を取得
    const id = $(this).data('target');
    // 対象inputを空にする
    $('#' + id).val('');
    // 検索を実施
    submitSearch();
});