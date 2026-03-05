<x-app-layout>
    <x-page-back :url="route('base.index')" />
    <div class="mt-5">
        <form method="POST" action="{{ route('user_update.update') }}" id="user_update_form">
            @csrf
            <div class="flex flex-col gap-3 my-5">
                <x-form.p label="ユーザーID" :value="$user->user_id" />
                <x-form.input type="text" label="姓" id="last_name" name="last_name" :value="$user->last_name" required="true" />
                <x-form.input type="text" label="名" id="first_name" name="first_name" :value="$user->first_name" />
                <x-form.p label="メールアドレス" :value="$user->email" />
                <x-form.select-boolean label="ステータス" id="status" name="status" :value="$user->status" required="true" />
                <x-form.select label="権限" id="role_id" name="role_id" :value="$user->role_id" :items="$roles" optionValue="role_id" optionText="role_name" required="true" />
                <x-form.select label="会社" id="company_id" name="company_id" :value="$user->company_id" :items="$companies" optionValue="company_id" optionText="company_name" required="true" />
            </div>
            <input type="hidden" name="user_no" value="{{ $user->user_no }}">
            <button type="button" id="user_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/user/user.js'])