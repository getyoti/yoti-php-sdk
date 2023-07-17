<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yoti\DocScan\DocScanClient;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategyBuilder;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategyBuilder;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedSearchProfileSourcesBuilder;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSourcesBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedCustomAccountWatchlistAdvancedCaConfigBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedLivenessCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedThirdPartyIdentityCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistAdvancedCaCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedYotiAccountWatchlistAdvancedCaConfigBuilder;
use Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilterBuilder;
use Yoti\DocScan\Session\Create\Filters\RequiredIdDocumentBuilder;
use Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocumentBuilder;
use Yoti\DocScan\Session\Create\Objective\ProofOfAddressObjectiveBuilder;
use Yoti\DocScan\Session\Create\SdkConfigBuilder;
use Yoti\DocScan\Session\Create\SessionSpecificationBuilder;
use Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTaskBuilder;
use Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTaskBuilder;

class HomeController extends BaseController
{
    public function show(Request $request, DocScanClient $client)
    {
        //WatchScreening Config
        $watchScreeningConfig = (new RequestedWatchlistScreeningConfigBuilder())
            ->withSanctionsCategory()
            ->withAdverseMediaCategory()
            ->build();

        //Search Profiles Sources
        $searchProfileSources = (new RequestedSearchProfileSourcesBuilder())
            ->withSearchProfile("5c62a621-ed6a-4f07-89a1-a941c1733b7c")
            ->build();

        //TypeListSources
        $typeListSources = (new RequestedTypeListSourcesBuilder())
            ->withTypes(['type1', 'type2'])
            ->build();

        //FuzzyMatchingStrategy
        $fuzzyMatchingStrategy = (new RequestedFuzzyMatchingStrategyBuilder())
            ->withFuzziness(0.5)
            ->build();

        //ExactMatchingStrategy
        $exactMatchingStrategy = (new RequestedExactMatchingStrategyBuilder())
            ->build();

        //CustomAccountConfig
        $customConfig = (new RequestedCustomAccountWatchlistAdvancedCaConfigBuilder())
            ->withMatchingStrategy($exactMatchingStrategy)
            ->withSources($searchProfileSources)
            ->withShareUrl(false)
            ->withRemoveDeceased(true)
            ->withApiKey('qiKTHG7Mgqj31mK2d21F7QPpaVBp9zKc')
            ->withClientRef("string")
            ->withMonitoring(true)
            ->withTags(['tag1'])
            ->build();

        //YotiAccountConfig
        $yotiConfig = (new RequestedYotiAccountWatchlistAdvancedCaConfigBuilder())
            ->withMatchingStrategy($exactMatchingStrategy)
            ->withSources($typeListSources)
            ->withShareUrl(false)
            ->withRemoveDeceased(true)
            ->build();


        $sessionSpec = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(600)
            ->withResourcesTtl(90000)
            ->withUserTrackingId('some-user-tracking-id')
            ->withRequestedCheck(
                (new RequestedDocumentAuthenticityCheckBuilder())
                    ->build()
            )
            ->withRequestedCheck(
                (new RequestedLivenessCheckBuilder())
                    ->forZoomLiveness()
                    ->build()
            )
            ->withRequestedCheck(
                (new RequestedWatchlistAdvancedCaCheckBuilder())
                    ->withConfig($customConfig)
                    ->build()
            )
            ->withRequestedCheck(
                (new RequestedFaceMatchCheckBuilder())
                    ->withManualCheckAlways()
                    ->build()
            )
            ->withRequestedCheck(
                (new RequestedIdDocumentComparisonCheckBuilder())
                    ->build()
            )
            ->withRequestedCheck(
                (new RequestedThirdPartyIdentityCheckBuilder())
                    ->build()
            )
            ->withRequestedCheck(
                (new RequestedWatchlistScreeningCheckBuilder())
                    ->withConfig($watchScreeningConfig)
                    ->build()
            )
            ->withRequestedTask(
                (new RequestedTextExtractionTaskBuilder())
                    ->withManualCheckAlways()
                    ->withChipDataDesired()
                    ->withCreateExpandedDocumentFields(true)
                    ->build()
            )
            ->withRequestedTask(
                (new RequestedSupplementaryDocTextExtractionTaskBuilder())
                    ->withManualCheckAlways()
                    ->build()
            )
            ->withSdkConfig(
                (new SdkConfigBuilder())
                    ->withAllowsCameraAndUpload()
                    ->withPrimaryColour('#2d9fff')
                    ->withSecondaryColour('#FFFFFF')
                    ->withFontColour('#FFFFFF')
                    ->withLocale('en-GB')
                    ->withPresetIssuingCountry('GBR')
                    ->withSuccessUrl(config('app.url') . '/success')
                    ->withErrorUrl(config('app.url') . '/error')
                    ->withPrivacyPolicyUrl(config('app.url') . '/privacy-policy')
                    ->withBiometricConsentFlow('EARLY')
                    ->build()
            )
            ->withRequiredDocument(
                (new RequiredIdDocumentBuilder())
                    ->withFilter(
                        (new OrthogonalRestrictionsFilterBuilder())
                            ->withWhitelistedDocumentTypes(['PASSPORT'])
                            ->build()
                    )
                    ->build()
            )
            ->withRequiredDocument(
                (new RequiredIdDocumentBuilder())->build()
            )
            ->withRequiredDocument(
                (new RequiredSupplementaryDocumentBuilder())
                    ->withObjective(
                        (new ProofOfAddressObjectiveBuilder)
                            ->build()
                    )
                    ->build()
            )
            ->build();

            
        $session = $client->createSession($sessionSpec);

        $request->session()->put('YOTI_SESSION_ID', $session->getSessionId());
        $request->session()->put('YOTI_SESSION_TOKEN', $session->getClientSessionToken());

        return view('home', [
            'iframeUrl' => config('yoti')['doc.scan.iframe.url'] . '?' . http_build_query([
                    'sessionID' => $session->getSessionId(),
                    'sessionToken' => $session->getClientSessionToken(),
                ])
        ]);
    }
}
