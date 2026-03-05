<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <button type="button" id="national_holiday_get_api" class="dropdown-content-element"><i class="las la-cloud-download-alt la-lg mr-1"></i>国民の祝日取得</button>
        </div>
    </div>
</div>
<form method="POST" action="{{ route('national_holiday.get_api') }}" id="national_holiday_get_api_form" class="m-0">
    @csrf
    <input type="hidden" id="get_year" name="get_year">
</form>