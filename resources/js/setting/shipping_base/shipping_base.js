import start_loading from '../../loading';

// 出荷倉庫を更新したら
$('.shipping_base_change').on("change",function(){
    try {
        const result = window.confirm("出荷倉庫を更新しますか？\n\n都道府県："+$(this).data('prefecture-name')+"\n変更後出荷倉庫："+$(this).find('option:selected').text());
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            // 更新する情報を格納
            $('#prefecture_id').val($(this).attr('id'));
            $('#shipping_base_id').val($(this).val());
            $("#shipping_base_update_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});