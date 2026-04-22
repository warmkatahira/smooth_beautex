// ドロップダウンボタンがマウスオーバーされたら
$('#dropdown').on("mouseover",function(){
    // 表示
    $('#dropdown-content').css('display', 'block');
});

// ドロップダウンボタンがマウスアウトされたら
$('#dropdown').on("mouseout",function(){
    // 非表示
    $('#dropdown-content').css('display', 'none');
});

// フィルタードロップダウンボタンがマウスオーバーされたら
$('#saved_filter_dropdown_wrap').on('mouseover', function () {
    // 表示
    $('#saved_filter_dropdown').css('display', 'block');
});

// フィルタードロップダウンボタンがマウスアウトされたら
$('#saved_filter_dropdown_wrap').on('mouseout', function () {
    // 非表示
    $('#saved_filter_dropdown').css('display', 'none');
});