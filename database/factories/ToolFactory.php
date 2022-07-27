<?php

namespace Database\Factories;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToolFactory extends Factory
{
    private static $id = 0;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tool::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $id = ++self::$id;
//        return [
//            'company_tool_id' => $id,
//            'name' => self::randomToolName($id),
//            'price' => rand(1, 1000),
//        ];
        return [
            'company_tool_id' => $id,
            'name' => $this->faker->word,
            'price' => rand(1, 1000),
        ];
    }

    private static function randomToolName($id): string
    {
        $tools = [
            'saw',
            'hammer',
            'ladder',
            'gloves',
            'mallet',
            'chisel',
            'screwdriver',
            'wrench',
            'hand drill',
            'shovel',
            'allen key',
        ];
        return $tools[$id];
    }
}
