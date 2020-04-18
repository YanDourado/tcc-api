<?php

namespace App\Http\Controllers;

use Auth;
use App\TCC;
use Validator;
use App\Models\Alert;
use App\Models\Camera;
use App\Events\AlertEvent;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * @var $rules
     */
    protected $rules = array('camera_id' => 'required|exists:cameras,id',
                                'image' => 'required|mimes:jpeg,png');

    /**
     * @var $messages
     */
    protected $messages = array('required' => 'Campo :attribute obrigatório.',
                                'exists' => 'O :attribute não existe.');

    /**
     * @var $messages
     */
    protected $attributes = array('camera_id' => 'Câmera',
                                    'image' => 'Imagem capturada');


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
            $user = Auth::user();
            
            $alerts = $user->Alerts;

            $alerts->load('Camera');

            return response()->json(['alerts' => $alerts], 200);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => ''], 500);
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
            
            $validator = Validator::make($request->all(), 
                                            $this->rules,
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()], 409);
            }

            $file = $request->file('image');

            $path = 'upload/alerts/';

            $filePath = Alert::uploadImage($file, $path);

            $alert = Alert::create([
                'camera_id' => $request->input('camera_id'),
                'image_url' => $filePath,
                'has_humna' => $request->input('has_human')
            ]);

            $camera = Camera::find($request->input('camera_id'));

            event(new AlertEvent($camera, $alert));

            return response()->json(['alert' => $alert], 201);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => $e], 500);
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

            
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => ''], 500);
        }
    }
}