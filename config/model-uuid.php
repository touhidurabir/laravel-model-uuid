<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Model uuid column name
    |--------------------------------------------------------------------------
    |
    | The global configuration for model uuid column. if this defined, HasUuid
    | trait will try to attach generated uuid to this table column. 
    | This can also be override as per model using a public method 'uuidable'.
    |
    */

    'column' => 'uuid',


    /*
    |--------------------------------------------------------------------------
    | Model uuid attaching event
    |--------------------------------------------------------------------------
    |
    | Define for which model event it will try to attach a random generating 
    | uuid to the model column. 
    | Default event set to 'creating. But can be override as per model basis
    | through public method 'uuidable'.
    |
    */

    'event' => 'creating',


    /*
    |----------------------------------------------------------------------------
    | The model uuid regeneration queue job
    |----------------------------------------------------------------------------
    | The queue job that will be called when run the 'uuid:regenerate' command to 
    | regenerate the UUIDs for any specific models . 
    |
    */

	'regeneration_job' => \Touhidurabir\ModelUuid\Jobs\ModelUuidRegeneratorJob::class,

];