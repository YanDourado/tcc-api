<?php

namespace App\Models;

use Auth;
use App\TCC;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'secret', 'user_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'secret',
    ];

    /**
     * Get a listing of the resource.
     *
     * @param  Array  $request
     * @return App\Models\Camera $cameras
     */
    public static function GetCamerasCL(array $request = null)
    {
        try
        {
            $user = Auth::user();

            $cameras = self::select('cameras.*');

            $cameras = $cameras->where('user_id', '=', $user->id);

            if((isset($request['code']) && $request['code']) && (isset($request['secret']) && $request['secret']))
            {
                $cameras = self::where('code', '=', $request['code'])
                                    ->where('secret', '=', $request['secret'])
                                    ->first();
            }

            return $cameras; 
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return null;
        }
    }

}