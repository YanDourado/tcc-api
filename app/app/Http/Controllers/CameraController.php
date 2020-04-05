<?php

namespace App\Http\Controllers;

use Auth;
use App\TCC;
use Validator;
use App\Models\Camera;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    /**
     * @var $rules
     */
    protected $rules = array('id' => 'required|exists:cameras,id',
                            'name' => 'required|string',
                            'address' => 'required|string',
                            'address_number' => 'required');

    /**
     * @var $messages
     */
    protected $messages = array('required' => 'Campo obrigatório.',
                                'string' => 'O campo :attribute tem que ser do tipo texto.',
                                'exists' => 'O :attribute não existe.');

    /**
     * @var $messages
     */
    protected $attributes = array('user' => 'usuário',
                                    'name' => 'nome da câmera',
                                    'endereço' => 'required|string',
                                    'número' => 'required',
                                    'description' => 'descrição da câmera');


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

            $validator = Validator::make($request->all(),
                                            array('code' => 'required', 'secret' => 'required'));

            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()], 409);
            }

            $camera = Camera::create($request->all());

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
            $validator = Validator::make($request->all(), 
                                            $this->rules,
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()], 409);
            }

            $camera = Camera::updateOrCreate(
                $request->only('id'),
                ['user_id' => Auth::user()->id] + $request->except('id')
            );

            return response()->json(['camera' => $camera], 200);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'CAMERA_REGISTRATION_FAILED'], 500);
        }
    }
}