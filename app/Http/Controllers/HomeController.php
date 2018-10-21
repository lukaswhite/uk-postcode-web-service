<?php

namespace App\Http\Controllers;

use App\Rules\Postcode;
use Illuminate\Http\Request;
use Lukaswhite\Postcodes\Postcodes;
use Lukaswhite\UkPostcode\UkPostcode;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Show the "homepage"
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( )
    {
        return response( )->json( [
            'code'          =>  200,
            'description'   =>  'Postcodes API',
            'endpoints'      =>  [
                'get'           =>  [
                    'uri'           =>  url( 'get/:postcode' ),
                    'route_parameters'    =>  [
                        'postcode'  =>  [
                            'name'          =>  'Postcode',
                            'description'   =>  'The postcode to geocode',
                            'validation'    =>  'Must be a valid UK postcode',
                        ]
                    ],
                    'response'  =>  [
                        'format'    =>  'json',
                        'properties'    =>  [
                            'status'    =>  [
                                'format'        =>  'integer',
                                'description'   =>  'The HTTP status code',
                                'example'       =>  '200',
                            ],
                            'data'      =>  [
                                'format'    =>  'array',
                                'description'   =>  'Information about the postcode',
                                'data'  =>  [
                                    'postcode'    =>  [
                                        'format'        =>  'string',
                                        'description'   =>  'The requested postcode, properly formatted',
                                        'example'       =>  'SW1A 2AA',
                                    ],
                                    'outcode'    =>  [
                                        'format'        =>  'string',
                                        'description'   =>  'The outcode of the postcode',
                                        'example'       =>  'SW1A',
                                    ],
                                    'inward_code'    =>  [
                                        'format'        =>  'string',
                                        'description'   =>  'The inward code of the postcode',
                                        'example'       =>  '2AA',
                                    ],
                                    'sector'    =>  [
                                        'format'        =>  'string',
                                        'description'   =>  'The postcode sector',
                                        'example'       =>  'SW1A 2',
                                    ],
                                    'coordinates'   => [
                                        'type'          =>  'array',
                                        'description'   =>  'The co-ordinates of the postcode; i.e. lat/lng',
                                        'data'  =>  [
                                            'latitude'  =>  [
                                                'format'        =>  'float',
                                                'description'   =>  'The latitude',
                                                'example'       =>  '51.50354',
                                            ],
                                            'longitude'  =>  [
                                                'format'        =>  'float',
                                                'description'   =>  'The longitude',
                                                'example'       =>  '-0.127695',
                                            ]
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ],
            ],
        ] );

    }
}
