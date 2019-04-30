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
