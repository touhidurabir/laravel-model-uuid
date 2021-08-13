# Laravel Model UUID

A simple package to generate model uuid for laravel models

## Installation

Require the package using composer:

```bash
composer require touhidurabir/laravel-model-uuid
```

To publish the config file:
```bash
php artisan vendor:publish --provider="Touhidurabir\ModelUuid\ModelUuidServiceProvider" --tag=config
```

## Usage

Use the trait **HashUuid** in model where uuid needed to attach

```php
use Touhidurabir\ModelUuid\HasUuid;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
    
    use HasUuid;
}
```

By default this package use the column name uuid as for the **uuid** attach and preform it on model **creating** event . But these configurations can be changed form the **model-uuid.php** config file.

Also possible to override the uuid column and attaching event from each of the model . to do that need to place the following method in the model : 

```php
use Touhidurabir\ModelUuid\HasUuid;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
    
    use HasUuid;

    public function uuidable() : array {

        return [
            'column' => 'uuid',
            'event'  => 'created',
        ];
    }
}
```

Make sure to the put the uuid colun name in migration file

```php
$table->string('uuid')->nullable()->unique()->index();
```
Or can be used with the combination of model-uuid config as such:

```php
$table->string(config('model-uuid.column'))->nullable()->unique()->index();
```

> The package also provide a bit of safe guard by checking if the model table has the given uuid column . 
>
> If the uuid column not found for model table schema, it will not create and attach an uuid.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
