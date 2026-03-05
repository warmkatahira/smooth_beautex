import start_loading from '../../loading';

// 表示切替ボタンを押下した場合
$('.display_switch').on("click",function(){
    start_loading();
});

// 商品単位表示のツールチップ
tippy('.tippy_display_by_item', {
    content: "商品単位表示",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 在庫単位表示のツールチップ
tippy('.tippy_display_by_stock', {
    content: "在庫単位表示",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// アップロードでファイルが選択されたら
$('.select_file input[type=file]').on("change",function(){
    // 処理を実行するか確認
    const result = window.confirm("商品ロケーション更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true){
        start_loading();
        $("#item_location_update_form").submit();
    }
    // 要素をクリア
    $('.select_file').val(null);
});