import start_loading from '../../loading';

// 出荷完了を押下したら
$('#shipping_work_end_enter').on("click",function(){
    // 変数を初期化
    let total = 0;
    // 「not_shipping_work_end_count」のクラスを持つ要素をループ処理
    $('.not_shipping_work_end_count').each(function() {
        // テキストを取得してカンマを除去し、数値に変換
        let value = parseInt($(this).text().replace(/,/g, ''), 10);
        // 数値であれば加算
        if(!isNaN(value)){
            total += value;
        }
    });
    try {
        // 出荷完了対象外がある場合
        if(total > 0){
            const check = window.confirm("出荷完了対象外が" + total + "件あります。\n実行しますか？");
            // 「いいえ」が押下されたら処理キャンセル
            if(check == false){
                throw new Error('出荷完了を中断しました。');
            }
        }
        const result = window.confirm("出荷完了を実行しますか？");
        // 「はい」が押下されたらsubmit
        if(result == true) {
            start_loading();
            $("#shipping_work_end_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 出荷完了対象件数のツールチップ
tippy('.tippy_shipping_work_end_target_count', {
    content: "出荷作業中かつ出荷検品が完了している<br>受注件数が表示されます",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});

// 出荷完了対象外件数のツールチップ
tippy('.tippy_not_shipping_work_end_target_count', {
    content: "出荷作業中で出荷検品が未完了の<br>受注件数が表示されます",
    duration: 500,
    allowHTML: true,
    placement: 'right',
});