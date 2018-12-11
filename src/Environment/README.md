## Uso

### Variables

#### Load Variables
##### `load( string loadPath , string envFile );`

```php
use Sarge\Environment\Variables;
...
Variables::load('./config/');
```

#### Save Variables
##### `save( string savePath , array content );`

```php
use Sarge\Environment\Variables;
...
Variables::save('./config/', array(
	'DBHOST' => '127.0.0.1',
	'DBPORT' => '3306'
));
```