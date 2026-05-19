<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeRequest;
use App\Jobs\SubscribesInfoJob;
use App\Models\Frame;
use App\Models\Subscribe;
class SubscribeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function create(string $token){
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
        return view('complited');
    }
}
