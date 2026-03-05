import Chart from "chart.js/auto";
import colorMap from '../color';

// グラフの存在確認用変数
let chart = null;

// 画面読み込み時の処理
$(document).ready(function() {
    // 現在の年月をyyyy-mm形式で取得
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // 月は0始まりなので +1
    const currentMonth = `${year}-${month}`;
    // オブジェクトを作成
    create_object(currentMonth);
});

// オブジェクトを作成
function create_object(month){
    // AJAX通信のURLを定義
    const ajax_url = '/dashboard/ajax_get_chart_data';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: ajax_url,
        type: 'GET',
        data: {
            month: month,
        },
        dataType: 'json',
        success: function(data){
            try {
                // グラフを作成
                create_chart(data);
                // カレンダーを作成
                create_calendar(data);
                // -でスプリット
                const month_split = month.split('-');
                // 年月を取得
                const year_month = month_split[0]+'年'+month_split[1]+'月';
                // 年月を書き換える
                $('#calendar_year').html(year_month);
                // ラベルを書き換える
                $('#total_shipped_label').html(year_month + ' 出荷実績');
                // 値を書き換える
                $('#sagyo_mae_order_count').html(Number(data['info']['sagyo_mae_order_count']).toLocaleString());
                $('#sagyo_mae_ship_quantity').html(Number(data['info']['sagyo_mae_ship_quantity']).toLocaleString());
                $('#sagyo_chu_order_count').html(Number(data['info']['sagyo_chu_order_count']).toLocaleString());
                $('#sagyo_chu_ship_quantity').html(Number(data['info']['sagyo_chu_ship_quantity']).toLocaleString());
                $('#total_shipped_count').html(Number(data['info']['month_shipped_count']).toLocaleString());
                $('#total_shipped_quantity').html(Number(data['info']['month_shipped_quantity']).toLocaleString());
            } catch (e) {
                alert('オブジェクトの生成に失敗しました。');
            }
        },
        error: function(){
            alert('オブジェクトの生成に失敗しました。');
        }
    });
};

// グラフを作成
function create_chart(data){
    // すでにグラフが存在する場合
    if(chart){
        // 削除
        chart.destroy();
    }
    // ラベルを格納する配列を初期化
    let labels = [];
    // 日付の分だけループ処理
    $.each(data['dates'], function(date, value) {
        // 日付を配列に格納
        labels.push(value['date']);
    });
    // 表示する情報や設定を配列に格納
    const chart_data = {
        labels: labels,
        datasets: [
            getShippingCountDataset(data['shipping_count'], data['dates']),
            getShippingQuantityDataset(data['shipping_quantity'], data['dates'])
        ]
    };
    // HTML内にある <canvas id="shipping_count_chart"> 要素を取得し、その2D描画コンテキストを取得する
    // Chart.js はこのコンテキストを使ってグラフを描画する
    const canvas = document.getElementById("shipping_history_chart");
    const ctx = canvas.getContext("2d");
    // 再描画前にサイズを親要素に合わせる
    canvas.width = canvas.parentElement.clientWidth;
    canvas.height = 600;
    // Chart.js を使って新しい折れ線グラフ(Line Chart)を作成する
    chart = new Chart(ctx, {
        // グラフに表示するデータ
        data: chart_data,
        // オプション設定
        options: {
            responsive: false,
            scales: {
                "y-axis-count": {
                    type: "linear",
                    position: "left",
                    ticks: {
                        max: 200,
                        min: 0,
                        stepSize: 10,
                        precision: 0,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    }
                },
                "y-axis-quantity": {
                    type: "linear",
                    position: "right",
                    ticks: {
                        max: 500,
                        min: 0,
                        stepSize: 50,
                        precision: 0,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    grid: {
                        drawOnChartArea: false // 左右のグリッドが重ならないように
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        // 凡例がpointStyleに従う
                        usePointStyle: true
                    }
                },
                tooltip: {
                    bodyFont: {
                        size: 16,       // ツールチップ本文のフォントサイズ
                    },
                    titleFont: {
                        size: 18,       // ツールチップタイトルのフォントサイズ
                    },
                    padding: 12,        // 内側の余白
                    boxPadding: 6,      // カラーボックスの余白
                },
            }
        }
    });
}

// 出荷件数のデータを取得
function getShippingCountDataset(shipping_count, dates)
{
    // 出荷件数を格納する配列を初期化
    let shipping_count_arr = [];
    // 日付の分だけループ処理
    $.each(dates, function(date, value) {
        // 出荷件数があれば出荷件数を、なければ0を格納
        let count = shipping_count.hasOwnProperty(date) ? shipping_count[date]['count'] : 0;
        // 出荷件数を配列に格納
        shipping_count_arr.push(count);
    });
    return {
        type: 'line',
        label: '出荷件数',
        data: shipping_count_arr,
        borderColor: colorMap[0].borderColor,
        backgroundColor: colorMap[0].backgroundColor,
        pointBackgroundColor: colorMap[0].borderColor,
        pointRadius: 5,
        pointHoverRadius: 7,
        yAxisID: "y-axis-count"
    };
}

// 出荷数量のデータを取得
function getShippingQuantityDataset(shipping_quantity, dates)
{
    // 出荷数量を格納する配列を初期化
    let shipping_quantity_arr = [];
    // 日付の分だけループ処理
    $.each(dates, function(date, value) {
        // 出荷数量があれば出荷数量を、なければ0を格納
        let quantity = shipping_quantity.hasOwnProperty(date) ? shipping_quantity[date]['total_quantity'] : 0;
        // 出荷数量を配列に格納
        shipping_quantity_arr.push(quantity);
    });
    return {
        type: 'bar',
        label: '出荷数量',
        data: shipping_quantity_arr,
        borderColor: colorMap[1].borderColor,
        backgroundColor: colorMap[1].backgroundColor,
        pointBackgroundColor: colorMap[1].borderColor,
        pointRadius: 5,
        pointHoverRadius: 7,
        yAxisID: "y-axis-quantity"
    };
}

// 月の変更ボタンが押下された場合
$(document).on("click", ".month_change", function(){
    // 変更する月を取得
    const month = $(this).attr('data-month');
    // 指定した月の前後の月を取得
    const result = getAdjacentMonths(month);
    // ボタンの値を書き換える
    $('#previous_month').attr('data-month', result['previous']);
    $('#next_month').attr('data-month', result['next']);
    // オブジェクトを作成
    create_object(month);
})

// カレンダーを作成
function create_calendar(data){
    const $table = $("#shipping_calendar");
    // 月初の日付を取得
    const dateKeys = Object.keys(data['dates']);
    const firstDay = new Date(dateKeys[0]);
    const firstDayOfWeek = firstDay.getDay(); // 0:日曜〜6:土曜
    let dayCounter = 0;
    // 曜日ヘッダーを作成
    const days = ["日", "月", "火", "水", "木", "金", "土"];
    let thead = "<thead><tr>";
    days.forEach((day, i) => {
        let bg = (i === 0) ? "bg-pink-100" : (i === 6) ? "bg-blue-100" : "";
        thead += `<th class="font-thin border border-black w-1/7 ${bg}">${day}</th>`;
    });
    thead += "</tr></thead>";
    // tbody 開始
    let tbody = "<tbody>";
    for (let week = 0; week < 6; week++) {
        tbody += `<tr class="h-24">`;
        for (let dow = 0; dow < 7; dow++) {
            let disp_date = "";
            let count = null;
            let quantity = null;
            let bg = "";
            let holiday_name = "";
            let disp_date_color = 'text-black';
            // 1週目かつ開始曜日より前なら空白（でも曜日色はつける）
            if (week === 0 && dow < firstDayOfWeek) {
                // 曜日背景設定（日曜:ピンク, 土曜:青）
                let emptyBg = "";
                if (dow === 0) emptyBg = "bg-pink-100";
                if (dow === 6) emptyBg = "bg-blue-100";
                tbody += `<td class="border border-black ${emptyBg}"></td>`;
                continue;
            }
            const currentKey = dateKeys[dayCounter];
            if (currentKey) {
                const date = new Date(currentKey);
                const ymd = date.toISOString().split("T")[0];
                const day = date.getDate();
                // 表示日付
                disp_date = day;
                // 件数・数量を取得
                count = Number(data['shipping_count'][ymd]?.count ?? 0).toLocaleString();
                quantity = Number(data['shipping_quantity'][ymd]?.total_quantity ?? 0).toLocaleString();
                dayCounter++;
                // 祝日の場合
                if (data['dates'][currentKey]['holiday'] !== null) {
                    bg = "bg-pink-100";
                    holiday_name = data['dates'][currentKey]['holiday'];
                    disp_date_color = 'text-red-500';
                }
            }
            // 土日用のクラス
            if (dow === 0) {
                bg = "bg-pink-100";
                disp_date_color = 'text-red-500';
            }
            if (dow === 6) bg = "bg-blue-100";
            // 中身
            if (disp_date) {
                let href = "";
                let clickable = "";
                if (count && count != 0) {
                    href = `/shipping_history?search_type=search&search_shipping_date_from=${currentKey}&search_shipping_date_to=${currentKey}`;
                    clickable = "hover:bg-theme-sub cursor-pointer";
                }
                tbody += `
                    <td class="border border-black align-top w-1/7 ${clickable} ${bg}">
                        <a ${href ? `href="${href}"` : ""} class="${count && count != 0 ? 'tippy_shipping_history' : ''}" data-ymd="${currentKey}" data-holiday-name="${holiday_name}" data-count="${count}" data-quantity="${quantity}">
                            <div class="flex flex-col gap-2 p-2 text-xs">
                                <div class="flex flex-row items-center gap-2 w-full relative">
                                    <strong class="text-base text-left ${disp_date_color}">${disp_date}</strong>
                                    ${holiday_name ? `
                                    <div class="ml-2 overflow-hidden flex-1 relative h-5">
                                        <div class="absolute whitespace-nowrap animate-marquee">
                                            ${holiday_name}
                                        </div>
                                    </div>` : ''}
                                </div>
                                ${count && count != 0 ? `
                                <div class="flex flex-row text-xs border-dashed border-b border-black">
                                    <p class="w-1/2">件数</p>
                                    <p class="w-1/2 text-right truncate overflow-hidden whitespace-nowrap">${count}</p>
                                </div>
                                <div class="flex flex-row text-xs border-dashed border-b border-black">
                                    <p class="w-1/2">数量</p>
                                    <p class="w-1/2 text-right truncate overflow-hidden whitespace-nowrap">${quantity}</p>
                                </div>` : ""}
                            </div>
                        </a>
                    </td>
                `;
            } else {
                tbody += `<td class="border border-black ${bg}"></td>`;
            }
        }
        tbody += `</tr>`;
    }
    tbody += "</tbody>";
    // 表全体を結合
    $table.html(thead + tbody);
    tippy('.tippy_shipping_history', {
        content(reference) {
            const ymd = reference.getAttribute('data-ymd');
            const holiday_name = reference.getAttribute('data-holiday-name');
            const count = reference.getAttribute('data-count');
            const quantity = reference.getAttribute('data-quantity');
            const [yyyy, mm, dd] = ymd.split("-");
            // 祝日名がある場合のみ括弧で表示
            const holiday_text = holiday_name ? `(${holiday_name})` : '';
            return `出荷履歴へ移動<br><br>日付：${yyyy}年${mm}月${dd}日${holiday_text}<br>件数：${count}<br>数量：${quantity}`;
        },
        duration: 500,
        maxWidth: 'none',
        allowHTML: true,
        placement: 'right',
        theme: 'tippy_main_theme',
    });
}

// 指定した月の前後の月を取得
function getAdjacentMonths(ym) {
    // "YYYY-MM" を分割
    const [year, month] = ym.split("-").map(Number);
    // 現在の月をベースにDateオブジェクトを作成
    const current = new Date(year, month - 1);
    // 前月と翌月を取得
    const previous = new Date(current.getFullYear(), current.getMonth() - 1);
    const next = new Date(current.getFullYear(), current.getMonth() + 1);
    // "YYYY-MM" 形式に整形して返す
    const format = (d) =>
        `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}`;
    return {
        previous: format(previous),
        next: format(next),
    };
}

// 現在の月へ移動ボタンのツールチップ
tippy('.tippy_current_month', {
    content: '今月へ',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// 前月移動ボタンのツールチップ
tippy('.tippy_previous_month', {
    content: '前月へ',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// 翌月移動ボタンのツールチップ
tippy('.tippy_next_month', {
    content: '翌月へ',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});