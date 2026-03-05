<x-guest-layout>
    <!-- バリデーションエラー -->
    <x-validation-error-msg />
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <!-- メールアドレス -->
        <x-auth.input id="email" label="メールアドレス" type="email" :db="$request->email" />
        <!-- パスワード -->
        <x-auth.input id="password" label="パスワード" type="password" :db="null" />
        <p class="ml-2 text-xs text-gray-600">・8～20文字以内で設定して下さい</p>
        <p class="ml-2 text-xs text-gray-600">・英大文字/英小文字/数字の3つを使用して下さい</p>
        <!-- パスワード（確認用） -->
        <x-auth.input id="password_confirmation" label="パスワード（確認用）" type="password" :db="null" />
        <p class="ml-2 text-xs text-gray-600">パスワードで入力したものと同じパスワードを入力して下さい</p>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-black text-white text-center rounded-lg py-2 px-5">リセット</button>
        </div>
    </form>
</x-guest-layout>