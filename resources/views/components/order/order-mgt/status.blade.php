<div class="flex flex-row">
    @foreach($dispStatuses as $disp_status)
        <div class="text-center w-32 mr-4 shadow-md">
            <p class="bg-black text-white py-1 border border-black">{{ $disp_status['order_status'] }}</p>
            <p class="py-1 border border-black {{ session('filter_order_status_id') == $disp_status['order_status_id'] ? 'bg-theme-sub' : 'bg-white' }}"><a href="{{ route('order_mgt.index', ['filter_order_status_id' => $disp_status['order_status_id']]) }}" class="start_loading text-blue-500 underline">{{ $disp_status['order_count'].'件' }}</a></p>
        </div>
    @endforeach
    <input type="hidden" id="filter_order_status_id" value="{{ session('filter_order_status_id') }}">
</div>