<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\Tag;
use App\Models\Movie;
use App\Models\MovieTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call( function(){
            $response = Http::get('https://api.themoviedb.org/3/movie/now_playing?api_key=f2488b05c329e7b0da9f8d32fefdee3b&language=en-US&page=1');
            $response = json_decode($response, true);
            $movieList = [];
            $movieTag = [];

            // proses ambil movie
            foreach($response['results'] as $value_movie){
                $movie['id'] = $value_movie['id'];
                $movie['title'] = $value_movie['original_title'];
                $movie['overview'] = $value_movie['overview'];
                $movie['poster'] = 'https://api.themoviedb.org' . $value_movie['poster_path'];
                $movieList[] = $movie;

                // proses tag
                foreach($value_movie['genre_ids'] as $value_tag){
                    $tag['tag_id'] = $value_tag;
                    $tag['movie_id'] = $value_movie['id'];

                    $movie_tag = MovieTag::where('tag_id', $tag['tag_id'])->where('movie_id', $tag['movie_id'])->get();
                    if($movie_tag->isEmpty()){
                        $movieTag[] = $tag;
                    }

                }

            }

            // insert to db
            Log::info('berhasil ditambahkan : ' . Movie::insertOrIgnore($movieList) . ' movie list' );
            if($movieTag){
                Log::info('berhasil ditambahkan : ' . MovieTag::insertOrIgnore($movieTag) . ' tag movie');
            }

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
