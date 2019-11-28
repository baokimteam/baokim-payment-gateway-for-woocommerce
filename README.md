<p align="center"><img src="https://user-images.githubusercontent.com/56957895/69772424-7dcaed00-11c2-11ea-832a-b89627c2fb2c.png" width="400"></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="#"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="#"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Bao Kim Payment gateway for Woocommerce
Contributors: Bao Kim Team

Tags: woocommerce, payment, baokim, credit card, atm

Requires at least: 4.4

Tested up to: 7.3

Requires PHP: 5.6

Stable tag: 1.0.2

License: GPLv3

License URI: https://www.gnu.org/licenses/gpl-3.0.html

### Description
Bao Kim Payment Platform is an open payment platform, Bao Kim provides a full range of APIs that allow users to integrate their application (web / app) with Bao Kim in order to receive payment orders and checks. account, transaction control, automatic trading, ...

### Bao Kim APIs Document
[Vietnamese](https://developer.baokim.vn/payment/)

[English](https://developer.baokim.vn/payment/english.html)

### Installation

This gateway requires WooCommerce 2.6 and above.

### Automatic installation

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of the WooCommerce Bao Kim Payment plugin, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Bao Kim Payment gateway for WooCommerce” and click Search Plugins. Once you’ve found our plugin you can view details about it such as the point release, rating, and description. Most importantly, of course, you can install it by simply clicking "Install Now", then "Activate".

### Manual installation

1. Download the plugin files from [here](https://github.com/baokimteam/baokim-payment-gateway-for-woocommerce/releases/download/1.0.2/baokim-payment-gateway-for-woocommerce.zip) and extract to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin


### Frequently Asked Questions

= Does this support both production mode and sandbox mode for testing? =

Yes, it does - production and sandbox mode is driven by the API keys you use. Read more about sandbox mode [here](https://developer.baokim.vn/payment/#mi-trng-sandboxtest)

### Screenshots

1. The settings panel used to configure the gateway.
2. Normal checkout with Bao Kim Payment.
3. Option to save a card to your account.
4. Checking out with a saved card.

### Changelog

= 1.0.2 =
* Small fix
* Add city and country to customer address

= 1.0.1 =
* Update customer phone and address.
* Refactor code

= 1.0 =
* First version of Bao Kim Payment for WooCommerce
