# Spectre
Lumen passport - Basic OAuth2 security module

This project is aimed to be a Laravel passport like module, to get started, up and running with OAuth2 on a Lumen project.

This implementation is based on [PHPLeague OAuth2](https://oauth2.thephpleague.com/) as well as Laravel Passport is, right now is missing private/public key creation vía artisan commands so this has to be done manually and those files have to be placed on the storage folder of your Lumen project.

## Installation
Add this repository as a dependency on composer.json
```javascript
{
  ...
  "require": {
    ...
    "byteam/spectre": "dev-master"
  }
  ...
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:fvasquezc23/spectre.git"
    }
  ]
}
```
Create a private/public encryption keys with openssl
```bash
openssl rsa -pubout -in oauth-private.key -out oauth-public.key
```
Place the newly generated keys on storage folder

## Configuration
Create a php file named spectre.php on config folder
```php
<?php

return [
    'interval' => [
        'access_token' => 'PT1H',
        'refresh_token' => 'P1M'
    ]
];
```
Only available options right now are expirations times for access_token as well for refresh_token.

## Contributing
Pull requests are welcome.
