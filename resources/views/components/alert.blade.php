<script>
    // Window読み込み時に実行
    window.onload = function(){
        // Sessionデータにメッセージが有るかどうかを確認
        if("{{ session('alert_type') }}"){
            // phpのuniqid関数でユニーク値をセット
            const messageIdValue = "{{ uniqid() }}";
            // 主要ブラウザはsessionStorageに対応しているが、念のため確認
            if(sessionStorage){
                // messageIdの値が同じだったら、フラッシュメッセージをdisplay:none;する
                if(sessionStorage.getItem('messageId') !== messageIdValue){
                    // messageIdがない場合は新しくセット。
                    // messageIdは有るが値が違う場合は上書き。
                    sessionStorage.setItem('messageId', messageIdValue);
                    // 改行を <br> に変換
                    const alertMessage = `{!! session('alert_message') !!}`.replace(/\n/g, '<br>');
                    // typeによって表示するアラートを可変
                    if("{{ session('alert_type') }}" == 'success'){
                        toastr.success(alertMessage);
                    }
                    if("{{ session('alert_type') }}" == 'info'){
                        toastr.info(alertMessage);
                    }
                    if("{{ session('alert_type') }}" == 'warning'){
                        toastr.warning(alertMessage);
                    }
                    if("{{ session('alert_type') }}" == 'error'){
                        toastr.error(alertMessage);
                    }
                }
            }
        }
    };
    // toastr.jsのオプション
    toastr.options = {
        "tapToDismiss": true,                       // クリックしたら閉じる
        "closeButton": false,                       // 閉じるボタンを表示
        "progressBar": true,                        // プログレスバーを表示
        "positionClass": "toast-top-full-width",    // 表示位置・サイズ
        "onclick": null,                            // クリック時の動作
        "showDuration": "300",                      // 表示開始アニメーションの速度
        "hideDuration": "1000",                     // 表示終了アニメーションの速度
        "timeOut": "4000",                          // 表示時間
        "extendedTimeOut": "4000",                  // ホバー後の閉じるまでの時間
        "showEasing": "swing",                      // 表示開始アニメーションの加速度のタイプ
        "hideEasing": "linear",                     // 表示終了アニメーションの加速度のタイプ
        "showMethod": "fadeIn",                     // 表示開始アニメーションのタイプ
        "hideMethod": "fadeOut"                     // 表示終了アニメーションのタイプ
    }
</script>