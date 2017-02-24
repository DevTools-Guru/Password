# Password

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![build status](https://gitlab.com/DevTools-Guru/Password/badges/master/build.svg)](https://gitlab.com/DevTools-Guru/Password/commits/master)
[![codecov](https://codecov.io/gl/DevTools-Guru/Password/branch/master/graph/badge.svg)](https://codecov.io/gl/DevTools-Guru/Password)
[![Total Downloads][ico-downloads]][link-downloads]

A simple password class to wrap the internal PHP `password_*` functions to make things easier to use.

## Install

Via Composer

``` bash
$ composer require DevToolsGuru/Password
```

## Usage

``` php
$password = new DevToolsGuru\Password('WhateverTheUserProvides');
echo $password->getHash();
```

For more usage information see the documentation:
* [API](https://gitlab.com/DevTools-Guru/Password/blob/master/docs/API.md)
* [Usage](https://gitlab.com/DevTools-Guru/Password/blob/master/docs/Usage.md)

## Change log

Please see [CHANGELOG](https://gitlab.com/DevTools-Guru/Password/blob/master/CHANGELOG.md) for more information 
on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](https://gitlab.com/DevTools-Guru/Password/blob/master/CONTRIBUTING.md) and 
[CONDUCT](https://gitlab.com/DevTools-Guru/Password/blob/master/CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jonathan@garbee.me instead of using the issue tracker.

## Credits

- [Jonathan Garbee][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/DevToolsGuru/Password.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/DevToolsGuru/Password.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/devtoolsguru/password
[link-downloads]: https://packagist.org/packages/devtoolsguru/password
[link-author]: https://github.com/Garbee
[link-contributors]: https://gitlab.com/DevTools-Guru/Password/graphs/master
