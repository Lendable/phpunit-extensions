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

return static function (RectorConfig $rector): void {
    // ...
    $rector->rule(EnforceDisableReturnValueGenerationForTestDoublesRector::class);
};
```
