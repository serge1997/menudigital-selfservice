<?php
namespace App\Main\TechnicalFiche;

use App\Models\Technicalfiche;
use Illuminate\Database\Eloquent\Collection;


class TechnicalFicheRepository implements TechnicalFicheRepositoryInterface
{
    public function findByItemId($id): Collection
    {
        return new Collection(
            Technicalfiche::select('*')
            ->join('products', 'technicalfiches.productID', '=', 'products.id')
                ->where('itemID', $id)
                    ->get()
        );
    }
}
