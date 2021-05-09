<?php

namespace Tests\Feature;

use App\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PointTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testComputeDistance()
    {
        $points = [
            [
                "name"  => "Legazpi City, Albay",
                "lat"   => 13.1212078,
                "lng"   => 123.6330878
            ],
            [
                "name"  => "Sorsogon City, Sorsogon",
                "lat"   => 12.971228,
                "lng"   => 123.8278376
            ],
            [
                "name"  => "Naga City, Camarines Sur",
                "lat"   => 13.644502,
                "lng"   => 123.205763
            ],
            [
                "name"  => "Makati City, Metro Manila",
                "lat"   => 14.5546336,
                "lng"   => 121.0156802
            ],
            [
                "name"  => "Taguig City, Metro Manila",
                "lat"   => 14.5136183,
                "lng"   => 121.0303829
            ],
            [
                "name"  => "Pasig City, Metro Manila",
                "lat"   => 14.5788423,
                "lng"   => 121.0461517
            ],
            [
                "name"  => "Mandaluyong, Metro Manila",
                "lat"   => 14.5844444,
                "lng"   => 121.0209957
            ],
            [
                "name"  => "Pasay City, Metro Manila",
                "lat"   => 14.5314227,
                "lng"   => 120.989434
            ]
        ];
        
        // get API keys
        $api_keys = Api::pluck('key')->toArray();
        if (count($api_keys) == 0) {
            $key = Str::random(60);

            // create permanent API token
            Api::create(["key" => Str::random(60)]);

            $api_keys[] = $key;
        }

        // START: test compute distance failed (no points)
        $response = $this->json('POST', '/api/points/compute-distances', [
            'key'       => $api_keys[0]
        ]);

        $response->assertStatus(422);
        // END: test compute distance failed (no points)

        // START: test compute distance failed (no key)
        $response = $this->json('POST', '/api/points/compute-distances', [
            'points'    => $points            
        ]);
        
        $response->assertStatus(403);
        // END: test compute distance failed (no key)

        // START: test compute distance success (haversine)
        $response = $this->json('POST', '/api/points/compute-distances', [
            'points'    => $points,
            'key'       => $api_keys[0]
        ]);

        $response->assertStatus(200);
        $this->assertEquals('OK', $response['status']);
        // END: test compute distance success (haversine)

        // START: test compute distance success (arccosine)
        $response = $this->json('POST', '/api/points/compute-distances', [
            'points'    => $points,
            'formula'   => 'arccosine',
            'key'       => $api_keys[0]
        ]);

        $response->assertStatus(200);
        $this->assertEquals('OK', $response['status']);
        // END: test compute distance success (arccosine)
    }
}
