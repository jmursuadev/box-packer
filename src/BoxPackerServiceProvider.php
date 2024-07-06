<?php

namespace Jmursuadev\BoxPacker;

use Illuminate\Support\ServiceProvider;
use Jmursuadev\BoxPacker\Box;
use Jmursuadev\BoxPacker\BoxPacker;

class BoxPackerServiceProvider extends ServiceProvider
{

    protected $routeFilePath = '/routes/boxpacker/base.php';

    public function register()
    {
        $this->app->bind(BoxPacker::class, function () {
            // read json file
            $boxes = json_decode(file_get_contents(__DIR__ . '/data/boxes.json'), true);
            $boxes = array_map(function ($box) {
                return new Box(
                    $box['name'],
                    $box['length'],
                    $box['width'],
                    $box['height'],
                    $box['weight_limit']
                );
            }, $boxes);

            return new BoxPacker(collect($boxes));
        });

        $this->loadRoutes();

        $this->loadHelpers();
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'boxpacker');

        // If you need to publish views
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/boxpacker'),
            __DIR__ . '/public/build' => public_path('vendor/jmursuadev/boxpacker'),
        ]);
    }

    protected function loadHelpers()
    {
        foreach (glob(__DIR__ . '/helpers.php') as $filename) {
            require_once $filename;
        }
    }

    protected function loadRoutes()
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__ . $this->routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        if (file_exists(base_path() . $this->routeFilePath)) {
            $routeFilePathInUse = base_path() . $this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }
}
