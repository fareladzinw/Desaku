<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nik' => Str::random(14),
            'name' => "Admin",
            'jenis_kelamin' => 1,
            'role' => 4,
            'tempat_lahir' => Str::random(6),
            'tanggal_lahir' => Carbon::now(),
            'agama' => Str::random(6),
            'alamat' => Str::random(6),
            'no_telp' => Str::random(12),
            'username' => "admin",
            'password' => bcrypt('admin'),
            'rt_id' => null,
            'rw_id' => null,
            'desa_id' => null,
            'is_kepala' => 0,
            'status' => 0
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
