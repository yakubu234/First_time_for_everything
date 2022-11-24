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
        #get pagination query params or set 1 as default
        $id = $request->query('page',1);

        #check if key exist in redis
        if(Redis::exists("users:{$id}")){
            $user = Redis::get("users:{$id}");
            return unserialize($user);
        }

        #select 10 records based on page number if redis key did not exist
        $users = User::orderBy('id', 'desc')->paginate(10);

        if($users->isEmpty()){
            $this->seedData();
            $users = User::orderBy('id', 'desc')->paginate(10);
        }

        $user = AllRecordsResource::collection(
            $users
        )->response()->getData(true);
         $count = User::count();

        $id = (round($count+5, -1)/10)/$id;

        Redis::set("users:{$id}",serialize($user));

        return response()->json([
            $user
        ],200);
    }

    public function fetchOne($id)
    {
        #fetch by supplying th id
        $range = round($id+5, -1)/10;

        if(Redis::exists("users:{$range}"))return $this->getRedisById($id,$range );

        if(Redis::exists("users-single:{$id}"))return $this->getRedisById($id);

        #selecet the user fromthe database if not exist in redis
        $users = User::where('id', $id)->first();
        $user = (new AllRecordsResource($users));

        Redis::set("users-single:{$id}",serialize($user));

        return response()->json([
            'data' => $user
        ],200);
    }

    public function seedData()
    {
        # to run the seeder
        Artisan::call('db:seed');
        Artisan::output();
        return ;
    }

    public function getRedisById($id, $range =null)
    {
            if($range == null){
                $user = Redis::get("users-single:{$id}");
                return response()->json([
                    'data' => unserialize($user)
                ],200);
            }

            #fetch the pagination and extract the object
            $user = Redis::get("users:{$range}");
            $data = unserialize($user)['data'];

            #search for the key here and grab the entire object of the key
            $user = $data[array_search($id,array_column($data, 'id'))];

            Redis::set("users-single:{$id}",serialize($user));# save in redis against another search
            return response()->json([
                'data' => $user
            ],200);
    }
}
