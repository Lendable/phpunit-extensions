Lendable PHPUnit Extensions
===========================

> [!WARNING]
> This library is still in early development.

## Installation

Install through Composer:

```bash
composer require --dev lendable/phpunit-extensions
```

## Features

### Strict mocking

By default, when creating a mock all method return values are stubbed based on typing information. E.g.

```php
public function foo(): int 
{
    return $this->foo;
}
```

Will be stubbed to return `0`. This library ships two mechanisms to simplify disabling this functionality to force all methods called on a mock to be configured explicitly.

* If you extend from `Lendable\PHPUnitExtensions\TestCase`, this will be enabled. If you can just extend from this class it is the simplest way to opt-in to all functionality.
* The trait `Lendable\PHPUnitExtensions\StrictMocking` is provided to enable this alone. If you are forced into using another abstract `*TestCase` (e.g. from a vendor) this can be added into the class hierarchy.

### Property garbage collection

PHPUnit keeps every test case object until the whole suite has finished, so anything a test stores in its properties (a booted kernel's services, database and message broker connections, large fixtures) stays alive for the rest of the run. In long suites this grows memory and can exhaust file descriptors.

When the next test starts, all of the previous test case's properties are unset so their values can be freed, then garbage cycles are collected. Releasing one test late means `tearDown()`, `#[After]` methods and `onNotSuccessfulTest()` can still use the properties, and a failing after hook does not stop the release. The last test's properties are freed when the process ends.

Static, readonly and hooked properties are left alone.

Override `collectsGarbageCyclesAfterRelease()` to return `false` if a cycle collection after every test is too costly for your suite.

* If you extend from `Lendable\PHPUnitExtensions\TestCase`, this is enabled.
* The trait `Lendable\PHPUnitExtensions\PropertyGarbageCollection` enables this alone.

## PHPStan

A PHPStan extension is provided to enforce usage of features of this library.

Add the rules into your PHPStan configuration:

```neon
rules:
    # ...
    - vendor/lendable/phpunit-extensions/phpstan/rules.neon
```

Configure any exclusions you may have:

```neon
lendable_phpunit:
    enforceStrictMocking:
        pardoned:
            - Foo\Bar\MyTest
```

## Rector

A [Rector](https://getrector.com/) rule is provided to automate adopting strict mocking across a test suite.

`Lendable\PHPUnitExtensions\Rector\EnforceDisableReturnValueGenerationForTestDoublesRector` adds PHPUnit's `#[DisableReturnValueGenerationForTestDoubles]` attribute to test classes that do not already have it. Abstract classes are skipped, since the attribute only has an effect when placed on the concrete test class.

Register the rule in your Rector configuration:

```php
use Lendable\PHPUnitExtensions\Rector\EnforceDisableReturnValueGenerationForTestDoublesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    // ...
    ->withRules([EnforceDisableReturnValueGenerationForTestDoublesRector::class]);
```

With the closure-style configuration, register it via `$rectorConfig->rule(EnforceDisableReturnValueGenerationForTestDoublesRector::class)`.

## Local development

The Docker-based environment is defined in `local/` and driven from the `Makefile`:

```shell
make up      # builds the image (on first run) and starts the container
make shell   # opens a shell inside the container, in /app
make down    # stops and removes the container
```

From the shell:

```shell
composer install
composer ci
```

See the `Makefile` for the remaining targets.

### Xdebug

Xdebug is installed but disabled by default. Copy `local/.env.dist` to
`local/.env`, set `XDEBUG_ENABLED=1` (optionally with `XDEBUG_HOST`,
`XDEBUG_PORT` and `XDEBUG_IDE_KEY`), then run `make restart`.
