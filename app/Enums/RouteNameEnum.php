<?php

namespace App\Enums;

enum RouteNameEnum
{
    // 出荷管理のルート名を定義
    const SHIPPING_MGT = 'shipping_mgt.index';

    // 在庫のルート名を定義
    const STOCK_BY_ITEM    = 'stock.index_by_item';
    const STOCK_BY_STOCK   = 'stock.index_by_stock';

    // 入力在庫数操作のルート名を定義
    const INPUT_STOCK_OPERATION  = 'input_stock_operation.index';
}
