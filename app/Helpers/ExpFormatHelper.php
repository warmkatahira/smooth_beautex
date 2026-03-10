<?php

    // 同じ名前の関数が既に存在していないか確認
    if(!function_exists('formatExp')){
        // 関数が存在しない場合、この関数を定義
        function formatExp($exp)
        {
            // 引数が '-' でない場合は、yyyymm形式の文字列をyyyy/mm形式に変換
            // 例: '202308' => '2023/08'
            // 引数が '-' の場合はそのまま '-' を返す
            return $exp != '-' ? substr($exp, 0, 4) . '/' . substr($exp, 4, 2) : '-';
        }
    }