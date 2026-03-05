import start_loading from '../../loading';

// 更新ボタンが押下されたら
$('#base_shipping_method_update_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("倉庫別配送方法を更新しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#base_shipping_method_update_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 設定1のツールチップ
tippy('.tippy_setting_1', {
    content: "ヤマト運輸：「請求先顧客コード」を設定して下さい<br>" +
             "佐川急便　：「ご依頼主コード」を設定して下さい",
    duration: 500,
    allowHTML: true,
    placement: 'right',
    maxWidth: 'none',
    width: 500,
});

// 設定2のツールチップ
tippy('.tippy_setting_2', {
    content: "ヤマト運輸：「運賃管理番号」を設定して下さい<br>" +
             "佐川急便　：設定不要",
    duration: 500,
    allowHTML: true,
    placement: 'right',
    maxWidth: 'none',
    width: 500,
});

// 設定3のツールチップ
tippy('.tippy_setting_3', {
    content: "ヤマト運輸：「送り状種類」を設定して下さい<br>" +
             "佐川急便　：設定不要",
    duration: 500,
    allowHTML: true,
    placement: 'right',
    maxWidth: 'none',
    width: 500,
});