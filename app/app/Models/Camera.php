<?php

namespace App\Models;

use Log;
use Auth;
use App\TCC;
use App\Models\CameraInfo;
use App\Models\BaseModel;

class Camera extends BaseModel
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

    public function User()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function CameraInfo()
    {
        return $this->hasOne('App\Models\CameraInfo', 'camera_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            // ... code here
        });

        self::created(function($model){
            
            $cameraInfo = CameraInfo::create(array('camera_id' => $model->id ));

        });

        self::updating(function($model){
            // ... code here
        });

        self::updated(function($model){
            // ... code here
        });

        self::deleting(function($model){
            // ... code here
        });

        self::deleted(function($model){
            // ... code here
        });
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
                                ->leftJoin('camera_info', 'cameras.id', '=', 'camera_info.camera_id');

            $cameras = $cameras->where('user_id', '=', $user->id);

            if(isset($request['name']) && $request['name'])
            {
                $name = $request['name'];
                $cameras = $cameras->where('camera_info.name', 'LIKE', ["%{$name}%"]);
            }

            if((isset($request['code']) && $request['code']) && (isset($request['secret']) && $request['secret']))
            {
                $cameras = self::where('code', '=', $request['code'])
                                    ->where('secret', '=', $request['secret'])
                                    ->whereNull('user_id')
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