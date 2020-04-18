<?php

namespace App\Models;

use Auth;
use App\TCC;
use App\Models\BaseModel;

class Alert extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alerts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'viewed_at', 'viewed_by', 'has_human', 'image_url', 'camera_id',
    ];

    public function Camera()
    {
        return $this->hasOne('App\Models\Camera', 'id', 'camera_id');
    }

    public function User()
    {
        return $this->hasOneThrough('App\Models\User', 'App\Models\Camera', 'user_id', 'id', 'camera_id');
    }

}