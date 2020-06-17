# AML Integration

## About

Yoti provides an AML (Anti Money Laundering) check service to allow a deeper KYC process to prevent fraud. This is a chargeable service, so please contact [sdksupport@yoti.com](mailto:sdksupport@yoti.com) for more information.

Yoti will provide a boolean result on the following checks:

* PEP list - Verify against Politically Exposed Persons list
* Fraud list - Verify against  US Social Security Administration Fraud (SSN Fraud) list
* Watch list - Verify against watch lists from the Office of Foreign Assets Control

To use this functionality you must ensure your application is assigned to your Organisation in the Yoti Hub - please see [here](https://developers.yoti.com/yoti-app/web-integration#step-1-creating-an-organisation) for further information.

For the AML check you will need to provide the following:

* Data provided by Yoti
  * Given name(s)
  * Family name
  * Postcode/Zip code (US citizens only) - this can be retrieved from the Structured Postal Address attribute

* Data that must be collected from the user:
  * Country of residence (must be an ISO 3166 3-letter code)
  * Social Security Number (US citizens only)

## Consent

Performing an AML check on a person *requires* their consent.
**You must ensure you have user consent *before* using this service.**

## Performing a Check

Given a YotiClient initialised with your SDK ID and KeyPair (see [Client Initialisation](./PROFILE.md#client-initialisation)) performing an AML check is a straightforward case of providing basic profile data.
 
```php
<?php
use Yoti\Aml\Country;
use Yoti\Aml\Address;
use Yoti\Aml\Profile;

// Address of the user profile to check
$amlAddress = new Address(new Country('GBR'));
$amlProfile = new Profile('Edward Richard George', 'Heath', $amlAddress);

// Perform the check
$amlResult = $client->performAmlCheck($amlProfile);

// Result returned for this profile
var_dump($amlResult->isOnPepList());
var_dump($amlResult->isOnFraudList());
var_dump($amlResult->isOnWatchList());

// Or
echo $amlResult;
```

## Running the example

- See the [AML Example](../examples/aml-check/README.md) folder for instructions on how to run the AML Example project
