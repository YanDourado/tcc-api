<?php

namespace App\Http\Controllers;

use Auth;
use App\TCC;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the profile of user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try
        {
            $user = Auth::user();

            $profile = array();

            $profile['cameras_count'] = $user->Cameras()
                                                ->count();

            $profile['active_cameras_count'] = $user->Cameras()
                                                        ->where('status', 1)
                                                        ->count();

            $profile['alerts_count'] = $user->Alerts()
                                              ->count();

            $profile['viewed_alerts_count'] = $user->Alerts()
                                                    ->whereNotNull('viewed_by')
                                                    ->count();

            
            return response()->json(['profile' => $profile], 200);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'GET PROFILE FAIL'], 500);
        }
    }
}