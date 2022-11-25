<?php

use Illuminate\Support\Facades\DB;

function studioNumber($schedule_id)
{
	$studio = DB::table('studios')
		->where('id', $schedule_id)->first();
	return $studio->studio_number;
}

function seatReady($schedule_id)
{
	$order_item = DB::table('order_items')
		->where('movie_schedule_id', $schedule_id)->get();

	$total = 0;
	foreach($order_item as $val){
		$total += $val->qty;
	}
	
	$studio_id = DB::table('movie_schedules')
		->where('id', $schedule_id)
		->first()->studio_id;

	$seatCapacity = DB::table('studios')
		->where('id', $studio_id)
		->first()->seat_capacity;

	$seatRemaining = $seatCapacity - $total;
	return $seatRemaining;
}

?>