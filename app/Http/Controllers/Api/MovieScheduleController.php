<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Tag;
use App\Models\Movie;
use App\Models\MovieTag;
use App\Models\MovieSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class MovieScheduleController extends Controller
{
    public function listMovies_notwork(Request $request)
    {
        try {

            $keyword = $request->keyword;
            $date = $request->date;
           
            // code below work
            $list_movie = Movie::with('tags:id,name','schedules:id,studio_id,movie_id,start_time,end_time,price')->whereHas('schedules', function($query) use ($date){
                $query->where('date', 'like', '%'.$date.'%')
                ->whereNotNull('id');
            })->where('title', 'like', '%'.$keyword.'%')->jsonPaginate(10);            

            // $paging_item = $pagination->items();
            // $paging_item_filter = [];
            // foreach ($paging_item as $key => $value) {
            //     $schedule = $value->schedule;
            //     foreach ($schedule as $key_schedule => $value_schedule) {
                    
            //         $schedule[$key_schedule]['studio_number'] = studioNumber($value_schedule->studio_number);
            //         $schedule[$key_schedule]['seat_remaining'] = seatReady($value_schedule->id);

            //         unset($schedule[$key_schedule]['studio_id']);
            //         unset($schedule[$key_schedule]['date']);
            //         unset($schedule[$key_schedule]['created_at']);
            //         unset($schedule[$key_schedule]['updated_at']);
            //         unset($schedule[$key_schedule]['deleted_at']);
            //     }

            //     unset($paging_item[$key]['schedule']);
            //     unset($paging_item[$key]['play_until']);
            //     unset($paging_item[$key]['created_at']);
            //     unset($paging_item[$key]['updated_at']);
            //     unset($paging_item[$key]['deleted_at']);
            //     $paging_item[$key]['schedule'] = $schedule;
            //     $paging_item_filter[] = $paging_item[$key]; 
            // }


            return response()->json([
                'status' => true,
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

    public function listMovies(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $date = $request->date;

            $list_movie = Movie::with('tags:id,name','schedules:id,studio_id,movie_id,start_time,end_time,price')->whereHas('schedules', function($query) use ($date){
                $query->where('date', 'like', '%'.$date.'%')
                ->whereNotNull('id');
            })->where('title', 'like', '%'.$keyword.'%')->paginate(10);

            $paging_item = $list_movie->items();
            $paging_item_filter = [];

            foreach ($paging_item as $key => $value) {
                $schedule = $value['schedules'];
                foreach ($schedule as $key_schedule => $value_schedule) {
                    $schedule[$key_schedule]['studio_number'] = studioNumber($value_schedule->studio_id);
                    $schedule[$key_schedule]['seat_remaining'] = seatReady($value_schedule->id);
                }
                $paging_item_filter[] = $paging_item[$key];
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'items' => $paging_item_filter,
                    'pagination' => [
                        'page' => $list_movie->currentPage(),
                        'per_page' => $list_movie->perPage(),
                        'total_items' => $list_movie->count(),
                        'total_pages' => $list_movie->lastPage(),
                        'prev_page_link' => $list_movie->previousPageUrl(),
                        'next_page_link' => $list_movie->nextPageUrl()
                    ]
                ],
                'message' => 'Data berhasil ditampilkan'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
