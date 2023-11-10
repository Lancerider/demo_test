<?php

namespace App\Jobs;

use App\Models\DemoTestInquiry;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

class InquiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $inquiryId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(DemoTestInquiry $inquiry): void
    {
        $inquiry = DemoTestInquiry::find($this->inquiryId);

        $demoTestObjects = json_decode($inquiry->payload);

        $testDemoJobs = [];

        foreach ($demoTestObjects as $object) {
            $testDemoJobs[] = new DemoTestJob($object, $inquiry->id);
        }

        Bus::batch($testDemoJobs)->then(function (Batch $batch) use ($inquiry) {
            $inquiry->status = "PROCESSED";
            $inquiry->save();
        })->finally(function (Batch $batch) use ($inquiry) {
            $inquiry->fresh();

            if ($inquiry->items_failed_count > 0) {
                $inquiry->status = "FAILED";
                $inquiry->save();
            }
        })->allowFailures()
            ->name("inquiry_bus-{$this->inquiryId}")
            ->onQueue('demo_job_queue')
            ->dispatch();
    }
}
