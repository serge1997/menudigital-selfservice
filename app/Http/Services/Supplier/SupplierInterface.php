<?php
namespace App\Http\Services\Supplier;

interface SupplierInterface
{
    public function beforeSave(string $supplierName);
}
