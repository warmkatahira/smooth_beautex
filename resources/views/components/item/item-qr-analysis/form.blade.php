<form method="POST" action="{{ route('item_qr_analysis.analysis') }}" class="" id="item_qr_analysis_form">
    @csrf
    <div class="flex flex-col gap-3 mt-5">
        <x-form.input type="tel" label="QRコード" id="doari_qr" name="doari_qr" :value="null" />
        <x-form.input type="tel" label="JANバーコード" id="doari_jan" name="doari_jan" :value="null" />
        <x-form.input type="tel" label="LOTバーコード" id="doari_lot" name="doari_lot" :value="null" />
        <div class="flex flex-row">
            <label for="doari_power" class="w-56 bg-black text-white py-2.5 pl-3 relative">度数</label>
            <select id="doari_power" name="doari_power" class="w-96 text-sm">
                @foreach($powerLists as $power_list)
                    <option value="{{ $power_list }}">{{ $power_list }}</value>
                @endforeach
            </select>
        </div>
        <button type="button" id="item_qr_analysis_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>解析</button>
    </div>
</form>