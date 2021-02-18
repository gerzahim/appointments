<?php

namespace Database\Factories;

use App\Models\Appoinment;
use App\Models\Analyst;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppoinmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appoinment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $analyst =  Analyst::all()->random();
        $service =  Service::all()->random();

        return [
            'date'       => $this->faker->date,
            'time'   => $this->faker->time,
            'name'       => $this->faker->name,
            'email'      => $this->faker->email,
            'phone'      => $this->faker->phoneNumber,
            'note'       => $this->faker->sentence(7),
            'status'     => 'reserved',
            'service_id' => $service->id,
            'analyst_id' => $analyst->id,
        ];
    }
}
