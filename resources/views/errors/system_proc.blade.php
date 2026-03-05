<x-exception-layout>
    <div class="flex flex-col items-center justify-center h-full">
        <div class="text-center">
            <img src="{{ asset('image/system_proc.svg') }}" class="w-52 text-center">
        </div>
        <div class="">
            <p class="text-5xl text-center">システム処理中です</p>
            <p class="text-2xl text-center mt-10">現在システム処理を行っているため、smoothを利用できません。</p>
            <p class="text-2xl text-center mt-3">終了まで、しばらくお待ちください。</p>
        </div>
    </div>
</x-exception-layout>
