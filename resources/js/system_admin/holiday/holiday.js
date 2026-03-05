import start_loading from '../../loading';

// 国民の祝日取得を押下したら
$('#national_holiday_get_api').on("click",function(){
    try {
        // 確認のためのインプットボックスを表示
        const year = prompt("国民の祝日を取得する西暦年を入力して下さい。\n※例：2025");
        // 入力がキャンセルされた場合は処理を中止
        if(year === null) return;
        // 正しい西暦年かチェック（2020〜2100の範囲）
        if(isNaN(year) || year < 2020 || year > 2100){
            throw new Error('正しい西暦年を入力してください。（例：2025）');
        }
        start_loading();
        $("#get_year").val(year);
        $("#national_holiday_get_api_form").submit();
    } catch (e) {
        alert(e.message);
    }
});

// 年のボタンが押下された場合
$('.year_btn').on("click",function(){
    // クリックしたボタンの次のdivをスライドトグル
    $(this).next('div').slideToggle();
    // アイコン切替（+ / -）があれば
    const icon = $(this).find('.accordion-icon');
    if(icon.length){
        icon.text(icon.text() === '+' ? '-' : '+');
    }
});