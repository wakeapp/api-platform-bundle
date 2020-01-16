## [2.0.7] - 2020-01-16
### Added
- `ApiErrorCodeGuesserInterface::class` now is alias for `wakeapp_api_platform.error_code_guesser_service`.

## [2.0.6] - 2019-12-30
### Changed
- Updated `wakeapp/swagger-resolver-bundle` to the `^0.4.6` pattern version.

## [2.0.5] - 2019-12-27
### Fixed
- Fixed deprecations for Symfony

## [2.0.4] - 2019-12-11
### Changed
- Updated `wakeapp/swagger-resolver-bundle` to the `^0.4.5` pattern version.

## [2.0.3] - 2019-09-23
### Added
- Installed `wakeapp/swagger-resolver-bundle` to the `^0.4.3` pattern version.
### Fixed
- Fixed route paths with multiple methods.
### Removed
- Removed `adrenalinkin/swagger-resolver-bundle`.

## [2.0.2] - 2019-09-11
### Changed
- Updated `adrenalinkin/swagger-resolver-bundle` to the `^0.4.2` pattern version.

## [2.0.1] - 2019-07-25
## Added
- Added `symfony/translation-contracts`.
## Fixed
- There was a dependency on `symfony/translations-contracts`, but this bundle was not in the required section.
### Changed
- Var name in `ApiResponseListenerCompiler::process` from `requestDebug` to `responseDebug`.

## [2.0.0] - 2019-04-29
### Added
- Added api versioning mechanism.
- Added `wakeapp_api_platform.minimal_api_version` configuration parameter.
- Added `ApiAreaGuesserInterface::getApiVersion`.
- Added resolving entry DTO's by full request.
- Added `ApiDtoFactory::createApiDtoByRequest`.
- Added popular HTTP codes: `409` `410` `412` `503`.
### Changed
- Added argument resolver instead controller listener.
- Updated `wakeapp/dto-resolver` to the `^1.0` pattern version.
### Removed
- Removed `ApiControllerArgumentListener`.
- Removed `ApiException::HTTP_INTERNAL_SERVER_ERROR`.
- Removed all non-HTTP error codes.
### Fixed
- Return `ApiResponse` instead of `JsonResponse` in the `ApiResponseListener`.

## [1.0.5] - 2019-04-11
### Changed
- Downgraded `DtoResolverInterface` to `JsonSerializable` in the `ApiResponse`.
- Updated `wakeapp/dto-resolver` to the `v0.4.0` version.

## [1.0.4] - 2019-04-09
### Changed
- Renamed monolog.logger channel from `api_platform` to `wakeapp_api_platform`.

## [1.0.3] - 2019-04-05
### Added
- Added `stackTrace` property in `ApiDebugExceptionResultDto`.
- Added `ApiPlatformLogger`.

## [1.0.1] - 2019-04-05
### Changed
- Updated `wakeapp/dto-resolver` to the `v0.3.1` version.
- Use `DtoResolverTrait` instead of `AbstractDtoResolver` in `ApiResultDto`, `ApiDebugExceptionResultDto`.

## [1.0.0] - 2019-03-25
### Changed
- Right response code when get HTTP error.

## [0.1.2] - 2019-03-25
### Changed
- Updated `adrenalinkin/swagger-resolver-bundle` to the `0.4.0` version.

## [0.1.1] - 2019-01-09
### Fixed
- Fixed unclear translation for an error code `22`. 

## [0.1.0] - 2018-08-30
### Added
- First version of this bundle.
