# odata-query-parser

Parse OData v4 query strings.

[![Packagist Version](https://img.shields.io/packagist/v/khalyomede/odata-query-parser)](https://packagist.org/packages/khalyomede/odata-query-parser) [![Packagist](https://img.shields.io/packagist/l/khalyomede/odata-query-parser)](https://github.com/khalyomede/odata-query-parser/blob/master/LICENSE) [![PHP from Packagist](https://img.shields.io/packagist/php-v/khalyomede/odata-query-parser)](https://github.com/khalyomede/odata-query-parser/blob/master/composer.json#L14) [![Build Status](https://travis-ci.com/khalyomede/odata-query-parser.svg?branch=master)](https://travis-ci.com/khalyomede/odata-query-parser) [![Maintainability](https://api.codeclimate.com/v1/badges/1ca8f176fedec7db81a2/maintainability)](https://codeclimate.com/github/khalyomede/odata-query-parser/maintainability) [![Known Vulnerabilities](https://snyk.io/test/github/khalyomede/odata-query-parser/badge.svg?targetFile=composer.lock)](https://snyk.io/test/github/khalyomede/odata-query-parser?targetFile=composer.lock)

## Summary

- [About](#about)
- [Requirements](#requirements)
- [Installation](#installation)
- [Examples](#examples)
- [Known issues](#known-issues)

## About

I needed to only parse query strings to convert OData v4 commands into an understandable array that I could use to make a Laravel package to offer a way to automatically use Eloquent to filter the response according to this parsed array of OData v4 command.

As I did not see a package exclusively dealing with parsing the query strings, and saw that [some people worked on their own without open sourcing it](https://stackoverflow.com/questions/14145604/parse-odata-query-uri-into-php-array), I decided I would start one myself.

## Requirements

- PHP >= 7.2.0
- [Composer](https://getcomposer.org/)

## Installation

Add the package to your dependencies:

```bash
composer require khalyomede/odata-query-parser
```

## Examples

- [1. Use \$select to filter on some fields](#1-use-select-to-filter-on-some-fields)
- [2. Use non dollar syntax](#2-use-non-dollar-syntax)

### 1. Use \$select to filter on some fields

In this example, we will use the `$select` OData query string command to filter the fields returned by our API.

```php
use Khalyomede\OdataQueryParser;

$data = OdataQueryParser::parse('http://example.com/api/user?$select=id,name,age');
```

If you inspect `$data`, this is what you will get:

```php
[
  "select" => [
    "id",
    "name",
    "age"
  ]
]
```

### 2. Use non dollar syntax

In this example, we will use a unique feature of this library: to be able to not specify any dollar, while still being able to use the OData v4 URL query parameter grammar.

```php
use Khalyomede/OdataQueryParser;

$data = OdataQueryParser::parse("http://example.com/api/user?select=id,name,age");
```

If you inspect `$data`, this is what you will get:

```php
[
  "select" => [
    "id",
    "name",
    "age"
  ]
]
```

## Known issues

- `$filter` command will not parse `or` and functions (like `contains()` of `substringof`), because I did not focused on this for the moment (the parser for `$filter` is too simplist, I should find a way to create an AST).
