<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GroutProductionBatch;
use App\Models\Notification as DbNotification;
use App\Services\NotificationService;
use Carbon\Carbon;

class CheckMixingTimers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grout:check-timers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan active grout mixing timers and trigger push notifications when completed';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info("Scanning active grout mixing timers...");

        $batches = GroutProductionBatch::where('status', 'Timer Running')
            ->where('timer_end_time', '<=', now())
            ->with(['machine', 'color', 'operator'])
            ->get();

        if ($batches->isEmpty()) {
            $this->info("No completed mixing timers found.");
            return 0;
        }

        foreach ($batches as $batch) {
            // Prevent duplicate notifications for this batch
            $alreadyNotified = DbNotification::where('type', 'grout_mixing_complete')
                ->where('payload->batch_id', $batch->id)
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            $machineCode = $batch->machine->code;
            $supervisorName = $batch->operator->name ?? 'Supervisor';
            $currentTime = Carbon::now()->timezone('Asia/Kolkata')->format('h:i A');

            $title = "Mixing Complete";
            $body = "Machine {$machineCode} mixing completed.\n" . 
                    "Batch: {$batch->batch_no}\n" .
                    "Department: Grout\n" .
                    "Supervisor: {$supervisorName}\n" .
                    "Current Time: {$currentTime}";

            $clickAction = route('grout-production.running', $batch->id);

            // Send notification to Grout department (GRT)
            $notificationService->sendToDepartment(
                'GRT',
                $title,
                $body,
                'grout_mixing_complete',
                [
                    'batch_id' => $batch->id,
                    'batch_no' => $batch->batch_no,
                    'machine_code' => $machineCode,
                    'click_action' => $clickAction
                ]
            );

            $this->info("Mixing completed notification sent for Batch #{$batch->batch_no} on Machine {$machineCode}.");
        }

        return 0;
    }
}
