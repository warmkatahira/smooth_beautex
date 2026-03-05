<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.holiday.operation-div />
    </div>
    <x-system-admin.holiday.list :holidaysByYear="$holidays_by_year" />
</x-app-layout>
@vite(['resources/js/system_admin/holiday/holiday.js'])