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
    if($openModalId === 'shipping_base_update_modal_open' && $order->order_status_id < OrderStatusEnum::SAGYO_CHU){
        $is_modal_icon_disp = true;
    }
    // 配送方法
    if($openModalId === 'shipping_method_update_modal_open' && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI){
        $is_modal_icon_disp = true;
    }
    // 配送希望日
    if($openModalId === 'desired_delivery_date_update_modal_open' && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI){
        $is_modal_icon_disp = true;
    }
    // 領収書宛名
    if($openModalId === 'receipt_name_update_modal_open' && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI){
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
    <p class="w-7/12 py-1 pl-3 bg-theme-sub">{!! nl2br(e($value)) !!}</p>
</div>