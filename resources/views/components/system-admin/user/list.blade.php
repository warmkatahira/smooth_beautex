<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="user_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="filter_table" class="text-xs" data-search-url="/user" data-scroll-target=".user_list">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0 h-7 z-10">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザーNo</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザーID</th>
                    <th class="font-thin py-1 px-2 text-center">氏名</th>
                    <th class="font-thin py-1 px-2 text-center">メールアドレス</th>
                    <th class="font-thin py-1 px-2 text-center">ステータス</th>
                    <th class="font-thin py-1 px-2 text-center">権限</th>
                    <th class="font-thin py-1 px-2 text-center">会社名</th>
                    <th class="font-thin py-1 px-2 text-center">パスワード変更</th>
                    <th class="font-thin py-1 px-2 text-center">最終ログイン日時</th>
                </tr>
                <tr class="filter-row sticky top-[28px] bg-white z-10">
                    <th></th>
                    <x-filter.input type="tel" id="filter_user_no" name="filter_user_no" />
                    <x-filter.input type="tel" id="filter_user_id" name="filter_user_id" />
                    <x-filter.input type="tel" id="filter_full_name" name="filter_full_name" />
                    <x-filter.input type="tel" id="filter_email" name="filter_email" />
                    <x-filter.select-boolean id="filter_status" name="filter_status" label1="有効" label0="無効" />
                    <x-filter.select id="filter_role_id" name="filter_role_id" :selectItems="$roles" optionValue="role_id" optionText="role_name" />
                    <x-filter.select id="filter_company_id" name="filter_company_id" :selectItems="$companies" optionValue="company_id" optionText="company_name" />
                    <x-filter.select-boolean id="filter_is_must_change_password" name="filter_is_must_change_password" label1="必要" label0="不要" />
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($users as $user)
                    <tr class="text-left cursor-default whitespace-nowrap @if(!$user->status) bg-common-disabled @endif">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('user_update.index', ['user_no' => $user->user_no]) }}" class="btn rounded bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-right">{{ $user->user_no }}</td>
                        <td class="py-1 px-2 border">{{ $user->user_id }}</td>
                        <td class="py-1 px-2 border">
                            <img class="profile_image_normal image_fade_in_modal_open" src="{{ asset('storage/profile_images/'.$user->profile_image_file_name) }}">
                            {{ $user->full_name }}
                        </td>
                        <td class="py-1 px-2 border">{{ $user->email }}</td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$user->status" label1="有効" label0="無効" />
                        </td>
                        <td class="py-1 px-2 border">{{ $user->role->role_name }}</td>
                        <td class="py-1 px-2 border">{{ $user->company->company_name }}</td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$user->is_must_change_password" label1="必要" label0="不要" />
                        </td>
                        <td class="py-1 px-2 border">
                            @if($user->last_login_at)
                                {{ CarbonImmutable::parse($user->last_login_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($user->last_login_at)->diffForHumans().')' }}
                            @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>