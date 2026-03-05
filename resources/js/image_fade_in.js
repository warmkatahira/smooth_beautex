// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('image_fade_in_modal_close') === true){
        $('#image_fade_in_modal').addClass('hidden');
    }
    // クリックした要素のクラスがモーダルを開くものであれば、モーダルを開く
    if(e.target.classList.contains('image_fade_in_modal_open')){
        // 画像を取得
        const src = e.target.getAttribute('src');
        // imgタグを作成
        const $img = $('<img>')
                .attr('src', src)
                .addClass('mx-auto');
        // 追加
        $('#image_fade_in_div').empty().append($img);
        $('#image_fade_in_modal').removeClass('hidden');
    }
});