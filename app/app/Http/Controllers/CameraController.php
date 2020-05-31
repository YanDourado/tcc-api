<?php

namespace App\Http\Controllers;

use Auth;
use App\TCC;
use Validator;
use App\Models\Camera;
use App\Models\CameraInfo;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    /**
     * @var $rules
     */
    protected $rules = array('camera_id' => 'required|exists:cameras,id',
                                'name' => 'required');

    /**
     * @var $messages
     */
    protected $messages = array('required' => 'Campo obrigatório.',
                                'string' => 'O campo :attribute tem que ser do tipo texto.',
                                'exists' => 'O :attribute não existe.');

    /**
     * @var $messages
     */
    protected $attributes = array('name' => 'nome');


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try
        {
            $cameras = Camera::GetCamerasCL($request->all());

            if($cameras)
            {
                $cameras = $cameras->orderBy('created_at', 'DESC')
                                        ->with('CameraInfo')
                                        ->get();
            }

            return response()->json(['cameras' => $cameras], 200);

        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'CAMERAS_LIST_FAILED'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try
        {
            $user = Auth::user();

            $file = $request->file('image');

            $validator = Validator::make($request->all(),
                                            array('code' => 'required', 'secret' => 'required', 'image' => 'mimes:jpeg,png'));

            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()], 409);
            }

            $camera = Camera::updateOrCreate($request->except('image'));

            if($file)
            {
                $path = 'upload/thumbnails/';
    
                $filePath = Camera::uploadImage($file, $path, 'T-');

                $cameraInfo = $camera->CameraInfo;

                $cameraInfo->thumbnail = $filePath;

                $cameraInfo->save();
            }

            return response()->json(['camera' => $camera], 201);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'CAMERA_REGISTRATION_FAILED'], 500);
        }
    }

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
            $user = Auth::user();

            $validator = Validator::make($request->all(), 
                                            $this->rules,
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()], 409);
            }

            $camera = Camera::find($request->input('camera_id'));

            if($camera->user_id && $camera->user_id != $user->id)
            {
                return response()->json(['errors' => 'Unauthorized'], 401);
            }
            else if($camera)
            {
                $camera->user_id = $user->id;

                $camera->status = $request->input('status');

                $camera->save();
            }

            $cameraInfo = $camera->CameraInfo;

            $cameraInfo = CameraInfo::updateOrCreate(
                $cameraInfo ? $cameraInfo->only('id') : ['id' => null],
                ['camera_id' => $camera->id] + $request->except('id')
            );

            $camera = Camera::where('id', '=', $request->input('camera_id'))
                                ->with('CameraInfo')
                                ->first();

            return response()->json(['camera' => $camera], 200);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'CAMERA_REGISTRATION_FAILED'], 500);
        }
    }
}