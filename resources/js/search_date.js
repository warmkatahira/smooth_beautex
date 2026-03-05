// 「date_from」要素を変更したら
$('.date_from').on("change",function(){
    // 同じ階層の「date_to」要素を取得
    const date_to = $(this).nextAll('input');
    // 自分の値よりも「date_to」要素の方が小さければ、「date_to」要素に自分の値を上書きする
    if($(this).val() > date_to.val()){
        date_to.val($(this).val());
    }
    // 「date_from」要素の値が削除されたら、「date_to」要素も削除する
    if($(this).val() == ''){
        date_to.val('');
    }
});

// 「date_to」要素を変更したら
$('.date_to').on("change",function(){
    // 同じ階層の「date_from」要素を取得
    const date_from = $(this).prevAll('input');
    // 自分の値よりも「date_from」要素の方が大きいか、「date_from」要素に値が無ければ、「date_from」要素に自分の値を上書きする
    if($(this).val() < date_from.val() || date_from.val() == ''){
        date_from.val($(this).val());
    }
    // 「date_to」要素の値が削除されたら、「date_from」要素も削除する
    if($(this).val() == ''){
        date_from.val('');
    }
});

// 「date_from_year」要素を変更したら
$('.date_from_year').on("change",function(){
    // 同じ階層の「date_to」要素を取得
    const date_to_year = $(this).nextAll('select');
    // 自分の値よりも「date_to_year」要素の方が小さければ、「date_to_year」要素に自分の値を上書きする
    if($(this).val() > date_to_year.val()){
        date_to_year.val($(this).val());
    }
    // 「date_from_year」要素の値が削除されたら、「date_to_year」要素も削除する
    if($(this).val() == ''){
        date_to_year.val('');
    }
});

// 「date_to_year」要素を変更したら
$('.date_to_year').on("change",function(){
    // 同じ階層の「date_from_year」要素を取得
    const date_from_year = $(this).prevAll('select');
    // 自分の値よりも「date_from_year」要素の方が大きいか、「date_from_year」要素に値が無ければ、「date_from_year」要素に自分の値を上書きする
    if($(this).val() < date_from_year.val() || date_from_year.val() == ''){
        date_from_year.val($(this).val());
    }
    // 「date_to_year」要素の値が削除されたら、「date_from_year」要素も削除する
    if($(this).val() == ''){
        date_from_year.val('');
    }
});