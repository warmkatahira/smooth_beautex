<x-app-layout>
    <div class="mt-3 flex flex-row gap-3">
        @foreach($prefecture_by_base as $base)
            <div class="flex flex-col">
                <p class="text-white bg-black text-center p-2">{{ $base->base->base_name }}</p>
                <p class="bg-white text-center p-2 text-base">{{ $base->setting_count }}<span class="ml-1 text-xs">都道府県</span></p>
            </div>
        @endforeach
    </div>
    <x-setting.shipping-base-setting.list :prefectures="$prefectures" :bases="$bases" />
</x-app-layout>
@vite(['resources/js/setting/shipping_base/shipping_base.js'])