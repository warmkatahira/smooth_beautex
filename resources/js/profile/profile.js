import start_loading from '../loading';

let cropper;

// 画像選択時の処理
$('#select_image').on('change', function(event){
    const file = event.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            $('#preview').attr('src', e.target.result);
            if(cropper){
                cropper.destroy();
            }
            cropper = new Cropper(document.getElementById('preview'), {
                aspectRatio: 1, // 例: 正方形トリミング。必要に応じて変更。
                viewMode: 1,
                ready: function(){
                    // 初期トリミング範囲を指定
                    cropper.setCropBoxData({
                        left: 50,
                        top: 50,
                        width: 100,
                        height: 100,
                    });
                }
            });
        };
        reader.readAsDataURL(file);
    }
});

// メニューを押下したら
$('#profile_image_update_modal_open').on("click",function(){
    // モーダルを開く
    $('#profile_image_update_modal').removeClass('hidden');
});

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば
    if(e.target.classList.contains('profile_image_update_modal_close')){
        // モーダルを閉じる
        $('#profile_image_update_modal').addClass('hidden');
        // 要素をクリア
        $('#preview').attr('src', '');
        $('#select_image').val('');
        if(cropper){
            cropper.destroy();
            cropper = null;
        }
    }
});

// 更新が押下されたら
$('#profile_image_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        if(cropper){
            cropper.getCroppedCanvas().toBlob((blob) => {
                start_loading();
                const cropData = cropper.getData();
                $("#crop_data_x").val(cropData.x);
                $("#crop_data_y").val(cropData.y);
                $("#crop_data_width").val(cropData.width);
                $("#crop_data_height").val(cropData.height);
                $("#profile_image_update_form").submit();
            });
        }
        if(!cropper){
            alert('画像が選択されていません。');
        }
    }
});