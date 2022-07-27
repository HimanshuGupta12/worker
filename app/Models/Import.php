<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

const A = 0;
const B = 1;
CONST C = 2;
CONST D = 3;
CONST E = 4;
CONST F = 5;
CONST G = 6;
CONST H = 7;
CONST I = 8;
CONST J = 9;
CONST K = 10;
CONST L = 11;
CONST M = 12;
CONST N = 13;
CONST O = 14;
CONST P = 15;
CONST Q = 16;
CONST R = 17;
CONST S = 18;
CONST T = 19;
CONST U = 20;
CONST V = 21;
CONST W = 22;
CONST X = 23;
CONST Y = 24;
CONST Z = 25;

class Import
{
    public static function importImages()
    {
        $tools = Tool::get();
        $tools->load('company');

        foreach ($tools as $k => $tool) {
            $images = $tool->images;
            if (!$images) {
                continue;
            }

            $new_images = [];
            foreach ($images as $image) {
                if (!self::isRemote($image)) {
                    continue 2;
                }

                $tmp_path = storage_path('tmp.jpg');
                $image = str_replace('?dl=0', '?dl=1', $image);
                \Image::make($image)->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($tmp_path, 95);
                $new_path = $tool->company->id . '/tools/' . Str::random(40) . '.jpg';
                \Illuminate\Support\Facades\Storage::put($new_path, file_get_contents($tmp_path));

                $new_images[] = $new_path;
            }
            if (empty($new_images)) {
                die('what?');
            }
            $tool->images = $new_images;
            $tool->save();
            echo $k, ' ', $tool->id, "<br>\n";
        }
    }

    private static function isRemote(string $url): bool
    {
        return Str::contains($url, ['dropbox.com', 'worker.nu']);
    }

    public static function import()
    {
//        $company = Company::findOrFail(4);

//        $company = Company::create([
//            'name' => 'Mega corp',
//        ]);
//
//        User::create([
//            'name' => 'Mind',
//            'email' => 'admin@admin.com',
//            'password' => Hash::make('admin123'),
//            'company_id' => $company->id,
//        ]);

        self::importWorkers();
        self::importTools();
    }

    private static function importWorkers()
    {
        $path = storage_path('workers2.csv');
        $rows = self::csvToArray($path);
//echo '<pre>'; print_r($rows); exit;
        foreach ($rows as $row) {
            $phone = self::phoneIntoParts($row[6]);
            $company = self::getCompany($row[1]);
            Worker::create([
                'company_id' => $company->id,
                'first_name' => $row[3],
                'last_name' => $row[4],
                'phone_country' => $phone['country'],
                'phone_number' => $phone['phone'],
                'login' => $row[A],
            ]);
        }
    }

    private static function importTools()
    {
        $path = storage_path('tools2.csv');
        $rows = self::csvToArray($path);
//echo '<pre>'; print_r($rows); exit;
        $operational = \App\Models\ToolStatus::where('name', 'operational')->firstOrFail();
        foreach ($rows as $k => $row) {
            if ($k === 0) {
                continue;
            }

            $company = self::getCompany($row[E]);
            $category = self::getCategory($company, $row[F]);
            $possessor = self::getPossessor($company, $row[L]);

            $tool = \App\Models\Tool::create([
                'company_id' => $company->id,
                'company_tool_id' => $row[M],
                'name' => $row[C],
                'images' => self::getImages($row[I]),
                'model' => $row[D] ?? null,
                'price' => $row[G] ? (float)$row[G] : null,
                'purchased_at' => $row[H] ? Carbon::createFromFormat('d/m/Y', $row[H]) : null,
                'tool_category_id' => $category->id,
                'status_id' => $operational->id,
                'possessor_id' => $possessor->id,
                'possessor_type' => $possessor::class,
            ]);

            $legacy_qr = Qr::create([
                'legacy_qr' => $row[B],
                'tool_id' => $tool->id,
            ]);
        }
    }

    private static function getCompany(string $name): Company
    {
        $id = [
            'Ak Nord AS' => 7,
            'D A K Aps' => 8,
            'Ere Montasje AS' => 5,
            'HB Entreprenør As' => 9,
            'MB LIRMESTA' => 10,
            'ØKØ AS' => 6,
        ][$name];

        return Company::findOrFail($id);
    }

    private static function getImages($csv_images): array|null
    {
        if (!$csv_images) {
            return null;
        }

        $images = explode(',', $csv_images);
        $images = array_map('trim', $images);

        return $images;
    }

    private static function getPossessor($company, $name)
    {
        $name = trim($name);

        if (in_array($name, ['Valhojs Alle Storage', 'Mini lager 324', 'Sandelys', 'Grazas', '200,00 kr.', 'Sandėlys'])) {
            return $storage = self::getStorage($company, $name);
        }

        return $worker = self::getWorker($company, $name);
    }

    private static function getCategory($company, $name) {
        $name = trim($name);
        $category = $company->toolCategories()->where('name', $name)->first();
        if (!$category) {
            $category = \App\Models\ToolCategory::createCategory($company, $name);
        }

        return $category;
    }

    private static function getStorage(Company $company, string $name, string $address = null)
    {
        $storage = Storage::where('name', $name)->first();
        if (!$storage) {
            $storage = Storage::createStorage($company, $name, $address);
        }
        return $storage;
    }

    private static function getWorker(Company $company, string $name): Worker
    {
        $name = preg_replace('/\s+/', ' ', $name);
        $tmp = explode(' ', $name);

        if (!isset($tmp[1])) {
            throw new \Exception($name);
        }

        $worker = Worker::where('first_name', $tmp[0])->where('last_name', $tmp[1])->first();
        if (!$worker) {
            throw new \Exception($name);
        }
//        if (!$worker) {
//            $worker = Worker::create([
//                'company_id' => $company->id,
//                'first_name' => '',
//                'last_name' => $name,
//                'phone_country' => '?',
//                'phone_number' => '?',
//            ]);
//        }
        return $worker;
    }

    private static function getStatus($old_name) {

        $name = [
            'In storage' => 'operational',
            'In use' => 'operational',
            'Broken',
        ];


        return \App\Models\ToolStatus::where('name', $name)->findOrFail();
    }

    private static function arrayColumn(array $array, int $key) {
        $data = [];
        foreach ($array as $row) {
            $data[(string)$row[$key]] = true;
        }
        return array_keys($data);
    }

    private static function csvToArray(string $path): array
    {
        $string = file_get_contents($path);
        $lines = explode("\n", $string);
        $data = [];
        unset($lines[0]);
        foreach ($lines as $line) {
            if ($line === '') {
                continue;
            }
            $tmp_data = str_getcsv($line, ';');
            $tmp_data = array_map('trim', $tmp_data);
            if (empty($tmp_data[A]) && empty($tmp_data[B]) && empty($tmp_data[C]) && empty($tmp_data[D])) {
                echo '<pre>'; print_r($tmp_data);
                continue; // skip empty rows
            }
            $data[] = $tmp_data;
        }

        return $data;
    }

    private static function phoneIntoParts(string $phone): array
    {
        $phone = trim($phone);

        $starts = [370, 45, 47];
        $valid_start = null;

        foreach ($starts as $start) {
            if (Str::startsWith($phone, $start)) {
                $valid_start = $start;
                break;
            }
        }
        if (!$valid_start) {
            throw new \Exception($phone);
        }

        return [
            'country' => $start,
            'phone' => substr($phone, strlen($start))
        ];
    }
}
