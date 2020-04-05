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
        'description', 'code', 'secret', 'thumbnail', 'video_url', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'secret',
    ];

    public function User()
    {
        return $this->belongsToMany('App\Models\User', 'properties', 'id', 'user_id');
    }

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

            $cameras = self::select('cameras.*')
                        ->leftJoin('users', 'properties.user_id', '=', 'users.id');

            $cameras = $cameras->where('users.id', '=', $user->id);

            if(isset($request['description']) && $request['description'])
            {
                $description = $request['description'];
                $cameras = $cameras->where('cameras.description', 'LIKE', ["%{$description}%"]);
            }

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