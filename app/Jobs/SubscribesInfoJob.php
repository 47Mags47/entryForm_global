<?php

namespace App\Jobs;

use App\Mail\SubscribesInfoMail;
use App\Models\Subscribe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SubscribesInfoJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Subscribe $subscribe){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->subscribe->email)->send(new SubscribesInfoMail($this->subscribe));
    }
}
