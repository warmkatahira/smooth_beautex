import{s as a}from"./loading-CLXJ3Lsj.js";let s=null,l=[];$(document).on("click",function(t){t.target.classList.contains("split_preview_modal_close")===!0&&($("#split_preview_modal").addClass("hidden"),$("#split_preview_modal").removeClass("flex"))});$(document).on("click",".split_preview_modal_open",function(){s=$(this).data("order-item-id"),$.get("/order_item_split/split_preview",{order_item_id:s},function(t){l=t.quantities.map(i=>i.quantity);const e=`
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-white bg-black">
                        <th class="py-1 px-2">件目</th>
                        <th class="py-1 px-2 text-right">数量</th>
                        <th class="py-1 px-2 text-right">小計</th>
                    </tr>
                </thead>
                <tbody>
                    ${t.quantities.map((i,r)=>`
                        <tr class="border-b">
                            <td class="py-1 px-2">${r+1}件目</td>
                            <td class="py-1 px-2 text-right">${i.quantity.toLocaleString()}個</td>
                            <td class="py-1 px-2 text-right">¥${i.subtotal.toLocaleString()}</td>
                        </tr>
                    `).join("")}
                </tbody>
            </table>
        `;$("#split_preview_content").html(e),$("#split_confirm").data("order-item-id",s),$("#split_preview_modal").removeClass("hidden").addClass("flex")})});$("#split_confirm").on("click",function(){window.confirm("商品分割を実行しますか？")===!0&&(a(),$("#split_order_item_id").val($(this).data("order-item-id")),$("#order_item_split_form").find(".split_quantity_input").remove(),l.forEach(function(e){$("#order_item_split_form").append(`<input type="hidden" name="quantities[]" value="${e}" class="split_quantity_input">`)}),$("#order_item_split_form").submit())});
