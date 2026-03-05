import Chart from "chart.js/auto";

// 画面読み込み時の処理
$(document).ready(function() {
    // グラフを作成
    createChart();
});

// グラフを作成
function createChart(){
    // AJAX通信のURLを定義
    const ajax_url = '/dashboard/ajax_get_chart_data';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: ajax_url,
        type: 'GET',
        data: {},
        dataType: 'json',
        success: function(data){
            // データを取得
            try {
                // ラベルを格納する配列を初期化
                let labels = [];
                // 日付の分だけループ処理
                $.each(data['dates'], function(date, value) {
                    // 日付を配列に格納
                    labels.push(value);
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
                const ctx = document.getElementById("shipping_history_chart").getContext("2d");
                // Chart.js を使って新しい折れ線グラフ(Line Chart)を作成する
                const chart = new Chart(ctx, {
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
                                    stepSize: 10
                                }
                            },
                            "y-axis-quantity": {
                                type: "linear",
                                position: "right",
                                ticks: {
                                    max: 500,
                                    min: 0,
                                    stepSize: 50
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
                            }
                        }
                    }
                });
            } catch (e) {
                alert('グラフの生成に失敗しました。');
            }
        },
        error: function(){
            alert('グラフの生成に失敗しました。');
        }
    });
};

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
        borderColor: 'rgb(75, 192, 192)',
        backgroundColor: 'rgba(75, 192, 192, 0.5)',
        pointBackgroundColor: 'rgb(75, 192, 192)',
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
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
        pointBackgroundColor: 'rgb(255, 99, 132)',
        pointRadius: 5,
        pointHoverRadius: 7,
        yAxisID: "y-axis-quantity"
    };
}