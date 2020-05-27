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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['CreationDateF', 'VisualizationDateF'];

    /**
     * Get the alert visualization date formatted
     *
     * @return bool
     */
    public function getVisualizationDateFAttribute()
    {
        return $this->viewed_at ? date('d/m/Y H:i:s', strtotime($this->viewed_at)) : null;
    }

    /**
     * Get the alert date formatted
     *
     * @return bool
     */
    public function getCreationDateFAttribute()
    {
        return date('d/m/Y H:i:s', strtotime($this->created_at));
    }

    public function Camera()
    {
        return $this->hasOne('App\Models\Camera', 'id', 'camera_id');
    }

    public function User()
    {
        return $this->hasOneThrough('App\Models\User', 'App\Models\Camera', 'user_id', 'id', 'camera_id');
    }

}