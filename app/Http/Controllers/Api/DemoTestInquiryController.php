<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\DemoTestInquiry;
use App\Jobs\InquiryJob;
use Illuminate\Support\Facades\DB;

class DemoTestInquiryController extends Controller
{
    /**
     * It takes an Inquiry to save a group of objects, it saves the inquiry and it triggers
     * queue jobs to manage the petition.
     */
    public function store(Request $request)
    {
        $demoTestObjects = $request->all();
        $isArrayTooLong = count($demoTestObjects) > 2000;

        if ($isArrayTooLong) {
            return response()->json(['error' => 'Array should not exceed 2000 elements'], 400);
        }

        $validator = Validator::make($demoTestObjects, [
            '*' => 'required|array|min:3',
            '*.ref' => 'required|string|distinct',
            '*.name' => 'required|string',
            '*.description' => 'string|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        $objectsIds = array_column($demoTestObjects, 'ref');
        $deactivatedObjects = DB::table('demo_test')->whereIn('ref', $objectsIds)->where('is_active', false)->get();
        $deactivatedObjectsFound = count($deactivatedObjects) > 0;

        if ($deactivatedObjectsFound) {
            $deactivatedIds = array_column($deactivatedObjects->toArray(), 'ref');

            return response()->json(['errors' => "Unable to save data because the refs [" . implode(', ', $deactivatedIds) . "] are deactivated."], 422);
        }

        $inquiry = new DemoTestInquiry();
        $inquiry->payload = json_encode($demoTestObjects);
        $inquiry->items_total_count = count($demoTestObjects);
        $inquiry->save();

        InquiryJob::dispatchAfterResponse($inquiry->id);

        return response(['inquiry_id' => $inquiry->id]);
    }
}
