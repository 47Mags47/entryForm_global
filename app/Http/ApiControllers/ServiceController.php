<?php

namespace App\Http\ApiControllers;

use App\Models\Division;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class ServiceController
{
    public function index(Request $request, Division $division)
    {
        $division = Division::find($request->input('division'));

        if ($division === null)
            return abort(404);

        $workers_ids = $division->workers()->pluck('id');

        $services = Service::query()
            ->with([
                'workers' => fn ($query) =>
                    $query->whereIn('main__users.id', $workers_ids),
            ])
            ->whereHas('workers', fn ($query) =>
                $query->whereIn('main__users.id', $workers_ids)
            )
            ->get()
            ->map(fn ($service) => [
                'id' => $service->id,
                'name' => $service->name,
                'workers' => $service->workers->map(fn ($worker) => [
                    'id' => $worker->id,
                    'full_name' => implode(' ', array_filter([
                        $worker->last_name,
                        $worker->first_name,
                        $worker->middle_name,
                    ])),
                ])->values(),
            ]);

            return json_encode($services);
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
