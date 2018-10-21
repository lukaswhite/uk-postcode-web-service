<?php

namespace App\Http\Controllers;

use App\Rules\Postcode;
use Illuminate\Http\Request;
use Lukaswhite\UkPostcodeGeocoder\Service;
use Lukaswhite\UkPostcode\UkPostcode;

/**
 * Class PostcodesController
 *
 * @package App\Http\Controllers
 */
class PostcodesController extends Controller
{
    /**
     * The postcodes service
     *
     * @var Service
     */
    protected $service;

    /**
     * PostcodesController constructor.
     *
     * @param Service $service
     */
    public function __construct( Service $service )
    {
        $this->service = $service;
    }

    /**
     * Get (geocode) a postcode
     *
     * @param string $postcode
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get( $postcode, Request $request )
    {
        // Decode the postcode; e.g. SW1A%202AA becomes SW1A 2AA
        $postcode = urldecode( $postcode );

        // Inject the postcode into the request, so that we can validate it
        $request[ 'postcode' ] = $postcode;

        // Validate the request data
        $this->validate(
            $request,
            [
                'postcode'  =>  new Postcode( ),
            ]
        );

        // Create a postcode instance, so that we can pass a correctly formatted
        // postcode regardless of the format specified in the parameter.
        $postcode = new UkPostcode( $postcode );

        // Perform the lookup
        $coordinates = $this->service->get( $postcode );

        // If the postcode cannot be found, return the appropriate response
        if ( ! $coordinates ) {
            return response( )->json( [
                'code'                  =>  404,
                'error'                 =>  'not_found',
                'error_description'     =>  'The postcode could not be found.'
            ] );
        }

        // We're good; return the necessary information
        return response( )->json( [
            'code'      =>  200,
            'data'      =>  [
                'postcode'      =>  $postcode->formatted( ),
                'outcode'       =>  $postcode->getOutcode( ),
                'sector'        =>  $postcode->getSector( ),
                'inward_code'   =>  $postcode->getInwardCode( ),
                'coordinates'   =>  $coordinates,
            ],
        ] );

    }
}
