<?php

namespace Database\Factories;

use App\Models\ToolCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToolCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ToolCategory::class;

    private static $nr = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
//        $names = ['Batteries', 'Drills', 'Shovels', 'Electronics'];
//
//        return [
//            'name' => $names[self::$nr++ % 3],
//        ];
        return [
            'name' => $this->faker->address,
        ];
    }
}
