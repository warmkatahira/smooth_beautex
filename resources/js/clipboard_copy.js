// クリップボードコピーが押下されたら
$('.clipboard_copy').click(function () {
    // 値を取得
    const clipboard_copy_value = $(this).data('clipboard-value');
    // クリップボードにコピー
    navigator.clipboard.writeText(clipboard_copy_value);
    // メッセージ要素を作成
    const msg_element = $('<div class="clipboard_copy_success_msg"><i class="las la-check-circle la-lg mr-2"></i>コピーしました</div>');
    // body に追加
    $('body').append(msg_element);
    // フェードイン → 一定時間後フェードアウトして削除
    msg_element.fadeIn("slow", function () {
        $(this).delay(1000).fadeOut("slow", function () {
            $(this).remove(); // DOMから削除
        });
    });
});

// クリップボードコピーのボタンがホバーされた際のツールチップ
document.addEventListener('DOMContentLoaded', function() {
    tippy('.tippy_clipboard_copy', {
        content(reference) {
            const label = reference.getAttribute('data-clipboard-label') || '';
            return `${label}をコピー`;
        },
        duration: 500,
        maxWidth: 'none',
        allowHTML: true,
        placement: 'right',
        theme: 'tippy_main_theme',
    });
});