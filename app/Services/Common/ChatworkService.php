<?php

namespace App\Services\Common;

// 列挙
use App\Enums\ChatworkEnum;
use App\Enums\SystemEnum;
// その他
use Carbon\CarbonImmutable;

class ChatworkService
{
    // Chatworkに通知する処理
    public function postMessage()
    {
        // 現在の日時を格納
        $nowDate = '通知日時　：'.CarbonImmutable::now()->format('Y/m/d H:i:s')."\n";
        // 荷主名を格納
        $customer_name = '荷主名　　：'.SystemEnum::CUSTOMER_NAME."\n";
        // メッセージを形成
        $message = "[info][title]smooth@百道からのメッセージ[/title]".$nowDate.$customer_name."購入数の更新処理が行われました。[/info]";
        // メッセージを投稿
        $this->postEnter($message);
    }

    // メッセージを投稿
    public function postEnter($message)
    {
        // メッセージを投稿
        $data = array('body' => $message);
        $options = array(
            'http' => array(
                'header' => "X-ChatWorkToken: " . ChatworkEnum::ACCESS_TOKEN . "\r\n" .
                            "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents(ChatworkEnum::URL, false, $context);
    }
}