<x-app-layout>
    <div class="flex flex-row gap-10 mt-3">
        <x-dashboard.info-div label="出荷作業中" :value="number_format($info['sagyo_chu_order_count'])" unit="件" />
        <x-dashboard.info-div label="当月出荷件数合計" :value="number_format($info['current_month_shipped_count'])" unit="件" />
        <x-dashboard.info-div label="当月出荷数量合計" :value="number_format($info['current_month_shipped_quantity'])" unit="PCS" />
    </div>
    <div class="flex flex-row gap-5 mt-5">
        <x-dashboard.shipping-calendar :dates="$dates" :shippingCount="$shipping_count" :shippingQuantity="$shipping_quantity" />
        <x-dashboard.shipping-history-chart />
    </div>
</x-app-layout>
@vite(['resources/js/dashboard/chart.js'])