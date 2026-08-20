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

    public function getAvailableTimeFromUser(
        User $worker,
        CarbonImmutable $date
    ) {
        $date = $date->toMutable();

        $schedule = $worker->shedules()
            ->where('day_of_the_week_id', $date->dayOfWeek)
            ->first();

        if (!$schedule) {
            return [];
        }

        $duration = $this->duration;
        $durationMinutes = $duration->hour * 60 + $duration->minute;

        $workStart = $date->copy()->setTime(
            $schedule->date_start->hour,
            $schedule->date_start->minute
        );

        $workEnd = $date->copy()->setTime(
            $schedule->date_end->hour,
            $schedule->date_end->minute
        );

        // ЗАНЯТОЕ ВРЕМЯ
        $busyTimes = $worker->subscribes()
            ->whereBetween('start_at', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ])
            ->with('service')
            ->orderBy('start_at')
            ->get()
            ->map(function ($subscribe) {

                $serviceDuration = $subscribe->service->duration;

                $serviceDurationMinutes =
                    $serviceDuration->hour * 60
                    + $serviceDuration->minute;

                $start = $subscribe->start_at->copy();

                return [
                    'start' => $start,
                    'end' => $start->copy()->addMinutes(
                        $serviceDurationMinutes
                    ),
                ];
            });

        // ОБЕД
        if ($schedule->lunch_start && $schedule->lunch_end) {
            $busyTimes->push([
                'start' => $date->copy()->setTime(
                    $schedule->lunch_start->hour,
                    $schedule->lunch_start->minute
                ),

                'end' => $date->copy()->setTime(
                    $schedule->lunch_end->hour,
                    $schedule->lunch_end->minute
                ),
            ]);
        }

        // ДОСТУПНОЕ ВРЕМЯ
        $availableTimes = [];

        for (
            $current = $workStart->copy();
            $current->copy()->addMinutes($durationMinutes) <= $workEnd;
            $current->addMinutes($durationMinutes)
        ) {
            $slotStart = $current->copy();

            $slotEnd = $current->copy()->addMinutes(
                $durationMinutes
            );

            $hasConflict = $busyTimes->contains(function ($busyTime) use (
                $slotStart,
                $slotEnd
            ) {
                return $slotStart < $busyTime['end']
                    && $slotEnd > $busyTime['start'];
            });

            if (!$hasConflict) {
                $availableTimes[] = $slotStart->format('H:i');
            }
        }

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
