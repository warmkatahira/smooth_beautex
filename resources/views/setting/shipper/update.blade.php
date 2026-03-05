<x-app-layout>
    <x-page-back :url="route('shipper.index')" />
    <div class="flex flex-row gap-10 my-5">
        <form method="POST" action="{{ route('shipper_update.update') }}" id="shipper_update_form">
            @csrf
            <div class="flex flex-col gap-3">
                <x-form.input type="text" label="荷送人会社名" id="shipper_company_name" name="shipper_company_name" :value="$shipper->shipper_company_name" required="true" />
                <x-form.input type="text" label="荷送人名" id="shipper_name" name="shipper_name" :value="$shipper->shipper_name" required="true" />
                <x-form.input type="text" label="荷送人郵便番号" id="shipper_zip_code" name="shipper_zip_code" :value="$shipper->shipper_zip_code" required="true" />
                <x-form.input type="text" label="荷送人住所" id="shipper_address" name="shipper_address" :value="$shipper->shipper_address" required="true" />
                <x-form.input type="text" label="荷送人電話番号" id="shipper_tel" name="shipper_tel" :value="$shipper->shipper_tel" required="true" />
                <x-form.input type="text" label="荷送人メールアドレス" id="shipper_email" name="shipper_email" :value="$shipper->shipper_email" />
                <x-form.input type="text" label="荷送人インボイス番号" id="shipper_invoice_no" name="shipper_invoice_no" :value="$shipper->shipper_invoice_no" />
            </div>
            <input type="hidden" name="shipper_id" value="{{ $shipper->shipper_id }}">
            <button type="button" id="shipper_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-5"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/setting/shipper/shipper.js'])