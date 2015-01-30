# Socialites plugin

A Croogo plugin to enable authentication with OAuth2 identity providers.
It basically wraps :
  * [thephpleague/oauth1-client](https://github.com/thephpleague/oauth1-client)
  * [thephpleague/oauth2-client](https://github.com/thephpleague/oauth2-client)

Currently, the plugin supports:

  * Github
  * Facebook
  * Google
  * Twitter

## Installation

In croogo APP directory:

	composer require xintesa/socialites:dev-master
	Console/cake ext activate plugin Socialites
	Console/cake migrations.migration run -p Socialites up

Once the plugin is active, use the example in `Config/providers.default.php`
to configure your application keys in:

	APP/Plugin/Socialites/Config/providers.php

To show login status/button, put the following somewhere in your default layout:

```php
	echo $this->element('Socialites.login_info');
```
