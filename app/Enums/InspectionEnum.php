<?php

namespace App\Enums;

enum InspectionEnum
{
    // JANコードの長さ（桁数）を定義
    const JAN_LENGTH = 13;
    // JANコードの開始位置を定義
    const JAN_START_POSITION = 1;
    // S-POWERコードの長さ（桁数）を定義
    const S_POWER_CODE_LENGTH = 3;
    // EXPの長さ（桁数）を定義
    const EXP_LENGTH = 4;
    // EXPが現在から数えて何ヶ月以内をNGとするかの閾値
    const EXP_THRESHOLD = 3;
}
