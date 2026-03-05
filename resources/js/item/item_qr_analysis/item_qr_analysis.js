import start_loading from '../../loading';

// 解析ボタンを押下した場合
$('#item_qr_analysis_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("解析を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#item_qr_analysis_form").submit();
    }
});