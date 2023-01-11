<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 2; $i <= 10; $i++) {
            $card = Card::create([
                'color' => 'black',
                'suite' => 'spades',
                'no' => $i . '',
                'value' => $i - 1
            ]);
        }

        for ($i = 10; $i >= 2; $i--) {
            Card::create([
                'color' => 'red',
                'suite' => 'diamonds',
                'no' => $i . '',
                'value' => $i - 1
            ]);
        }

        for ($i = 10; $i >= 2; $i--) {
            Card::create([
                'color' => 'black',
                'suite' => 'clubs',
                'no' => $i . '',
                'value' => $i - 1
            ]);
        }

        for ($i = 10; $i >= 2; $i--) {
            Card::create([
                'color' => 'red',
                'suite' => 'hearts',
                'no' => $i . '',
                'value' => $i - 1
            ]);
        }

        $suites = ['spades', 'clubs'];
        foreach ($suites as $suite) {
            Card::create([
                'color' => 'black',
                'suite' => $suite,
                'no' => 'Ace',
                'value' => 13
            ]);

            Card::create([
                'color' => 'black',
                'suite' => $suite,
                'no' => 'King',
                'value' => 12
            ]);

            Card::create([
                'color' => 'black',
                'suite' => $suite,
                'no' => 'Queen',
                'value' => 11
            ]);

            Card::create([
                'color' => 'black',
                'suite' => $suite,
                'no' => 'Jack',
                'value' => 10
            ]);
        }

        $suites = ['hearts', 'diamonds'];
        foreach ($suites as $suite) {
            Card::create([
                'color' => 'red',
                'suite' => $suite,
                'no' => 'Ace',
                'value' => 13
            ]);

            Card::create([
                'color' => 'red',
                'suite' => $suite,
                'no' => 'King',
                'value' => 12
            ]);

            Card::create([
                'color' => 'red',
                'suite' => $suite,
                'no' => 'Queen',
                'value' => 11
            ]);

            Card::create([
                'color' => 'red',
                'suite' => $suite,
                'no' => 'Jack',
                'value' => 10
            ]);
        }
    }
}
