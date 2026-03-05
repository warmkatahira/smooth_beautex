<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
// リクエスト
use App\Http\Requests\Auth\UserCreateRequest;
// その他
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserCreateRequest $request): RedirectResponse
    {
        // ユーザー作成
        $user = User::create([
            'user_id' => $request->user_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'company_id' => 'warm',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // +-+-+-+-+-+-+-+-+-+-   ユーザー登録通知メール   +-+-+-+-+-+-+-+-+-+-
        // インスタンス化
        $mail = new UserRegisteredMail($user);
        // 送信先のメールアドレスを格納
        $to_users = 't.katahira@warm.co.jp';
        // Toを設定
        $mail->to($to_users);
        // 件名を設定
        $mail->subject('【smooth】ユーザー登録通知');
        // メールを送信
        Mail::send($mail);
        // +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-

        // 自動ログインさせない
        //Auth::login($user);
        //return redirect(route('dashboard', absolute: false));

        return redirect()->route('welcome.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'ユーザー登録が完了しました。<br>承認されるまでお待ち下さい。<br>※承認が完了するとメールで通知されます。',
        ]);
    }
}
