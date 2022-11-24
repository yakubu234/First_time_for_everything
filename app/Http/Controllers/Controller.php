<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllRecordsResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


    public function fetchAll()
    {
        if(Redis::exists('users')){
            $user = Redis::get('users');
            return unserialize($user);
        }

        $users = User::orderBy('id', 'desc')->paginate(10);

        if($users->isEmpty()){
            $this->seedData();
            $this->fetchAll();
        }

        $user = AllRecordsResource::collection(
            $users
        );

        Redis::set('users',serialize($user));

        return response()->json([
            'data' => $user
        ],200);
    }

    public function fetchOne()
    {

    }

    public function seedData()
    {
        Artisan::call('db:seed');
        Artisan::output();
        return ;
    }
}
