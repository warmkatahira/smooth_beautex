<div class="flex flex-row border-b border-gray-300 text-xs">
    <div class="flex flex-row w-5/12 bg-black text-white py-1">
        <div class="flex flex-row">
            <p class="pl-3">配送伝票番号</p>
        </div>
        @if(auth()->user()->can('warm_check') && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI)
            <i id="tracking_no_update_modal_open" class="tippy_tracking_no_update las la-edit ml-auto pr-2 la-lg cursor-pointer"></i>
        @endif
    </div>
    @if(is_null($order->tracking_no))
        <p class="w-7/12 py-1 bg-theme-sub"></p>
    @endif
    @if(!is_null($order->tracking_no))
        @foreach(TrackingNoUrlMakeFunc::make($order) as $key => $value)
            <a href="{{ $value }}" class="w-7/12 py-1 pl-3 bg-theme-sub underline tippy_tracking_no_url" target="_blank" rel="noopener noreferrer">{{ $key }}</a>
        @endforeach
    @endif
</div>