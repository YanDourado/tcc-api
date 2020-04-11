<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use App\TCC;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try
        {
            Log::debug('USER', $request->all());
            $authUser = Auth::user();

            if($authUser->id != $request->input('id'))
            {
                return response()->json('Anouthorized', 401);
            }

            $user = User::updateOrCreate(
                $request->only('id'),
                $request->except('id')
            );

            return response()->json(['user' => $user], 200);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'USER_UPDATE_FAILED'], 500);
        }
    }

}