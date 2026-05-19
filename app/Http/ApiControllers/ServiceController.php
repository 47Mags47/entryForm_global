<?php

namespace App\Http\ApiControllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;


class ServiceController
{
    public function index(Request $request)
    {
        return json_encode(Service::all()->map(fn($service) => [
            'id' => $service->id,
            'name' => $service->name,
            'workers' => $service->workers()
                ->where('division_id', $request->input('division'))
                ->get()
                ->map(fn($user) => [
                    'id' => $user->id,
                    'full_name' => implode(' ', [$user->last_name, $user->first_name, $user->middle_name])
                ])
        ]));
    }

    public function shedulesFromWorker(Request $request)
    {
        $worker = User::findOrFail($request->input('worker'));
        $service = Service::findOrFail($request->input('service'));
        $date = CarbonImmutable::parse($request->input('date'));

        return json_encode($service->getAvailableTimeFromUser($worker, $date));
    }

    public function availableWeekdays(Request $request)
    {
        $worker = User::findOrFail($request->input('worker'));
        $service = Service::findOrFail($request->input('service'));

        return response()->json(
            $service->getAvailableWeekdays($worker)
        );
    }

}
