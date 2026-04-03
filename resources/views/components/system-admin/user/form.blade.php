<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('user_create.create')
                    : route('user_update.update') }}"
      id="user_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        @if($form_mode === 'create')
            <x-form.input type="text" label="ユーザーID" id="user_id" name="user_id" :value="null" required="true" />
        @else
            <x-form.p label="ユーザーID" :value="$user->user_id" grayedOut="true" />
        @endif
        <x-form.input type="text" label="姓" id="last_name" name="last_name" :value="$form_mode === 'update' ? $user->last_name : null" required="true" />
        <x-form.input type="text" label="名" id="first_name" name="first_name" :value="$form_mode === 'update' ? $user->first_name : null" />
        <x-form.input type="tel" label="メールアドレス" id="email" name="email" :value="$form_mode === 'update' ? $user->email : null" />
        <x-form.switch-boolean label="ステータス" id="status" name="status" label0="無効" label1="有効" :value="$form_mode === 'update' ? $user->status : null" required="true" />
        <x-form.select label="権限" id="role_id" name="role_id" :value="$form_mode === 'update' ? $user->role_id : null" :items="$roles" optionValue="role_id" optionText="role_name" required="true" />
        <x-form.select label="会社" id="company_id" name="company_id" :value="$form_mode === 'update' ? $user->company_id : null" :items="$companies" optionValue="company_id" optionText="company_name" required="true" />
        @if($form_mode === 'update')
            <x-form.switch-boolean label="パスワード変更" id="must_change_password" name="must_change_password" label0="不要" label1="必要" :value="$form_mode === 'update' ? $user->must_change_password : null" required="true" />
        @endif
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="user_no" value="{{ $user->user_no }}">
    @endif
    <button type="button" id="user_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>