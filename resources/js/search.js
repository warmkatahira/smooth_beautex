import start_loading from './loading';

// 検索ボタンを押下した場合
$('#search_enter').on("click",function(){
    start_loading();
    // 検索タイプを設定
    $('#search_type').val('search');
    // フォームを送信
    $("#search_form").submit();
});

// クリアボタンを押下した場合
$('#search_clear').on("click",function(){
    start_loading();
    // 「disabled」を設定（送信されないようにしている）
    $('#search_type').prop('disabled', true);
    // 検索条件の値をnullに変更
    $('.search_element').each(function(){
        $(this).val(null);
    });
    // フォームを送信
    $("#search_form").submit();
});