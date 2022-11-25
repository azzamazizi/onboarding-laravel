<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Tag;
use App\Models\Movie;
use App\Models\MovieSchedule;
use App\Models\MovieTag;

class BackOfficeController extends Controller
{
    public function listTags()
    {
        try {
            $tag = Tag::orderBy('name', 'asc')->with('movie:title')->jsonPaginate(10);
            return response()->json([
                'success' => true,
                'data' => $tag,
                'message' => 'Data berhasil ditampilkan'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function storeMovieSchedule(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'movie_id' => 'required',
                'studio_id'=> 'required',
                'start_time'=> 'required',
                'end_time'=> 'required',
                'price'=> 'required',
                'date'=> 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Insert data failed',
                    'errors' => ([
                        $validator->errors()
                    ])
                ], 401);
            }

            $movie_schedule = MovieSchedule::create([
                'movie_id' => $request->movie_id,
                'studio_id' => $request->studio_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'price' => $request->price,
                'date' => $request->date
            ]);

            return response()->json([
                'success' => true,
                'data' => $movie_schedule,
                'message' => 'Insert movie schedule success'
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function listMovies()
    {
        try {
            $list_movie = Movie::with('tags:id,name')->jsonPaginate(10);
            
            return response()->json([
                'success' => true,
                'data' => $list_movie,
                'message' => 'Data berhasil ditampilkan'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateMovie(Request $request, $movie)
    {
        try {

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'overview' => 'required',
                // 'poster' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
                'play_until' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Update movie gagal',
                    'errors' => ([
                        $validator->errors()
                    ])
                ], 401);
            }

            $data_movie = Movie::find($movie);
            $data_movie->title = $request->title;
            $data_movie->overview = $request->overview;
            $data_movie->play_until = $request->play_until;
            $data_movie->save();          

            foreach($request['tags'] as $data_movie_tag){
                MovieTag::create([
                    'movie_id' => $movie,
                    'tag_id' => $data_movie_tag
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $data_movie,
                'message' => 'Update data berhasil'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
