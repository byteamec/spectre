# Spectre
Lumen passport - Basic OAuth2 security module

This project is aimed to be a Laravel Passport like module, to get started, up and running with OAuth2 on a Lumen project.

This implementation is based on [PHPLeague OAuth2](https://oauth2.thephpleague.com/) as well as Laravel Passport is, right now is missing private/public key creation via artisan commands so this has to be done manually and those files have to be placed on the storage folder of your Lumen project.

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
      "url": "git@github.com:byteamec/spectre.git"
    }
  ]
}
```
Create private/public encryption keys with openssl
```bash
openssl rsa -pubout -in oauth-private.key -out oauth-public.key
```
Place the newly generated keys on storage folder.

Register the service provider on your bootstrap/app.php
```php
...
$app->register(Byteam\Spectre\Providers\SpectreServiceProvider::class);
...
```

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

## Extending
If you want to add field or functionality to the default User extend your User class form SpectreUser
```php
...
use Byteam\Spectre\User as SpectreUser;
...
class User extends SpectreUser {
  ...
}
```
Same principle can be applied to Role class.
```php
...
use Byteam\Spectre\Role as SpectreRole;
...
class Role extends SpectreRole {
  ...
}
```

## Contributing
Pull requests are welcome.
