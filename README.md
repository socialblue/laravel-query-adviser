# Laravel-Query-Adviser

[![Latest Version on Packagist](https://img.shields.io/packagist/v/socialblue/laravel-query-adviser.svg?style=flat-square)](https://packagist.org/packages/socialblue/laravel-query-adviser)
[![Build Status](https://img.shields.io/travis/socialblue/laravel-query-adviser/master.svg?style=flat-square)](https://travis-ci.org/socialblue/laravel-query-adviser)
[![Quality Score](https://img.shields.io/scrutinizer/g/socialblue/laravel-query-adviser.svg?style=flat-square)](https://scrutinizer-ci.com/g/socialblue/laravel-query-adviser)
[![Total Downloads](https://img.shields.io/packagist/dt/socialblue/laravel-query-adviser.svg?style=flat-square)](https://packagist.org/packages/socialblue/laravel-query-adviser)

This package can help you in optimizing your MySql queries created by your Eloquent models.
It uses the Builder to get more information about the underlying query. 

## Installation

You can install the package via composer:

```bash
composer require socialblue/laravel-query-adviser
```

## Usage

``` php

$userBuilder = User::getQuery()
->join('post', 'post.user_id', '=', 'user.id')
->select([DB::raw('SUM(post.id)')]);
dd(QueryBuilderHelper::info($userBuilder));


```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mark.broersen@outlook.com instead of using the issue tracker.

## Credits

- [Mark](https://github.com/socialblue)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).