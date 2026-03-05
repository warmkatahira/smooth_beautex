<x-guest-layout>
    <!-- バリデーションエラー -->
    <x-validation-error-msg />
    <form method="POST" action="{{ route('register') }}" id="register_form">
        @csrf
        <!-- ユーザーID -->
        <x-auth.input id="user_id" label="ユーザーID" type="text" :db="null" />
        <p class="ml-2 text-xs text-gray-600">・8～20文字以内で設定して下さい</p>
        <p class="ml-2 text-xs text-gray-600">・英数字が使用できます</p>
        <!-- ユーザー名 -->
        <div class="mt-2">
            <label for="last_name" class="text-sm">ユーザー名</label>
            <div class="flex flex-row">
                <input type="text" id="last_name" name="last_name" class="text-sm block mt-1 w-5/12 rounded-lg" value="{{ old('last_name') }}" autocomplete="off">
                <input type="text" id="first_name" name="first_name" class="text-sm block mt-1 w-5/12 rounded-lg ml-auto" value="{{ old('first_name') }}" autocomplete="off">
            </div>
            <div class="flex flex-row text-gray-600">
                <span class="text-xs w-5/12 pl-1">姓</span>
                <span class="text-xs w-5/12 pl-1 ml-auto">名</span>
            </div>
        </div>
        <!-- メールアドレス -->
        <x-auth.input id="email" label="メールアドレス" type="email" :db="null" />
        <!-- パスワード -->
        <x-auth.input id="password" label="パスワード" type="password" :db="null" />
        <p class="ml-2 text-xs text-gray-600">・8～20文字以内で設定して下さい</p>
        <p class="ml-2 text-xs text-gray-600">・英数字が使用できます</p>
        <!-- パスワード（確認用） -->
        <x-auth.input id="password_confirmation" label="パスワード（確認用）" type="password" :db="null" />
        <p class="ml-2 text-xs text-gray-600">・パスワードで入力したものと同じパスワードを入力して下さい</p>
        <div class="flex items-center justify-end mt-4">
            <button type="button" id="register_enter" class="btn bg-black text-white text-center rounded-lg py-2 px-5">登録</button>
        </div>
    </form>
</x-guest-layout>
@vite(['resources/js/auth/register.js'])