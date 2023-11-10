<?php

namespace App\Jobs;

use Exception;

use App\Models\DemoTest;
use App\Models\DemoTestInquiry;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DemoTestJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    private $forceError = false;

    /**
     * Create a new job instance.
     */
    public function __construct(private $object, private $inquiryId)
    {
        $this->forceError = !!env('FORCE_JOBS_ERRORS') && rand(1, 1000) <= 100;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $demoTestInquiry = DemoTestInquiry::find($this->inquiryId);

        try {
            if ($this->forceError) {
                throw new Exception('Error thrown');
            }

            $demoTest = DemoTest::firstWhere('ref', $this->object->ref);

            if (!$demoTest) {
                $demoTest = new DemoTest();
                $demoTest->ref = $this->object->ref;
            } else {
                $demoTest->status = 'UPDATED';
            }

            $demoTest->name = $this->object->name;
            $demoTest->description = $this->object->description;
            $demoTest->save();

            $demoTestInquiry->increment('items_processed_count');
        } catch (\Throwable $th) {
            $this->fail();
        }
    }

    /**
     * Called when the has is failing.
     */
    public function failed()
    {
        $demoTestInquiry = DemoTestInquiry::find($this->inquiryId);
        $demoTestInquiry->increment('items_failed_count');
    }
}
