<?php
namespace App\Http\Services\Supplier;

use App\Models\Supplier;
use Exception;
class SupplierRepository implements SupplierInterface
{
    /**
     * @throws Exception
     */
    public function beforeSave(string $supplierName)
    {
        $check_name = Supplier::where('sup_name', $supplierName)->exists();

        if ($check_name)
        {
            throw new Exception("Fornecedor já existe");
        }
    }
}
