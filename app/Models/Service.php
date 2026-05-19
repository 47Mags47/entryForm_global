<?php

namespace App\Models;

use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\CarbonImmutable;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\Division\ServiceFactory> */
    use HasFactory, SoftDeletes;

    ### Настройки
    ##################################################
    protected
    $table = 'main__services',
    $fillable = [
        'name',
        'duration',
    ];

    protected function casts(): array
    {
        return [
            'duration' => 'datetime:H:i',
        ];
    }

    public function getAvailableTimeFromUser(User $worker, CarbonImmutable $date)
    {
        $shedule = $worker->shedules()->where('day_of_the_week_id', $date->dayOfWeek())->first();
        $subscribes = $worker->subscribes()
            ->whereBetween("start_at", [$date->startOfDay(), $date->endOfDay()])
            ->orderBy('start_at')
            ->get();
        $step = $this->duration->format('H') . ' hours '
            . $this->duration->format('i') . ' minutes '
            . $this->duration->format('s') . ' seconds';

        $busyTimes = $subscribes->map(
            function ($subscribe) {
                return [
                    'start' => $subscribe->start_at->format('H:i'),
                    'end' => $subscribe->start_at
                        ->addHours((int) $subscribe->service->duration->format('H'))
                        ->addMinutes((int) $subscribe->service->duration->format('i'))
                        ->format('H:i')
                ];
            }
        );

        if ($shedule->lunch_start and $shedule->lunch_end)
            $busyTimes[] = [
                'start' => $shedule->lunch_start->sub($step)->format('H:i'),
                'end' => $shedule->lunch_end->format('H:i'),
            ];

        $availableTimes = [];
        $currentStep = $shedule->date_start->format('H:i');

        foreach ($busyTimes as $busyTime) {

            if ($currentStep < $busyTime['start'])
                $availableTimes[] = ['start' => $currentStep, 'end' => $busyTime['start']];

            if ($currentStep < $busyTime['end'])
                $currentStep = $busyTime['end'];
        }
        $availableTimes[] = [
            'start' => $busyTimes->last()['end'],
            'end' => $shedule->date_end->sub($step)->format('H:i')
        ];


        $availableTimes = collect($availableTimes)->map(function ($availableTimes) use ($step) {
            $period = new CarbonPeriod($availableTimes['start'], $step, $availableTimes['end']);

            return collect($period->toArray())->map(fn($date) => $date->format('H:i'));
        })->collapse()->toArray();

        return $availableTimes;
    }

    public function getAvailableWeekdays(User $worker): array
    {

        return WorkSchedule::where('user_id', $worker->id)
            ->pluck('day_of_the_week_id')
            ->unique()
            ->values()
            ->toArray();
    }
    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'main__user_service', 'service_id', 'user_id');
    }
}
