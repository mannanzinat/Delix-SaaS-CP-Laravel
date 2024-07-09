<?php

namespace App\Repositories;

use App\Models\Server;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpseclib3\Net\SSH2;
use Brian2694\Toastr\Facades\Toastr;


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
        $ssh = new SSH2($request['ip']);
        if (!$ssh->login('root', $request['password'])) {
            return false;
        }else{
            $request['password'] = $request['password']; 

            Server::create([
                'provider'   => $request['provider'],
                'ip'         => $request['ip'],
                'user_name'  => $request['user_name'],
                'password'   => $request['password'],
            ]);

            return response()->json(['message' => 'Server created successfully'], 200);
        }

        


    }

    public function update($request, $id)
    {
        $ssh                = new SSH2($request['ip']);
        $server             = Server::findOrFail($id);
        $password           = $request['password'] ? $request['password'] : $server->password;
        if (!$ssh->login('root', $password)) {
            return false;
        } else {
            $server->provider   = $request['provider'];
            $server->ip         = $request['ip'];
            $server->user_name  = $request['user_name'];
        
            if ($request['password']) {
                $server->password = $request['password'];
            }

            $server->save();

            return response()->json(['message' => 'Server updated successfully'], 200);
        }
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

    public function defaultChange($data)
    {
        $server = Server::findOrFail($data['id']);

        Server::query()->update(['default' => false]);

        $server->default = true;
        $server->save();
        
        return;
    }


}
