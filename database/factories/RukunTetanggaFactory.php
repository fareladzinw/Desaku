<?php

namespace Database\Factories;

use App\Models\RukunTetangga;
use Illuminate\Database\Eloquent\Factories\Factory;

class RukunTetanggaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RukunTetangga::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => "RT 1",
            'rw_id' => 1,
            'desa_id' => 1,
        ];
    }
}
