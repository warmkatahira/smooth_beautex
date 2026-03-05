<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <x-shipping.nifuda-download.list :nifudaCreateHistories="$nifuda_create_histories" />
</x-app-layout>