# UK Postcode Geocoder Web Service

A simple, self-hosted web service for geocoding UK postcodes, built atop my [UK Postcode Geocoder](https://github.com/lukaswhite/uk-postcode-geocoder) package.

It's based on Laravel's [Lumen](https://lumen.laravel.com) micro-framework.

## Basic Usage

Make an HTTP GET request like this:

```
/get/[POSTCODE]
```

For example:

```
/get/SW1A%202AA
```

You'll receive a JSON response that looks like this:

```
{
  "code": 200,
  "data": {
    "postcode": "SW1A 2AA",
    "outcode": "SW1A",
    "sector": "SW1A 2",
    "inward_code": "2AA",
    "coordinates": {
      "latitude": 51.50354,
      "longitude": -0.127695
    }
  }
}
```

That's it!

## Installation

1. Clone the package
2. Copy `.env.example` to `.env`
3. Follow the instructions in the [UK Postcode Geocoder](https://github.com/lukaswhite/uk-postcode-geocoder) README to install the data
4. Configure Nginx / Apache

### Notes

By default, it expects to find the database at the following path:

```
/storage/database/postcodes.sqlite
```

You may override this by setting the `DATABASE_DIR` and/or `DATABASE_FILENAME` environment variables in your `.env` file.

## Error Responses

If you provide an invalid postcode, you'll receive a `422` error that looks like this:

```
{
  "code": 422,
  "error": "validation",
  "error_description": "Validation failed",
  "data": {
    "postcode": [
      "Incorrectly formatted postcode."
    ]
  }
}
```

If the postcode cannot be found, you'll receive a `200` response, with the following body:

```
{
  "code": 404,
  "error": "not_found",
  "error_description": "The postcode could not be found."
}
```

You may wonder why the response code is `200` and not `404`. The idea is that the data is known not to be 100% complete (it's the trade-off you make by using free data, rather than pay a large sum of money for a PAF licence). Because the postcode may well exist, but just isn't in the database, it seemed to make sense to indicate that it cannot be found, but without issuing a `404` response. I'm open to feedback if you feel this is incorrect.

A server error will issue a `500` status code anda body that looks like this:

```
{
  "code": 500,
  "error": "server_error",
  "error_description": "A server error has occured"
}
```

If you set the `APP_DEBUG` environment variable to `true`, then the response will include some additional information:

```
{
  "code": 500,
  "error": "server_error",
  "error_description": "A server error has occured",
  "exception": {
    "class": "Exception",
    "message": "The error message",
    "filename": "/path/to/file.php",
    "line": 65
  }
}
```

## Security

For simplictly, the service does not include any authentication mechanism. 

If you do wish to secure it, there are a number of approaches you could take, for example:

1. Use HTTP Basic Auth
2. Use a firewall to restrict access by IP address
3. Fork the package and implement your preferred [authentication](https://lumen.laravel.com/docs/5.7/authentication) mechanism