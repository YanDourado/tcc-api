<?php

namespace App\Models;

use Auth;
use App\TCC;
use Illuminate\Database\Eloquent\Model;

class CameraInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'camera_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'thumbnail', 'video_url', 'cep', 'address', 'address_number', 'camera_id',
    ];
}