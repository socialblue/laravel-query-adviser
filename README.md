# Laravel-Query-Adviser

[![Latest Version on Packagist](https://img.shields.io/packagist/v/socialblue/laravel-query-adviser.svg?style=flat-square)](https://packagist.org/packages/socialblue/laravel-query-adviser)
[![Build Status](https://img.shields.io/travis/socialblue/laravel-query-adviser/master.svg?style=flat-square)](https://travis-ci.org/socialblue/laravel-query-adviser)
[![Quality Score](https://img.shields.io/scrutinizer/g/socialblue/laravel-query-adviser.svg?style=flat-square)](https://scrutinizer-ci.com/g/socialblue/laravel-query-adviser)
[![Total Downloads](https://img.shields.io/packagist/dt/socialblue/laravel-query-adviser.svg?style=flat-square)](https://packagist.org/packages/socialblue/laravel-query-adviser)

With Laravel Query Adviser you can get more insights into the SQL queries created by Eloquent models used in your app.
It logs the queries used by your application and provides a helpful overview of the SQL queries. You can rerun the SQL query to analyze the database impact or copy the SQL query to your clipboard.

The handy card view allows you to quickly point out the pain points of your application's SQL queries.

goto **{app_url}/query-adviser/**
> ![Query Overview](./img/stepper.png)

## Session list
> ![Query Overview](./img/session-list.png)

## Query time line
> ![Query Overview](./img/overview.png)

collapse and expand the panels for less or more information about the queries executed in that time frame.

**Groups**

You can group the queries by time, routes and rawQuery.
Also you can clear the cached queries by pressing the eject button.
> group by time, routes, referer, raw queries, queries with bindings, and query time
![Query groups](img/group-by-new.png)

**Sort**
> Sort the time line by last inserted, slowest query, and most occurrences
![Query Card](./img/sorting.png)

**Query card**
> Re-execute a query, get query information, and copy the query to your clipboard
![Query Card](./img/card.png)

**Query labels**
As of version 0.10.0, the query card has labels related to the class, 
file and function of the query executed from your app folder.

> ![Query labels](./img/labels.png)

Open the explain dialog to see more information about the query.

**Query information**
> ![Query Card](./img/query-information.png)

**Re-execute Query**
> ![Query Card](./img/query-execute.png)


## Installation

You can install the package via composer:

```bash
composer require socialblue/laravel-query-adviser --dev
```

Publish Laravel-Query-Adviser 

```bash
php artisan vendor:publish --provider="Socialblue\LaravelQueryAdviser\LaravelQueryAdviserServiceProvider"
```

**When updating from `0.13.2` to `0.14.0` please use**

```bash
php artisan vendor:publish --provider="Socialblue\LaravelQueryAdviser\LaravelQueryAdviserServiceProvider" --force
```

## Usage

Dump and die
``` php

User::join('post', 'post.user_id', '=', 'user.id')
->select([DB::raw('SUM(post.id)')])->dd();

```

Just dump
``` php

User::join('post', 'post.user_id', '=', 'user.id')
->select([DB::raw('SUM(post.id)')])->dump();

```


### Start query logging

1. To start a query log session goto {app_url}/query-adviser/
2. Press play and open the pages of your app you want to log the queries of
3. Stop the session and click on the session bar to see all the details.


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
