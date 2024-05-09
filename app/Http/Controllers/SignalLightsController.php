<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SignalLight;

class SignalLightsController extends Controller
{
    public function index()
    {
        return view('signal-lights-new');
    }

    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sequence' => 'required|array',
            'sequence.*' => 'integer|min:1|max:4',
            'green_interval' => 'required|integer|min:1',
            'yellow_interval' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
 
        SignalLight::create([
            'sequence' => $request->input('sequence'),
            'green_interval' => $request->input('green_interval'),
            'yellow_interval' => $request->input('yellow_interval'),
        ]);

        $latestSignalLight = SignalLight::latest()->first();

        return response()->json(['signal_light' => $latestSignalLight]);
    }
}
