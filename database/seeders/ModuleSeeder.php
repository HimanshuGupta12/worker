<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::truncate();
        $modules = array(
            [
                'title' => 'Hours report',
                'name' => 'hours',
                'route_names' => json_encode(['hours.index']),
                'type' => 'manager'
            ],
            [
                'title' => 'Tools',
                'name' => 'tools',
                'route_names' => json_encode(['tools.index', 'tools.create']),
                'type' => 'manager'
            ],
            [
                'title' => 'Workers',
                'name' => 'workers',
                'route_names' => json_encode(['workers.index', 'workers.create']),
                'type' => 'manager'
            ],
            [
                'title' => 'Projects',
                'name' => 'projects',
                'route_names' => json_encode(['projects.index', 'projects.create']),
                'type' => 'manager'
            ],
            [
                'title' => 'Clients',
                'name' => 'clients',
                'route_names' => json_encode(['clients.index', 'clients.create']),
                'type' => 'manager'
            ],
            [
                'title' => 'Register hours',
                'name' => 'worker_hours',
                'route_names' => json_encode(['worker.hours']),
                'type' => 'worker'
            ],
            /*[
                'title' => 'Tools',
                'name' => 'worker_tools',
                'route_names' => json_encode(['scan', 'worker.tools.index', 'worker.scan.inventory', 'worker.company-tools.index', 'worker.tools.scan-to-storage0']),
                'type' => 'worker'
            ],
            [
                'title' => 'Balance tools',
                'name' => 'balance_tools',
                'route_names' => json_encode(['worker.scan.inventory']),
                'type' => 'worker'
            ],
            [
                'title' => 'Balance storage',
                'name' => 'balance_storage',
                'route_names' => json_encode(['worker.inventory-storage-choose-storage']),
                'type' => 'worker'
            ],*/
            [
                'title' => 'Sickness',
                'name' => 'worker_sickness',
                'route_names' => json_encode(['worker.sickness']),
                'type' => 'worker'
            ],
            [
                'title' => 'Holidays',
                'name' => 'worker_holidays',
                'route_names' => json_encode(['worker.sickness']),
                'type' => 'worker'
            ],
        );
        foreach($modules as $m => $module) {
            Module::create($module);
        }
    }
}
