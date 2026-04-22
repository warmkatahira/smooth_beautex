import{s as d}from"./filter-si_zA3_j.js";const o=$("#filter_table").data("filter-page");let i=!1;$("#saved_filter_dropdown_wrap").on("mouseover",function(){$("#saved_filter_dropdown").css("display","block"),i||(r(),i=!0)});function r(){$.ajax({url:"/saved_filter?filter_page="+o,type:"GET",success:function(t){const s=$("#saved_filter_list");s.empty();const e=$(`
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
            `);t.length===0?e.find("tbody").append('<tr><td colspan="2" style="padding: 8px 10px; font-size: 12px; color: #aaa;">保存済みフィルターはありません</td></tr>'):t.forEach(function(a){const n=typeof a.filter_conditions=="string"?a.filter_conditions:JSON.stringify(a.filter_conditions);e.find("tbody").append(`
                        <tr>
                            <!-- フィルター名（クリックで適用） -->
                            <td class="saved_filter_apply cursor-pointer hover:bg-theme-sub" data-filter-conditions='${n}' data-saved-filter-id="${a.saved_filter_id}">
                                ${a.filter_name}
                            </td>
                            <!-- 削除ボタン -->
                            <td>
                                <span class="btn rounded bg-btn-cancel text-white saved_filter_delete cursor-pointer px-2 py-1" data-saved-filter-id="${a.saved_filter_id}">削除</span>
                            </td>
                        </tr>
                    `)}),s.append(e)}})}$(document).on("click",".saved_filter_apply",function(){let t=$(this).data("filter-conditions");typeof t=="string"&&(t=JSON.parse(t)),$(".filter-row th input, .filter-row th select").each(function(){$(this).val("")}),Object.entries(t).forEach(([s,e])=>{$('[name="'+s+'"]').val(e)}),$("#saved_filter_dropdown").addClass("hidden"),d()});$(document).on("click",".saved_filter_delete",function(t){t.stopPropagation();const s=$(this).data("saved-filter-id");confirm("このフィルターを削除しますか？")&&$.ajax({url:"/saved_filter/delete",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{saved_filter_id:s},success:function(){alert("削除しました。"),i=!1,r()}})});$(document).on("click","#saved_filter_save",function(){const t=prompt("フィルター名を入力してください（20文字以内）");if(!t)return;const s={};$(".filter-row th input, .filter-row th select").each(function(){const e=$(this).attr("name"),a=$(this).val();e&&a!==""&&(s[e]=a)}),$.ajax({url:"/saved_filter/create",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{filter_page:o,filter_name:t,filter_conditions:JSON.stringify(s)},success:function(){alert("保存しました。"),i=!1,r()},error:function(e){if(e.status===422){const a=e.responseJSON.errors,n=Object.values(a).flat().join(`
`);alert(n)}else alert("エラーが発生しました。")}})});
