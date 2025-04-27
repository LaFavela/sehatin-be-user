<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessUserJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $userId;

    /**
     * Create a new job instance.
     *
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
    /**
     * Get Unique ID for the job
     */
    public function uniqueId(): string {
        return $this->userId;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Process the user data
        // Log the data
        // and the queue name
        Log::info('Processing user data for user ID: ' . $this->userId);
    }
}
