# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]


## [1.0.0] - 2021-08-14
- Initial release

## [1.0.1] - 2021-08-14

- Some new feature added like some helper function and score to make it easy to search by uuid.
- Some bug fix.
- Test case updates.

## [1.0.2] - 2021-09-15
- Add save quietly ability on uuid attach/update

## [1.0.3] - 2021-09-22
- Bug fix for non string attach event.

## [1.0.4] - 2021-09-22
- Added default on missing config values.

## [1.1.0] - 2021-10-29
- Bug fix.
- Regeneration command and job added.
- New Features like temporarily disable/enable added.
- Tests update.
- README update.

## [1.1.1] - 2021-10-29
- Bug fix

## [1.2.0] - 2026-10-28
- Laravel 11, 12 and 13 support added.
- PHPUnit 10, 11 and 12 support for tests; test methods now use `#[Test]` attributes alongside the `@test` annotations.
- Fixed `LogicException` on recent Laravel versions caused by instantiating the model while it was being booted in `HasUuid::bootHasUuid`.
- Fixed `uuid:regenerate` command on Laravel 10+ by using `dispatchSync` (with `dispatchNow` fallback) for the non-queued run.
- Fixed PHP 8.4 implicit nullable parameter deprecation in `HasUuid::canHaveUuid`.
- Code style formatted to PSR-12 with Laravel Pint (`composer format` / `composer lint`).
- Replaced Travis CI with GitHub Actions (PHP 8.2–8.4 × Laravel 11/12/13, plus a code style check).
