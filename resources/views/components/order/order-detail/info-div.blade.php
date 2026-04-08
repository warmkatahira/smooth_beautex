@props([
    'label',
    'value',
    'order' => null,
    'openModalId' => null,
    'infoTippy' => null,
    'modalTippy' => null,
])

@php
    // 変数を初期化
    $is_modal_icon_disp = false;
    // 出荷倉庫
    if($openModalId === 'shipping_base_update_modal_open'
        && $order->order_status_id < OrderStatusEnum::SAGYO_CHU
        && Auth::user()->can('warm_check')){
            $is_modal_icon_disp = true;
    }
    // 配送方法
    if($openModalId === 'shipping_method_update_modal_open'
        && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI
        && Auth::user()->can('warm_check')){
            $is_modal_icon_disp = true;
    }
    // 配送希望日
    if($openModalId === 'desired_delivery_date_update_modal_open'
        && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI
        && Auth::user()->can('warm_check')){
            $is_modal_icon_disp = true;
    }
    // 受注マーク
    if($openModalId === 'order_mark_update_modal_open' && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI){
        $is_modal_icon_disp = true;
    }
    // 受注メモ
    if($openModalId === 'order_memo_update_modal_open' && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI){
        $is_modal_icon_disp = true;
    }
    // 出荷作業メモ
    if($openModalId === 'shipping_work_memo_update_modal_open' && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI){
        $is_modal_icon_disp = true;
    }
    // 補足事項
    if($openModalId === 'supplement_update_modal_open' && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI){
        $is_modal_icon_disp = true;
    }
    // 配送先住所
    if($openModalId === 'ship_address_update_modal_open'
        && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI
        && Auth::user()->can('warm_check')){
            $is_modal_icon_disp = true;
    }
    // 在庫引当処理
    if($openModalId === 'is_stock_allocation_skipped_update_modal_open' && $order->order_status_id < OrderStatusEnum::SAGYO_CHU){
        $is_modal_icon_disp = true;
    }
    // 出荷検品処理
    if($openModalId === 'is_shipping_inspection_skipped_update_modal_open' && $order->order_status_id < OrderStatusEnum::SAGYO_CHU){
        $is_modal_icon_disp = true;
    }
@endphp

<div class="flex flex-row border-b border-gray-300 text-xs">
    <div class="flex flex-row w-5/12 bg-black text-white py-1">
        <div class="flex flex-row">
            <p class="pl-3">{{ $label }}</p>
            @if(!is_null($infoTippy))
                <i class="{{ $infoTippy }} las la-info-circle la-lg ml-1 pt-0.5"></i>
            @endif
        </div>
        @if(!is_null($openModalId) && $is_modal_icon_disp)
            <i id="{{ $openModalId }}" class="{{ $modalTippy }} las la-edit ml-auto pr-2 la-lg cursor-pointer"></i>
        @endif
    </div>
    <div class="flex flex-row w-7/12 bg-theme-sub items-center relative group/clipboard">
        <p class="py-1 pl-3">{!! nl2br(e($value)) !!}</p>
        <x-clipboard-copy-btn :value="$value" :label="$label" />
    </div>
</div>