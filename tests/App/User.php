<?php

namespace Touhidurabir\ModelUuid\Tests\App;

use Touhidurabir\ModelUuid\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model {

    use SoftDeletes;
    
    use HasUuid;

    /**
     * The model associated table
     *
     * @var string
     */
    protected $table = 'users';


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

}