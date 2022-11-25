<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Studio;
use Illuminate\Support\Facades\Validator;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $studio = Studio::orderBy('id','asc')->get();
            return response()->json([
                'success' => true,
                'data' => $studio,
                'message' => 'Data berhasil ditampilkan'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'studio_number' => 'required',
                'seat_capacity'=> 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Insert data gagal',
                    'errors' => ([
                        $validator->errors()
                    ])
                ], 401);
            }

            $studio = Studio::create([
                'studio_number' => $request->studio_number,
                'seat_capacity' => $request->seat_capacity
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $studio,
                'message' => 'Insert data berhasil'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Studio $studio)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'studio_number' => 'required',
                'seat_capacity'=> 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Update data gagal',
                    'errors' => ([
                        $validator->errors()
                    ])
                ], 401);
            }

            // $studio = Studio::find($id);
            // $studio->studio_number = $request->studio_number;
            // $studio->seat_capacity = $request->seat_capacity;
            // $studio->save();

            $studio->update([
                'studio_number' => $request->studio_number,
                'seat_capacity' => $request->seat_capacity
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $studio,
                'message' => 'Update data berhasil'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $studio = Studio::findOrFail($id);
            $studio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil hapus data'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }        
    }

}
