<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <p>ご利用中のメールアドレスを入力してください</p>
        <p>パスワード再設定のためのURLをお送りします</p>
    </div>
    <!-- バリデーションエラー -->
    <x-validation-error-msg />
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <!-- メールアドレス -->
        <x-auth.input id="email" label="メールアドレス" type="email" :db="null" />
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-black text-white text-center rounded-lg py-2 px-5">送信</button>
        </div>
    </form>
</x-guest-layout>