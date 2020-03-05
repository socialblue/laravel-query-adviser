# Laravel-Query-Adviser

[![Latest Version on Packagist](https://img.shields.io/packagist/v/socialblue/laravel-query-adviser.svg?style=flat-square)](https://packagist.org/packages/socialblue/laravel-query-adviser)
[![Build Status](https://img.shields.io/travis/socialblue/laravel-query-adviser/master.svg?style=flat-square)](https://travis-ci.org/socialblue/laravel-query-adviser)
[![Quality Score](https://img.shields.io/scrutinizer/g/socialblue/laravel-query-adviser.svg?style=flat-square)](https://scrutinizer-ci.com/g/socialblue/laravel-query-adviser)
[![Total Downloads](https://img.shields.io/packagist/dt/socialblue/laravel-query-adviser.svg?style=flat-square)](https://packagist.org/packages/socialblue/laravel-query-adviser)

This package can help you in optimizing your MySql queries created by your Eloquent models.
It logs the queries used by your application and provides a helpful overview of the queries.
You can rerun the query analyze the database impact or just copy the query to your clipboard.

The handy card view allows you to quickly see the pain points of your application.

goto **{app_url}/query-adviser/query**
> 


![Query Overview](./img/overview.png)

collapse and expand the panels for less or more information about the queries executed in that time frame.

**Groups**

You can group the queries by time, routes and rawQuery.
Also you can clear the cached queries by pressing the eject button.

![Query groups](./img/groupby.png)

**Query card**
![Query Card](./img/card.png)
> | execute | information | copy to clipboard |

Open the explain dialog to see more information about the query.

**Query information**
![Query Card](./img/query-information.png)

**Re-execute Query**
![Query Card](./img/query-execute.png)


## Installation

You can install the package via composer:

```bash
composer require socialblue/laravel-query-adviser

php artisan vendor:publish
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