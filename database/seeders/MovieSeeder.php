<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;


class MovieSeeder extends Seeder
{
    public function run()
    {
        $movies  = [
            [
                'title' => 'Interstellar',
                'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanitys survival.',
                'release_date' => '2014-11-07', 
                'is_featured' => true,
            ],
            [ 
                'title' => 'The Shawshank Redemption',
                'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'release_date' => '1994-09-14',
                'is_featured' => false,
            ],
            [ 
                'title' => 'Avengers',
                'description' => 'A lot of heroes wants to avenger their city.',
                'release_date' => '2021-02-17',
                'is_featured' => false,
            ],
        ];

        foreach ($movies as $movie) {
            Movie::create($movie);
        }
    }
}
