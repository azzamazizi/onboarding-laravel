<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;

use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MovieSchedule;
use App\Models\Movie;
use App\Models\MovieTag;
use App\Models\Tag;
use App\Models\Studio;
use JWTAuth;


class OrderController extends Controller
{
    public function orderPreview(Request $request)
    {
        try {

            $data_order = $request->all();

            if(empty($data_order['items'])){
                return response()->json([
                    'success' => false,
                    'message' => 'Data variabel kosong'
                ], 200);
            }
            
            $movieSchedule = [];
            $total_qty = 0;
            $total_price = 0;
            $price = 0;

            $refOut = collect();
            foreach ($data_order['items'] as $value) {
                $newData = movieSchedule::where('id', $value['movie_schedule_id'])->with('movies:id,title')->first(['movie_schedules.start_time','movie_schedules.end_time','movie_schedules.movie_id','movie_schedules.price','movie_schedules.studio_id']);

                if(empty($newData)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Movie schedule is not available'
                    ], 200);
                }

                $refOut->push($newData);
                $newData->qty = $value['qty'];
                $newData->sub_total_price = $value['qty'] * $newData->price;
                
                // this code below work
                // $newData->studio_number = Studio::find($newData->studio_id)->studio_number;
                $newData->studio_number = studioNumber($newData->studio_id);
                $price += $newData->price * $value['qty'];
                $total_qty += $value['qty'];
            }

            $movieSchedule['total_qty'] = $total_qty;
            $movieSchedule['total_price'] = $price;
            $movieSchedule['item_details'] = $refOut->flatten();

            return response()->json([
                'success' => true,
                'message' => 'Movie Schedule is available',
                'data' => $movieSchedule
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'success' => true,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function orderCheckout(Request $request)
    {
        try {

            $data_order = $request->all();

            if(empty($data_order['items'])){
                return response()->json([
                    'success' => false,
                    'message' => 'Data variabel kosong'
                ], 200);
            }
            
            $count_items = count($data_order['items']);
            $movieSchedule = [];
            $total_qty = 0;
            $total_price = 0;
            $price = 0;

            $movieScheduleId = [];
            $movieScheduleQty = [];
            $movieSchedulePrice = [];
            $movieScheduleSubTotalPrice = [];

            $refOut = collect();
            foreach ($data_order['items'] as $value) {   
                $newData = movieSchedule::where('id', $value['movie_schedule_id'])->with('movies:id,title')->first(['movie_schedules.start_time','movie_schedules.end_time','movie_schedules.movie_id','movie_schedules.price','movie_schedules.studio_id']);

                if(empty($newData)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Movie schedule is not available'
                    ], 200);
                }
                    
                $refOut->push($newData);
                $newData->qty = $value['qty'];
                $newData->sub_total_price = $value['qty'] * $newData->price;
                $newData->studio_number = Studio::find($newData->studio_id)->studio_number;
                $price += $newData->price * $value['qty'];
                $total_qty += $value['qty'];
                
                $movieScheduleId[] = $value['movie_schedule_id'];
                $movieScheduleQty[] = $value['qty'];
                $movieSchedulePrice[] = $newData->price;
                $movieScheduleSubTotalPrice[] = $newData->sub_total_price;
            }

            $movieSchedule['total_qty'] = $total_qty;
            $movieSchedule['total_price'] = $price;
            $movieSchedule['item_details'] = $refOut->flatten();

            $checkout = Order::create([
                'user_id' => JWTAuth::user()->id,
                'payment_method' => 'cash',
                'total_item_price' => $price
            ]);

            for ($i=0; $i < $count_items ; $i++) { 
                $checkout_items = OrderItem::create([
                    'order_id' => $checkout->id,
                    'movie_schedule_id' => $movieScheduleId[$i],
                    'qty' => $movieScheduleQty[$i],
                    'price' => $movieSchedulePrice[$i],
                    'sub_total_price' => $movieScheduleSubTotalPrice[$i]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Checkout success',
                'data' => $movieSchedule
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'success' => true,
                'message' => $th->getMessage()
            ], 400);
        }
    }
}
