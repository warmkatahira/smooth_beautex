import{s as e}from"./loading-ChqFcCVw.js";$(".shipping_base_change").on("change",function(){try{window.confirm(`出荷倉庫を更新しますか？

都道府県：`+$(this).data("prefecture-name")+`
変更後出荷倉庫：`+$(this).find("option:selected").text())==!0&&(e(),$("#prefecture_id").val($(this).attr("id")),$("#shipping_base_id").val($(this).val()),$("#shipping_base_update_form").submit())}catch(t){alert(t.message)}});
