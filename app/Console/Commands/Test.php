<?php

namespace App\Console\Commands;

use App\Models\Route;
use App\Models\Stop;
use Illuminate\Console\Command;


class Test extends Command
{

    protected $signature = 'c:test';


    public function handle()
    {

        $routes = file_get_contents(storage_path('/routes.json'));

        $routes = json_decode($routes, true);


        $stops = file_get_contents(storage_path('/stops.json'));

        $stops = json_decode($stops, true);

        foreach ($stops as $stop) {
            $points = [$stop['geoData']['coordinates'][1], $stop['geoData']['coordinates'][0]];
            Stop::updateOrCreate([ 'id' => $stop['stop_id']],[
                'id' => $stop['stop_id'],
                'stop_name' => $stop['stop_name'],
                'points' => $points,
            ]);

        }


        foreach ($routes as $route) {

            $points = $route['geoData']['coordinates'][0];
            $points = array_map(fn($points) => [$points[1], $points[0]], $points);
            $cnt = count($points);
            $first = round($cnt / 10);
            $second = $cnt - $first;

            $buses = [$points[$first], $points[$second]];


            Route::create([
                'id' => $route['ID'],
                'route_name' => $route['RouteName'],
                'route_number' => $route['RouteNumber'],
                'points' => $points,
                'buses' => $buses,
            ]);

        }




    }


}
