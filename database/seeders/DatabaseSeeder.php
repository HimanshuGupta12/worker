<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Qr;
use App\Models\Storage;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\ToolStatus;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call([
//            UserSeeder::class,
//        ]);

        ToolStatus::insert([
            ['name' => 'operational', 'needs_description' => false],
            ['name' => 'broken', 'needs_description' => true],
            ['name' => 'lost', 'needs_description' => true],
            ['name' => 'in service', 'needs_description' => true],
            ['name' => 'decommissioned', 'needs_description' => false],
        ]);

//        Company::factory(10)->create();
//
//        User::create([
//            'name' => 'Mantas',
//            'email' => 'admin@admin.com',
//            'password' => Hash::make('admin123'),
//            'company_id' => 10,
//        ]);
//
//        return;


//        \App\Models\User::factory(10)->create();

//
        $company = Company::create([
            'name' => 'Mega corp',
        ]);

        User::create([
            'name' => 'Mantas',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'company_id' => $company->id,
        ]);

        Worker::factory(10)->create([
            'company_id' => 1,
            'change_tool_status' => 1,
            'scan_to_storage' => 1,
            'inventory_storage' => 1,
            'see_company_tools' => 1,
        ]);

        Storage::factory(10)->create([
            'company_id' => 1
        ]);

        ToolStatus::insert([
            ['name' => 'operational', 'needs_description' => false],
            ['name' => 'broken', 'needs_description' => true],
            ['name' => 'lost', 'needs_description' => true],
            ['name' => 'in service', 'needs_description' => true],
            ['name' => 'decommissioned', 'needs_description' => false],
        ]);

        Tool::factory(10)->create([
            'company_id' => 1,
        ]);

        ToolCategory::factory(10)->create([
            'company_id' => 1,
        ]);

        Qr::create([
            'legacy_qr' => 'abc',
            'tool_id' => 3,
        ]);
    }
}
