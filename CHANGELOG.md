# Changelog

## 4.6.0 (2026-09-24)

### Breaking Changes

- **Removed AML (Anti-Money-Laundering) check API** — The Connect `/aml-check` endpoint has been decommissioned. The following classes and methods have been removed:
  - `Yoti\YotiClient::performAmlCheck()`
  - `Yoti\Aml\Address`
  - `Yoti\Aml\Country`
  - `Yoti\Aml\Profile`
  - `Yoti\Aml\Result`
  - `Yoti\Aml\Service`
  - `Yoti\Exception\AmlException`

  If you were using the AML check API, please remove all calls to `performAmlCheck()` and references to `Yoti\Aml\*` classes from your application.
