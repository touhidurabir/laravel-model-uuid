<?php

namespace Touhidurabir\ModelUuid\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Touhidurabir\ModelUuid\UuidGenerator\Generator as UuidGenerator;

class ModelUuidRegeneratorJob implements ShouldQueue {

    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The target models class
     *
     * @var string
     */
    public $models;


    /**
     * Should apply the uuid on all existing records
     *
     * @var bool
     */
    public $updateAll = false;


    /**
     * Create a new job instance.
     *
     * @param  array $models
     * @param  bool $updateAll
     * 
     * @return void
     */
    public function __construct(array $models, bool $updateAll) {

        $this->models       = $models;
        $this->updateAll    = $updateAll;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        foreach($this->models as $model) {

            $traits = class_uses_recursive($model);

            if ( ! in_array('Touhidurabir\\ModelUuid\\HasUuid', array_values($traits)) ) {

                continue;
            }

            $instance = new $model;
            
            if ( ! $instance->canHaveUuid() ) {

                continue;
            }

            $uuidColumn = $instance->getUuidFieldName();

            $records = $instance->select(['id', $uuidColumn]);

            $records = $this->updateAll ? $records : $records->whereNull($uuidColumn);

            $records->chunk(5000, function ($rows) use ($uuidColumn, $instance) {
                $rows->each(function ($row) use ($uuidColumn, $instance) {
                    $row->update([
                        $uuidColumn => UuidGenerator::uuid4()
                    ]);
                });
            });
        }
    }
    
}