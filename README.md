[![Latest Stable Version](https://img.shields.io/packagist/v/omisai/laravel-billingo?style=for-the-badge)](https://packagist.org/packages/omisai/laravel-billingo)
[![License](https://img.shields.io/packagist/l/omisai/laravel-billingo?style=for-the-badge)](https://packagist.org/packages/omisai/laravel-billingo)
[![PHP Version Require](https://img.shields.io/badge/PHP-%3E%3D8.3-blue?style=for-the-badge&logo=php)](https://packagist.org/packages/omisai/laravel-billingo)
![Laravel](https://img.shields.io/badge/Laravel-11%2C12-red?style=for-the-badge&logo=laravel)
![Billingo API](https://img.shields.io/badge/Billingo%20API-v3-yellow?style=for-the-badge)

# About

**laravel-billingo** is a Laravel package for seamless integration with the Billingo API v3. This package provides a simple and elegant way to interact with Billingo's features directly from your Laravel application.

## Installation

You can install the package via Composer:

```bash
composer require omisai/laravel-billingo
```

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Omisai\Billingo\BillingoServiceProvider"
```

This will create a `config/billingo.php` file where you can configure your Billingo API credentials and settings.

## Usage

For detailed usage instructions, including examples and API documentation, please refer to the [USAGE.md](USAGE.md) file.

## Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this project.

## Security

If you discover any security-related issues, please email security@omisai.com instead of using the issue tracker. For more information, see [SECURITY.md](SECURITY.md).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Sponsoring

If you find this package useful, please consider sponsoring the development:

- [Sponsoring on GitHub](https://github.com/sponsors/omisai-tech)

Your support helps us maintain and improve this open-source project!
