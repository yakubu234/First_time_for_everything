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
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


    public function fetchAll(Request $request)
    {
        $id = $request->query('page',1);
        Redis::del("users:{$id}");

        if(Redis::exists("users:{$id}")){
            $user = Redis::get("users:{$id}");
            return unserialize($user);
        }

        $users = User::orderBy('id', 'desc')->paginate(10);

        if($users->isEmpty()){
            $this->seedData();
            $users = User::orderBy('id', 'desc')->paginate(10);
        }

        $user = AllRecordsResource::collection(
            $users
        )->response()->getData(true);
        Redis::set("users:{$id}",serialize($user));

        return response()->json([
            'data' => $user
        ],200);
    }

    public function fetchOne($id)
    {
        $range = round($id+5, -1);

        if(Redis::exists("users:{$range}"))return $this->getRedisById($id,$range );

        if(Redis::exists("users-single:{$id}"))return $this->getRedisById($id);

        $users = User::where('id', $id)->first();
        $user = (new AllRecordsResource($users));

        Redis::set("users-single:{$id}",serialize($user));

        return response()->json([
            'data' => $user
        ],200);
    }

    public function seedData()
    {
        Artisan::call('db:seed');
        Artisan::output();
        return ;
    }

    public function getRedisById($id, $range =null)
    {
            $user = Redis::get("users:{$range}");
            $data = unserialize($user);


            return $data;
    }
}
