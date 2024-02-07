<?php
namespace App\Main\Planning;

use App\Models\Planning;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlanningRepository implements PlanningRepositoryInterface
{

    public function create($request): void
    {
        $users_name = $request->users_name;
        $html_id = $request->html_id;
        foreach ($users_name as $key => $user)
        {
            if (!$this->beforeSave($user, $html_id[$key])){
                $planning = new Planning();
                $planning->user_name = $user;
                $planning->html_id = $html_id[$key];
                $planning->save();
            }
        }
    }

    public function getAll(): Collection
    {
        return new Collection(
            Planning::all()
        );
    }

    public function beforeSave(string $user_name, string $htm_id): bool
    {
        if (Planning::where([['user_name', $user_name], ['html_id', $htm_id]])->exists()){
            return true;
        }
        return false;
    }

    public function clearTable(): void
    {
        DB::table('planning')->delete();
    }
}
