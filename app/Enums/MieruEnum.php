<?php

namespace App\Enums;

enum MieruEnum
{
    // 項目を定義
    const SHIPMENT_ORDER_QUANTITY                           = "shipment_order_quantity";
    const SHIPMENT_QUANTITY_PCS                             = "shipment_quantity_pcs";
    const SHIPMENT_QUANTITY_BL                              = "shipment_quantity_bl";
    const SHIPMENT_QUANTITY_CS                              = "shipment_quantity_cs";
    const INSPECTION_INCOMPLETE_SHIPMENT_ORDER_QUANTITY     = "inspection_incomplete_shipment_order_quantity";
    const INSPECTION_INCOMPLETE_SHIPMENT_QUANTITY_PCS       = "inspection_incomplete_shipment_quantity_pcs";
    const INSPECTION_INCOMPLETE_SHIPMENT_QUANTITY_BL        = "inspection_incomplete_shipment_quantity_bl";
    const INSPECTION_INCOMPLETE_SHIPMENT_QUANTITY_CS        = "inspection_incomplete_shipment_quantity_cs";
}