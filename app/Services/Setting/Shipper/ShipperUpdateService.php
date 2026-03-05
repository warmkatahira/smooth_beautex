<?php

namespace App\Services\Setting\Shipper;

// モデル
use App\Models\Shipper;

class ShipperUpdateService
{
    // 荷送人を更新
    public function updateShipper($request)
    {
        // 荷送人を更新
        Shipper::getSpecify($request->shipper_id)->update([
            'shipper_company_name' => $request->shipper_company_name,
            'shipper_name' => $request->shipper_name,
            'shipper_zip_code' => $request->shipper_zip_code,
            'shipper_address' => $request->shipper_address,
            'shipper_tel' => $request->shipper_tel,
            'shipper_email' => $request->shipper_email,
            'shipper_invoice_no' => $request->shipper_invoice_no,
        ]);
    }
}