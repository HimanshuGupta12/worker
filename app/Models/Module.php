<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    /*
     * Return modules type wise
     */
    public static function getModules() {
        $modules = [];
        $data = self::select('id', 'title', 'name', 'type')->get()->toArray();
        if(!empty($data)) {
            foreach($data as $d => $module) {
                $modules[$module['type']][] = $module;
            }
        }
        return $modules;
    }
}
