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
    public function postMessageAtSihppingWorkStart($order_count, $shipping_group_name)
    {
        // 現在の日時を格納
        $notice_date = '通知日時：'.CarbonImmutable::now()->format('Y/m/d H:i:s');
        // メッセージを形成
        $message = "[info][title]smooth@".SystemEnum::CUSTOMER_NAME_JP."からのメッセージ[/title]".
                    "以下の出荷作業開始が行われました。\n\n".
                    $notice_date."\n".
                    "件数：".$order_count."\n".
                    "出荷グループ名：".$shipping_group_name.
                    "[/info]";
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