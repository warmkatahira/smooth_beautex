import start_loading from '../../loading';

// 条件番号を振り直し、削除ボタンの表示・非表示を制御
function updateConditionLabels() {
    // condition_divの数を取得
    const total = $('.condition_div').length;
    // condition_divの分だけループ処理
    $('.condition_div').each(function (index) {
        // 条件1、条件2... と番号を更新
        $(this).find('p.text-base').text('条件' + (index + 1));
        // 削除ボタンがなければ追加
        let $deleteBtn = $(this).find('.delete_condition_btn');
        if($deleteBtn.length === 0){
            $(this).find('div.flex.justify-between').append(
                '<button type="button" class="delete_condition_btn bg-red-500 text-white px-2 py-1 rounded text-xs">削除</button>'
            );
            $deleteBtn = $(this).find('.delete_condition_btn');
        }
        // 条件が1つだけなら削除ボタン非表示、それ以外は表示
        if(total === 1){
            $deleteBtn.hide();
        }else{
            $deleteBtn.show();
        }
    });
}

// 条件追加処理
$('#add_condition_btn').on('click', function () {
    // 最後にあるcondition_divを取得して複製
    const $last = $('.condition_div').last();
    const $clone = $last.clone();
    // 入力値/クラスを初期化して追加
    $clone.find('select, input').val('');
    $clone.find('.bg-gray-300').removeClass('bg-gray-300');
    $clone.find(':disabled').prop('disabled', false);
    $('#conditions_wrapper').append($clone);
    updateConditionLabels();
});

// 条件削除処理
$('#conditions_wrapper').on('click', '.delete_condition_btn', function () {
    $(this).closest('.condition_div').remove();
    updateConditionLabels();
});

// 初期化（リロード対策）
updateConditionLabels();

// 条件項目を変更した場合
$('#conditions_wrapper').on('change', '.column_name', function () {
    // 選択されたアクション区分を取得
    const selected = $(this).val();
    // このselectが属している条件ブロック内でのみ対象にする
    const conditionDiv = $(this).closest('.condition_div');
    const textWrapper = conditionDiv.find('.value_text_wrapper');
    const deliveryWrapper = conditionDiv.find('.value_delivery_company_wrapper');
    const textInput = textWrapper.find('input');
    const deliverySelect = deliveryWrapper.find('select');
    // 配送方法の場合
    if(selected === 'shipping_method_id'){
        // 表示/非表示を切り替え
        textWrapper.hide();
        deliveryWrapper.removeClass('hidden').show();
        // disabled属性を切り替え
        textInput.prop('disabled', true);
        deliverySelect.prop('disabled', false);
        // name属性を切り替え
        textInput.prop('name', 'value_dummy[]');
        deliverySelect.prop('name', 'value[]');
    // 配送方法を変更以外の場合
    }else{
        // 表示/非表示を切り替え
        textWrapper.show();
        deliveryWrapper.hide();
        // disabled属性を切り替え
        textInput.prop('disabled', false);
        deliverySelect.prop('disabled', true);
        // name属性を切り替え
        textInput.prop('name', 'value[]');
        deliverySelect.prop('name', 'value_dummy[]');
    }
});

// 初期化（リロード対策）
$('.column_name').trigger('change');

// 設定ボタンを押下した場合
$('#auto_process_condition_update_enter').on("click",function(){
    // AJAX通信のURLを定義
    const ajax_url = '/auto_process_condition_update/ajax_validation';
    // バリデーションで使用するデータを整理
    const data = collectValidationData();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: ajax_url,
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(data){
            // 処理を実行するか確認
            const result = window.confirm("自動処理条件の設定を実行しますか？");
            // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
            if(result === true){
                start_loading();
                $("#auto_process_condition_update_form").submit();
            }
        },
        error: function(xhr){
            if(xhr.status === 422){
                // バリデーションエラーを取得
                const errors = xhr.responseJSON.errors;
                // エラー情報を格納する変数を宣言
                let validation_errors = '';
                // ここで画面にエラーメッセージ表示など処理
                $.each(errors, function(index, value) {
                    // index が "column_name.0" のような形式であることを想定
                    // .数字の部分を正規表現で抽出
                    const match = index.match(/\.(\d+)$/);
                    // 数字に+1する（これが条件XのXの部分となる）
                    const number = parseInt(match[1], 10) + 1;
                    // 変数にエラー情報を格納
                    validation_errors += `条件${number}: ${value[0]}\n`;
                });
                alert(validation_errors);
            }else{
                alert('通信エラーが発生しました。');
            }
        }
    });
});

// バリデーションで使用するデータを整理
function collectValidationData()
{
    return {
        auto_process_id: $('#auto_process_id').val(),
        column_name: $("select[name='column_name[]']").map(function () {
            return $(this).val();
        }).get(),
        value: $("input[name='value[]'], select[name='value[]']").map(function () {
            return $(this).val();
        }).get(),
        operator: $("select[name='operator[]']").map(function () {
            return $(this).val();
        }).get(),
    };
}

// 比較方法を変更した場合
$('#conditions_wrapper').on('change', '.operator', function () {
    toggleInputs($(this));
});

// 比較方法によって条件値を操作
function toggleInputs(operatorSelect) {
    // 選択された比較方法を取得
    const selected = operatorSelect.val();
    // このselectが属している条件ブロック内でのみ対象にする
    const conditionDiv = operatorSelect.closest('.condition_div');
    const textWrapper = conditionDiv.find('.value_text_wrapper');
    const deliveryWrapper = conditionDiv.find('.value_delivery_company_wrapper');
    const textInput = textWrapper.find('input');
    const deliverySelect = deliveryWrapper.find('select');
    // 比較方法が「is null」か「is not null」の場合
    if(selected === 'is null' || selected === 'is not null'){
        // 現在の値を削除し、操作できなくする
        textInput.val(null);
        deliverySelect.val(null);
        textInput.val(null).prop('readonly', true).addClass('bg-gray-300');
        deliverySelect.val(null).css('pointer-events', 'none').addClass('bg-gray-300');
    // 上記以外の場合
    }else{
        // 操作できるようにする
        textInput.prop('readonly', false).removeClass('bg-gray-300');
        deliverySelect.css('pointer-events', '').removeClass('bg-gray-300');
    }
}

// ページロード時に全ての.operatorに適用
$('.operator').each(function () {
    toggleInputs($(this));
});