<?php

namespace App\Repositories;

use App\Models\Server;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServerRepository
{
    use ImageTrait;

    public function all($data, $relation = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if (! arrayCheck('paginate', $data)) {
            $data['paginate'] = setting('paginate');
        }

        return Server::with($relation)->latest()->paginate($data['paginate']);
    }

    public function activeClient()
    {
        return Server::latest()->where('status', 1)->get();
    }


    public function find($id)
    {
        return Server::find($id);
    }

    public function store($request)
    {

        $request['provider']                 = $request['provider'];
        $request['ip']                       = $request['ip'];
        $request['user_name']                = $request['user_name'];
        $request['password']                 = bcrypt($request['password']);

        Server::create($request);

        return;

    }

    public function update($request, $id)
    {

        $server                             = Server::findOrFail($id);
        $server['provider']                 = $request['provider'];
        $server['ip']                       = $request['ip'];
        $server['user_name']                = $request['user_name'];
        if($request['password']){
            $server['password']             = bcrypt($request['password']);
        }
        $server->save();
        return;
    }

    public function destroy($id): int
    {
        return Server::destroy($id);
    }

    public function statusChange($request)
    {
        $id = $request['id'];
        return Server::find($id)->update($request);
    }

}
