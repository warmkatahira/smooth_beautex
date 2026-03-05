import{s as t}from"./loading-ChqFcCVw.js";$(".select_file input[type=file]").on("change",function(){window.confirm(`以下のアップロードを実行しますか？

対象：`+$("#upload_target option:selected").text()+`
タイプ：`+$("#upload_type option:selected").text())===!0&&(t(),$("#item_upload_form").submit()),$(".select_file").val(null)});
