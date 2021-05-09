<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'point1', 'point2'
    ];

    public function getDistance($formula) {
        $point1 = $this->point1;
        $point2 = $this->point2;

        $distance = [];

        switch ($formula) {
            case "haversine":
                // START: Haversine Formula
                $earth_radius = 6371;  // earth radius in km

                $delta_lat = deg2rad($point2['lat'] - $point1['lat']);
                $delta_lng = deg2rad($point2['lng'] - $point1['lng']);

                $a = sin($delta_lat / 2) * sin($delta_lat / 2) + cos(deg2rad($point1['lat'])) * cos(deg2rad($point2['lat'])) * sin($delta_lng / 2) * sin($delta_lng / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

                $distance = [
                    "f"     => "haversine",
                    "m"     => $earth_radius * $c * 1000, // 1 km = 1000 m
                    "km"    => $earth_radius * $c, // distance in km by default because earch radius was in km
                    "mi"    => $earth_radius * $c * 0.621371192 // 1 km = 0.621371192 mile
                ];
                // END: Haversine Formula

            default:
                // START: Spherical Law of Cosines
                $degrees = rad2deg(
                    acos(
                                (sin(deg2rad($point1['lat'])) * sin(deg2rad($point2['lat']))) 
                            +   (cos(deg2rad($point1['lat'])) * cos(deg2rad($point2['lat'])) * cos(deg2rad($point1['lng'] - $point2['lng'])))
                        )
                );

                $distance = [
                    "f"     => "arccosine",
                    "m"     => $degrees * 111133.84, // 1 degree = 111133.84 m, based on the average diameter of the Earth (12,735,000 m)
                    "km"    => $degrees * 111.13384, // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
                    "mi"    => $degrees * 69.05482 // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
                ];
                // END: Spherical Law of Cosines

                break;
        }

        return $distance;
    }
}
