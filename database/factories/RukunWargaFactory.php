<?php

namespace Database\Factories;

use App\Models\RukunWarga;
use Illuminate\Database\Eloquent\Factories\Factory;

class RukunWargaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RukunWarga::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => "RW 1",
            'desa_id' => 1, 
        ];
    }
}
