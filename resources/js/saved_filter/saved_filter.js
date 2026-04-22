import { submitSearch } from '../filter.js';

// 保存済みフィルターのページ識別子をdata属性から取得
const SAVED_FILTER_PAGE = $('#filter_table').data('filter-page');
// フィルター一覧の初回読み込みフラグ
let savedFiltersLoaded = false;

// フィルタードロップダウンにマウスオーバーされたら
$('#saved_filter_dropdown_wrap').on('mouseover', function () {
    // ドロップダウンを表示
    $('#saved_filter_dropdown').css('display', 'block');
    // 未読み込みの場合のみAJAXで取得
    if (!savedFiltersLoaded) {
        fetchSavedFilters();
        savedFiltersLoaded = true;
    }
});

// フィルター一覧を取得
function fetchSavedFilters() {
    $.ajax({
        url: '/saved_filter?filter_page=' + SAVED_FILTER_PAGE,
        type: 'GET',
        success: function (filters) {
            const $list = $('#saved_filter_list');
            // 一覧をクリア
            $list.empty();
            // テーブルを生成
            const $table = $(`
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="font-thin">フィルター名</th>
                            <th class="font-thin">操作</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" id="saved_filter_save_wrap">
                                <button type="button" id="saved_filter_save">
                                    <i class="las la-save la-lg mr-1"></i>現在のフィルターを保存
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            `);
            // 保存済みフィルターがない場合
            if (filters.length === 0) {
                $table.find('tbody').append('<tr><td colspan="2" style="padding: 8px 10px; font-size: 12px; color: #aaa;">保存済みフィルターはありません</td></tr>');
            } else {
                // フィルターの分だけ行を追加
                filters.forEach(function (filter) {
                    // filter_conditionsが文字列の場合はそのまま、オブジェクトの場合はJSON文字列に変換
                    const conditionsStr = typeof filter.filter_conditions === 'string'
                        ? filter.filter_conditions
                        : JSON.stringify(filter.filter_conditions);
                    $table.find('tbody').append(`
                        <tr>
                            <!-- フィルター名（クリックで適用） -->
                            <td class="saved_filter_apply cursor-pointer hover:bg-theme-sub" data-filter-conditions='${conditionsStr}' data-saved-filter-id="${filter.saved_filter_id}">
                                ${filter.filter_name}
                            </td>
                            <!-- 削除ボタン -->
                            <td>
                                <span class="btn rounded bg-btn-cancel text-white saved_filter_delete cursor-pointer px-2 py-1" data-saved-filter-id="${filter.saved_filter_id}">削除</span>
                            </td>
                        </tr>
                    `);
                });
            }
            // テーブルをリストに追加
            $list.append($table);
        }
    });
}

// フィルターを適用
$(document).on('click', '.saved_filter_apply', function () {
    let filter_conditions = $(this).data('filter-conditions');
    // 文字列の場合はオブジェクトに変換
    if (typeof filter_conditions === 'string') {
        filter_conditions = JSON.parse(filter_conditions);
    }
    // 既存のフィルターを全てクリア
    $('.filter-row th input, .filter-row th select').each(function () {
        $(this).val('');
    });
    // 保存済み条件をフォームにセット
    Object.entries(filter_conditions).forEach(([key, value]) => {
        $('[name="' + key + '"]').val(value);
    });
    // ドロップダウンを閉じる
    $('#saved_filter_dropdown').addClass('hidden');
    // 検索を実施
    submitSearch();
});

// フィルターを削除
$(document).on('click', '.saved_filter_delete', function (e) {
    // 親要素へのクリックイベント伝播を防止
    e.stopPropagation();
    // 削除対象のフィルターIDを取得
    const saved_filter_id = $(this).data('saved-filter-id');
    // 確認ダイアログを表示
    if (!confirm('このフィルターを削除しますか？')) return;
    $.ajax({
        url: '/saved_filter/delete',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            saved_filter_id: saved_filter_id,
        },
        success: function () {
            alert('削除しました。');
            // 次回ホバー時に再取得するためフラグをリセット
            savedFiltersLoaded = false;
            // 一覧を再取得
            fetchSavedFilters();
        }
    });
});

// 現在のフィルターを保存
$(document).on('click', '#saved_filter_save', function () {
    // フィルター名を入力
    const filter_name = prompt('フィルター名を入力してください（20文字以内）');
    // キャンセルまたは未入力の場合は処理を中断
    if (!filter_name) return;
    // 現在のフィルター条件を収集
    const filter_conditions = {};
    $('.filter-row th input, .filter-row th select').each(function () {
        const key = $(this).attr('name');
        const value = $(this).val();
        // 値がある場合のみ追加
        if (key && value !== '') {
            filter_conditions[key] = value;
        }
    });
    $.ajax({
        url: '/saved_filter/create',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            filter_page: SAVED_FILTER_PAGE,
            filter_name: filter_name,
            // オブジェクトをJSON文字列に変換して送信
            filter_conditions: JSON.stringify(filter_conditions),
        },
        success: function () {
            alert('保存しました。');
            // 次回ホバー時に再取得するためフラグをリセット
            savedFiltersLoaded = false;
            // 一覧を再取得
            fetchSavedFilters();
        },
        // バリデーションエラーの場合
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                const messages = Object.values(errors).flat().join('\n');
                alert(messages);
            } else {
                alert('エラーが発生しました。');
            }
        }
    });
});