# AGENTS.md

This file provides guidance to LLM Agents when working with code in this repository.

## Project

PHP OpenTSDB HTTP API Client (`cybercog/opentsdb-client`) — a library for sending time-series metric data to OpenTSDB via its HTTP API. Also compatible with VictoriaMetrics.

## Commands

All commands must be run inside Docker containers via `docker compose run`.

### Install dependencies
```bash
docker compose run php85 composer update --prefer-stable --prefer-dist --no-interaction
```

### Run all tests
```bash
docker compose run php85 vendor/bin/phpunit
```

### Run unit tests only
```bash
docker compose run php85 vendor/bin/phpunit --testsuite Unit
```

### Run integration tests only (requires running OpenTSDB container)
```bash
docker compose run php85 vendor/bin/phpunit --testsuite Integration
```

### Run a single test
```bash
docker compose run php85 vendor/bin/phpunit --filter testMethodName
```

Available PHP services: `php80` through `php85`. The `opentsdb` service runs on port 4242.

## Architecture

The library has three core classes in `src/` under namespace `Cog\OpenTsdbClient`:

- **`DataPoint`** — Immutable value object representing a single metric. Validates all inputs in the constructor (AssertionError on failure). Sanitizes metric/tag names by replacing unsupported characters with `-`. Implements `JsonSerializable`.
- **`OpenTsdbClient`** — Main client. Takes a PSR-18 `ClientInterface` via constructor injection. Two send methods: `sendDataPointList()` (fire-and-forget) and `sendDataPointListWithDebug()` (returns response details). Maps 12 HTTP status codes to specific error messages.
- **`SendDataPointListResponse`** — Immutable response object with httpStatusCode, success/failed counts, and errors.

Exception hierarchy: `OpenTsdbExceptionInterface` marker implemented by `OpenTsdbException` (HTTP errors) and `OpenTsdbConnectionException` (network failures).

## Code Conventions

- `declare(strict_types=1)` in all files
- All classes are `final`
- PSR-4 autoloading, PSR-18 HTTP client, PSR-7 messages
- 4-space indentation, LF line endings
- Tests: `test/Unit/` and `test/Integration/` with separate PSR-4 namespaces
- Integration tests are skipped in CI via `APP_ENV=ci` check
