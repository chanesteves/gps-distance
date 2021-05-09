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

    public function getDistance() {
        $point1 = $this->point1;
        $point2 = $this->point2;

        // Calculate the distance in degrees
        // Spherical Law of Cosines
        $degrees = rad2deg(
                        acos(
                                    (sin(deg2rad($point1['lat'])) * sin(deg2rad($point2['lat']))) 
                                +   (cos(deg2rad($point1['lat'])) * cos(deg2rad($point2['lat'])) * cos(deg2rad($point1['lng'] - $point2['lng'])))
                            )
                    );

        return [
            "m"     => $degrees * 111133.84, // 1 degree = 111133.84 m, based on the average diameter of the Earth (12,735,000 m)
            "km"    => $degrees * 111.13384, // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
            "mi"    => $degrees * 69.05482 // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
        ];
    }
}
