<?php

namespace App\Http\Controllers;

use App\Pair;

use Illuminate\Http\Request;

class PointController extends Controller
{
    public function computeDistances(Request $request) {
        // validate parameters
        $this->validate($request, [
            'points' => 'required|array'
        ]);

        $points = $request->points;
        
        // START: Pair the points from the array
        $pairs = [];
        foreach($points as $p1) {
            foreach($points as $p2) {
                if (isset($p1['lat']) && isset($p1['lng']) && isset($p2['lat']) && isset($p2['lng']) && ($p1['lat'] != $p2['lat'] || $p1['lng'] != $p2['lng'])) {
                    $pair = new Pair([
                        "point1"    => [
                            "name"      => isset($p1['name']) ? $p1['name'] : null,
                            "lat"       => $p1["lat"],
                            "lng"       => $p1["lng"]
                        ],
                        "point2"    => [
                            "name"      => isset($p2['name']) ? $p2['name'] : null,
                            "lat"       => $p2["lat"],
                            "lng"       => $p2["lng"]
                        ]
                    ]);

                    // compute the distance between 2 points here
                    $pair->distance = $pair->getDistance();

                    $pairs[] = $pair;
                }
            }
        }
        // END: Pair the points from the array

        return [
            "status"    => "OK",
            "distances" => $pairs
        ];
    }
}
