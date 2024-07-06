# Box Packer Laravel Package

a Laravel package that takes a set of products and returns the smallest possible box, or set of boxes, that would fit all the products into, while adhering to specific constraints.

## Requirements

Before you begin, ensure you have the following installed on your system:

- **PHP**: Version 8.2 or higher
- **Composer**: Latest version
- **Node.js**: Version 18 or higher
- **NPM**: Version 6.14.15 or higher

## Installation

### From your Laravel Project directory

Run the command

```bash
composer config repositories.box-packer vcs https://github.com/jmursuadev/box-packer.git
composer require jmursuadev/box-packer:dev-main
```

### Publish Vendor Assets

Publishing public assets is required to use the package.

Run the command

```bash
php artisan vendor:publish --provider="Jmursuadev\BoxPacker\BoxPackerServiceProvider" --tag=public
```

Other assets is available: Replace --tag=public with the specific tag used by the package for its public assets.

Available publishable tags:

1. public
2. views
3. routes

## Box Packer Page

You can access the box packer page in this url `/package/box-packer` just append this on your laravel app base url

## TEST

First, clone the repository to your local machine:

```bash
git clone https://github.com/jmursuadev/box-packer.git
cd box-packer or custom repo name
composer install
vendor/bin/phpunit
```