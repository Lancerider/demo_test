<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemoTest;

class DemoTestController extends Controller
{
    /**
     * It activates a demoTest object
     */
    public function activate(DemoTest $demoTest)
    {
        if (!$demoTest) return abort(404);

        if ($demoTest->is_active) return response()->json(['error' => 'Already activated'], 403);

        $demoTest->is_active = true;
        $demoTest->save();

        return response()->json(["message" => 'Activated'], 200);
    }

    /**
     * It deactivates a demoTest object
     */
    public function deactivate(DemoTest $demoTest)
    {
        if (!$demoTest) return abort(404);

        if (!$demoTest->is_active) return response()->json(['error' => 'Already deactivated'], 403);

        $demoTest->is_active = false;
        $demoTest->save();

        return response()->json(["message" => 'Deactivated'], 200);
    }
}
