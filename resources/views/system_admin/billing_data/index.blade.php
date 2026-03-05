<x-app-layout>
    <div class="mt-5">
        <form method="GET" action="{{ route('billing_data_download.download') }}">
            <div class="flex flex-col text-base">
                <label for="billing_date" class="mb-1"><i class="las la-calendar-alt mr-1 la-lg"></i>請求年月</label>
                <div class="flex flex-col">
                    <input type="month" id="billing_date" name="billing_date" class="search_element date_from py-2 rounded border-gray-400 text-base w-52" autocomplete="off">
                </div>
            </div>
            <button type="submit" class="btn bg-btn-enter p-3 text-white w-56 mt-5"><i class="las la-download la-lg mr-1"></i>ダウンロード</button>
        </form>
    </div>
</x-app-layout>