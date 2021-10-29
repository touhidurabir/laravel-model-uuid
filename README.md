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

Use the trait **HasUuid** in model where uuid needed to attach

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

This package also include some helper method that make it easy to find model records via UUID. for example

```php
User::byUuid($uuid)->where('active', true)->first(); // single uuid
User::byUuid([$uuid1, $uuid2])->where('active', true)->get(); // multiple uuid
```

Or simple and direct find

```php
User::findByUuid($uuid); // single uuid
User::findByUuid([$uuid1, $uuid2]); // multiple uuid
```

> The package also provide a bit of safe guard by checking if the model table has the given uuid column . 
>
> If the uuid column not found for model table schema, it will not create and attach an uuid.

It is also possible to disable the uuid generation for certiain purpose temporarily using the provided **static** method. Then can be enabled again.

```php
User::disbaleUuidGeneration(true) // this will diable uuid generation temporarily for model
User::disbaleUuidGeneration(false) // this will enable uuid generation for model again
```

## Command

This packaga includes a command that can use to set up the model uuid for the missing ones or update existing one . It will be helpful if this package included later in any laravel app that already have some data in it's tables and needed to set up uuid for those records. To utlize this command run

```bash
php artisan uuid:regenerate User,Profile
```

The one **argument** it reuired is the name of the models command seperated(if there is multiple models to run for) . Behind the scene it calls a queue job to go through the model recored and work on those to update/fill uuid column value. Other options as follow

### --path=
By default it assumes all the models are located in the **App\Models\\** namespace . But if it's located some where else , use the option to define the proper model space path with **trailing slash** .

### --update-all
By default this command will only work with those model records that have the defined uuid column null . So basically it will fill up the missing ones , but if this false is provided with the command it will update all regardless of uuid associated with or not.

### --on-job
This defined if this will update/fill missing one through a queue job . The command use a job where the main logic resides in . But by default it uses the framework provided **dispatchNow** method to run the jon in sync way . if the falg provided and queue configured properly, it will push the job in the queue . 

### --job= 
If need to pass custom queue job implementation, it can be directly provided though this option . also one can update the queue class in the config file . 

> The default job is defined in the configuration **model-uuid** file as the key **regeneration_job**.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
