<x-app-layout>
    <x-item.item-qr-analysis.form :powerLists="$power_lists" />
    <x-item.item-qr-analysis.list :itemQrAnalysisHistories="$item_qr_analysis_histories" />
</x-app-layout>
@vite(['resources/js/item/item_qr_analysis/item_qr_analysis.js',])