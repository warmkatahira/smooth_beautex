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