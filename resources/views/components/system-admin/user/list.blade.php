<div class="disable_scrollbar flex flex-grow overflow-scroll my-3">
    <div class="user_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザーNo</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザーID</th>
                    <th class="font-thin py-1 px-2 text-center">氏名</th>
                    <th class="font-thin py-1 px-2 text-center">メールアドレス</th>
                    <th class="font-thin py-1 px-2 text-center">ステータス</th>
                    <th class="font-thin py-1 px-2 text-center">権限</th>
                    <th class="font-thin py-1 px-2 text-center">会社名</th>
                    <th class="font-thin py-1 px-2 text-center">最終ログイン日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($users as $user)
                    <tr class="text-left cursor-default whitespace-nowrap @if(!$user->status) bg-common-disabled @endif">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('user_update.index', ['user_no' => $user->user_no]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">{{ $user->user_no }}</td>
                        <td class="py-1 px-2 border">{{ $user->user_id }}</td>
                        <td class="py-1 px-2 border">
                            <img class="profile_image_normal image_fade_in_modal_open" src="{{ asset('storage/profile_images/'.$user->profile_image_file_name) }}">
                            {{ $user->full_name }}
                        </td>
                        <td class="py-1 px-2 border">{{ $user->email }}</td>
                        <td class="py-1 px-2 border text-center">{{ $user->status_text }}</td>
                        <td class="py-1 px-2 border">{{ $user->role->role_name }}</td>
                        <td class="py-1 px-2 border">{{ $user->company->company_name }}</td>
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