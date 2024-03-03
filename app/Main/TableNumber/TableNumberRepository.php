<?php
namespace App\Main\TableNumber;

use App\Models\TableNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use DateTime;

class TableNumberRepository implements TableNumberRepositoryInterface
{
    public function getAllFreeTable()
    {
        $table = TableNumber::select('tablenumber.table')
            ->whereNotIn('id', function($query) {
                $query->select('ped_tableNumber')
                    ->from('pedidos')
                        ->where([['status_id', '=', 6], ['ped_delete', false]]);
            })
                ->get();

        return $table;
    }

    public function getAllBusyTable(): Collection
    {
        $now = new DateTime();
        $now = $now->format('H:i');
        $busyTables = DB::table('pedidos')
        ->select(
            'pedidos.ped_tableNumber',
            'pedidos.id', 'users.name',
            'pedidos.ped_customer_quantity as customer',
            DB::raw("CONCAT(DATE_FORMAT(TIMEDIFF('$now', DATE_FORMAT(pedidos.created_at, '%H:%i')), '%i'), ' min') as timing")
            )
            ->join('users', 'pedidos.user_id', '=', 'users.id')
                ->where([['pedidos.status_id', '=', 6], ['pedidos.ped_delete', 0]])
                        ->get();

        return new Collection($busyTables);
    }
}
