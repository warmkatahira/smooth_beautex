<x-guest-layout>
    <!-- バリデーションエラー -->
    <x-validation-error-msg />
    <div>
        <p class="text-center py-2 my-3 bg-theme-main text-lg">パスワード変更画面</p>
        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf
            <div>
                <x-auth.input id="password" label="新しいパスワード" type="password" :db="null" />
                <p class="ml-2 text-xs text-gray-600">・8～20文字以内で設定して下さい</p>
                <p class="ml-2 text-xs text-gray-600">・英数字が使用できます</p>
            </div>
            <div>
                <x-auth.input id="password_confirmation" label="新しいパスワード（確認用）" type="password" :db="null" />
            </div>
            <button type="submit" class="btn bg-btn-enter text-white text-center rounded-lg py-2 w-full mt-3"><i class="las la-check la-lg mr-1"></i>変更</button>
        </form>
    </div>
</x-guest-layout>