<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeRequest;
use App\Jobs\SendSubscribeWorkerAlertJob;
use App\Jobs\SubscribesInfoJob;
use App\Models\Frame;
use App\Models\Subscribe;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SubscribeController extends Controller
{
    public function create(string $token)
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return response()
                ->view('dbDisconnected', [], 503);
        }

        SendSubscribeWorkerAlertJob::dispatch(\App\Models\Subscribe::all()->random());

        $frame = Frame::where('token', $token)->firstOrFail();
        return response()
            ->view('frame', compact('frame'));
    }

    public function store(StoreSubscribeRequest $request)
    {
        $subscribe = Subscribe::create($request->only(
            'first_name',
            'last_name',
            'middle_name',
            'phone',
            'email',
            'division_id',
            'service_id',
            'start_at',
            'worker_id',
            'comment',
        ));

        SubscribesInfoJob::dispatch($subscribe);

        if($subscribe->worker->receiveMail){
            SendSubscribeWorkerAlertJob::dispatch($subscribe);
        }

        return view('complited');
    }
}
