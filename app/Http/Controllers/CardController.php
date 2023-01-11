<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function game(Request $request)
    {
        return view('game');
    }

    public function availableCards(Request $request)
    {
        $cards1 = Card::whereNotIn('id', [$request->card2 ?? 0, $request->card3 ?? 0, $request->card4 ?? 0, $request->card5 ?? 0])->get();
        $cards2 = Card::whereNotIn('id', [$request->card1 ?? 0, $request->card3 ?? 0, $request->card4 ?? 0, $request->card5 ?? 0])->get();
        $cards3 = Card::whereNotIn('id', [$request->card1 ?? 0, $request->card2 ?? 0, $request->card4 ?? 0, $request->card5 ?? 0])->get();
        $cards4 = Card::whereNotIn('id', [$request->card1 ?? 0, $request->card2 ?? 0, $request->card3 ?? 0, $request->card5 ?? 0])->get();
        $cards5 = Card::whereNotIn('id', [$request->card1 ?? 0, $request->card2 ?? 0, $request->card3 ?? 0, $request->card4 ?? 0])->get();

        $response = $this->getrank($request);

        return response()->json([

            'card1' => $request->card1 ?? 0,
            'card2' => $request->card2 ?? 0,
            'card3' => $request->card3 ?? 0,
            'card4' => $request->card4 ?? 0,
            'card5' => $request->card5 ?? 0,

            'cards1' => $cards1,
            'cards2' => $cards2,
            'cards3' => $cards3,
            'cards4' => $cards4,
            'cards5' => $cards5,
            
            'rank' => $response[0],
            'details' => $response[1]
        ]);
    }

    public function getrank(Request $request)
    {
        $rank = $this->rank([
            $request->card1,
            $request->card2,
            $request->card3,
            $request->card4,
            $request->card5
        ]);

        $response = [0, 'No Card Selected'];
        switch ($rank) {
            case 1:
                $response = [1, 'Royal Flush'];
                break;

            case 2:
                $response = [2, 'Staight Flush'];
                break;

            case 3:
                $response = [3, 'Four Of A Kind'];
                break;

            case 4:
                $response = [4, 'Full House'];
                break;
            case 5:
                $response = [5, 'Flush'];
                break;
            case 6:
                $response = [6, 'Straight'];
                break;
            case 7:
                $response = [7, 'Three Of A Kind'];
                break;
            case 8:
                $response = [8, 'Two Pairs'];
                break;
            case 9:
                $response = [9, 'Pair'];
                break;
            case 10:
                $response = [10, 'High Card'];
                break;

            default:
                $response = [0, 'No Card Selected'];
                break;
        }
        return $response;
    }


    public function rank($cards = [])
    {
        if ((count($cards) <= 0) || (count($cards) >= 6)) return 0;

        $cards = Card::find($cards);

        $values = $cards->map(function ($item) {
            return $item->value;
        })->toArray();
        rsort($values);

        $suites = $cards->map(function ($item) {
            return $item->suite;
        })->toArray();
        $suites = array_unique($suites);

        #1 Royal Flush - A,K,Q,J,10 Of Same Suite
        $temp = [13, 12, 11, 10, 9];
        if ((count($values) == 5) && ($values == $temp) && (count($suites) == 1)) return 1;

        #2 Staight Flush - All 5 Cards In Squence
        if ((count($values) == 5) && ($values[0] == ($values[4] + 4)) && (count($suites) == 1)) return 2;

        #3 Four Of A Kind
        if ((count($values) >= 4)) {
            if ($values[0] == $values[3]) return 3;
            if ((count($values) == 5) && ($values[1] == $values[4])) return 3;
        }

        #4 Full House - Three Of A Kind With A Pair
        if (count($values) == 5) {
            $condition1 = ($values[0] == $values[2] && $values[3] == $values[4]);
            $condition2 = ($values[0] == $values[1] && $values[2] == $values[4]);
            if ($condition1 || $condition2) return 4;
        }

        #5 Flush - All 5 From Same Suite
        if ((count($values) == 5) && (count($suites) == 1)) return 5;

        #6 Straight - All 5 In Sequence From Any Suite
        if ((count($values) == 5) && ($values[0] == ($values[4] + 4))) return 6;

        #7 Three Of A Kind
        if (count($values) >= 3) {
            if ($values[0] == ($values[2])) return 7;
            if ((count($values) >= 4) && ($values[1] == ($values[3]))) return 7;
            if ((count($values) == 5) && ($values[2] == ($values[4]))) return 7;
        }

        #8 Two Pairs
        if (count($values) >= 4) {
            if (($values[0] == $values[1]) && ($values[2] == $values[3])) return 8;
            if ((count($values) == 5)) {
                $con1 = ($values[1] == $values[2]) && ($values[3] == $values[4]);
                $con2 = ($values[0] == $values[1]) && ($values[3] == $values[4]);
                if ($con1 || $con2) return 8;
            }
        }

        #9 One Pair
        if (count($values) >= 2) {
            if (($values[0] == $values[1])) return 9;
            if ((count($values) >= 3) && ($values[1] == $values[2])) return 9;
            if ((count($values) >= 4) && ($values[2] == $values[3])) return 9;
            if ((count($values) == 5) && ($values[3] == $values[4])) return 9;
        }

        # High Card
        if (count($values) >= 1) return 10;
    }
}
