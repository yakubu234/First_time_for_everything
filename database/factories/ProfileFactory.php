<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $max = User::count();
        $min = ($max > 5) ? (($max -5) + 1) : 1;

        // Log::info($max);
        // Log::info($min);
        // Log::info($this->faker->unique()->numberBetween($min, $max));

        return [
            'user_id' => $this->faker->unique(true)->numberBetween($min, $max),
            'professional_summary' => 'pareag so much information to be supplied here by the user',
            'skills' => json_encode($this->randomWords()),
            'years_of_experience' => rand(1,10),
            'certifications' =>json_encode($this->randomWords()),
            'educations' => json_encode($this->randomWords()),
        ];
    }

    public function randomWords():array
    {
        $input = array(
            'random thing 1',
            'random thing 2',
            'random thing 3',
            'random thing 4',
            'random thing 5',
            'random thing 6',
            'random thing 7 ',
          );

          $keys = array_rand($input, 5);

          $result = [
            $input[$keys[0]],
            $input[$keys[1]],
            $input[$keys[2]],
            $input[$keys[3]]
          ];

          shuffle($result);
          return $result;
    }
}
