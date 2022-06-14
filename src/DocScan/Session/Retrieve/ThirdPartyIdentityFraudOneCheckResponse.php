<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\Contracts\ProfileCheckResponse;

/**
 *  Represents a check with a third party fraud prevention organisation
 *
 *  Yoti recommends that you inform your users that their data might be checked against a third party data source
 *  as part of the fraud check.
 */
class ThirdPartyIdentityFraudOneCheckResponse extends ProfileCheckResponse
{
}
