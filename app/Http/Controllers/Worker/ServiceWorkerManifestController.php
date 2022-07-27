<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class ServiceWorkerManifestController extends Controller
{

    public function __invoke($worker_hash)
    {
        $worker = Worker::where('login', $worker_hash)->first();
        return [
            'name' => 'Worker '.$worker->fullName(),
            "short_name" => "Worker ".$worker->fullName(),
            "start_url" => url("/worker/".$worker_hash."/login"),
            "background_color" => "#ffffff",
            "description" => "Worker app",
            "display" => "fullscreen",
            "scope" => "/worker/",
            "theme_color" => "#ffffff",
            "id" => "worker.nu.app",
            'icons' => [
                0 => [
                  'src' => '/img/pwa/android/android-launchericon-512-512.png',
                  'sizes' => '512x512',
                ],
                1 => [
                  'src' => '/img/pwa/android/android-launchericon-192-192.png',
                  'sizes' => '192x192',
                ],
                2 => [
                  'src' => '/img/pwa/android/android-launchericon-144-144.png',
                  'sizes' => '144x144',
                ],
                3 => [
                  'src' => '/img/pwa/android/android-launchericon-96-96.png',
                  'sizes' => '96x96',
                ],
                4 => [
                  'src' => '/img/pwa/android/android-launchericon-72-72.png',
                  'sizes' => '72x72',
                ],
                5 => [
                  'src' => '/img/pwa/android/android-launchericon-48-48.png',
                  'sizes' => '48x48',
                ],
                6 => [
                  'src' => '/img/pwa/ios/16.png',
                  'sizes' => '16x16',
                ],
                7 => [
                  'src' => '/img/pwa/ios/20.png',
                  'sizes' => '20x20',
                ],
                8 => [
                  'src' => '/img/pwa/ios/29.png',
                  'sizes' => '29x29',
                ],
                9 => [
                  'src' => '/img/pwa/ios/32.png',
                  'sizes' => '32x32',
                ],
                10 => [
                  'src' => '/img/pwa/ios/40.png',
                  'sizes' => '40x40',
                ],
                11 => [
                  'src' => '/img/pwa/ios/50.png',
                  'sizes' => '50x50',
                ],
                12 => [
                  'src' => '/img/pwa/ios/57.png',
                  'sizes' => '57x57',
                ],
                13 => [
                  'src' => '/img/pwa/ios/58.png',
                  'sizes' => '58x58',
                ],
                14 => [
                  'src' => '/img/pwa/ios/60.png',
                  'sizes' => '60x60',
                ],
                15 => [
                  'src' => '/img/pwa/ios/64.png',
                  'sizes' => '64x64',
                ],
                16 => [
                  'src' => '/img/pwa/ios/72.png',
                  'sizes' => '72x72',
                ],
                17 => [
                  'src' => '/img/pwa/ios/76.png',
                  'sizes' => '76x76',
                ],
                18 => [
                  'src' => '/img/pwa/ios/80.png',
                  'sizes' => '80x80',
                ],
                19 => [
                  'src' => '/img/pwa/ios/87.png',
                  'sizes' => '87x87',
                ],
                20 => [
                  'src' => '/img/pwa/ios/100.png',
                  'sizes' => '100x100',
                ],
                21 => [
                  'src' => '/img/pwa/ios/114.png',
                  'sizes' => '114x114',
                ],
                22 => [
                  'src' => '/img/pwa/ios/120.png',
                  'sizes' => '120x120',
                ],
                23 => [
                  'src' => '/img/pwa/ios/128.png',
                  'sizes' => '128x128',
                ],
                24 => [
                  'src' => '/img/pwa/ios/144.png',
                  'sizes' => '144x144',
                ],
                25 => [
                  'src' => '/img/pwa/ios/152.png',
                  'sizes' => '152x152',
                ],
                26 => [
                  'src' => '/img/pwa/ios/167.png',
                  'sizes' => '167x167',
                ],
                27 => [
                  'src' => '/img/pwa/ios/180.png',
                  'sizes' => '180x180',
                ],
                28 => [
                  'src' => '/img/pwa/ios/192.png',
                  'sizes' => '192x192',
                ],
                29 => [
                  'src' => '/img/pwa/ios/256.png',
                  'sizes' => '256x256',
                ],
                30 => [
                  'src' => '/img/pwa/ios/512.png',
                  'sizes' => '512x512',
                ],
                31 => [
                  'src' => '/img/pwa/ios/1024.png',
                  'sizes' => '1024x1024',
                ],
            ],
            // "icons" => [
            //     [
            //         "src" => "/img/custom_icon72x72.png",
            //         "sizes"=> "72x72",
            //         "type"=> "image/png"
            //     ],
            //     [
            //         "src"=> "/img/logo/icon-192x192.png",
            //         "sizes"=> "192x192",
            //         "type"=> "image/png"
            //     ],
            //     [
            //         "src" => "/img/logo/icon-256x256.png",
            //         "sizes"=> "256x256",
            //         "type" => "image/png"
            //     ],
            //     [
            //         "src" => "/img/logo/icon-384x384.png",
            //         "sizes" => "384x384",
            //         "type" => "image/png"
            //     ],
            //     [
            //         "src" => "/img/logo/icon-512x512.png",
            //         "sizes" => "512x512",
            //         "type"=> "image/png"
            //     ],
            // ]
        ];
    }
}



