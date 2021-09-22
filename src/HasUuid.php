<?php

namespace Touhidurabir\ModelUuid;

use Throwable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Touhidurabir\ModelUuid\UuidGenerator\Generator;
use Touhidurabir\ModelUuid\Concerns\CanSaveQuietly;

trait HasUuid {

    /**
     * UUID field column name
     *
     * @var string
     */
	protected $uuidFieldName;


	/**
     * Attach event name on which UUID will be added to model object
     *
     * @var string
     */
	protected $attachEvent;

	
	/**
     * Model user relation attach after action
     *
     * @var array
     */
	protected $saveActionForEvents = ['created', 'updated'];

	
	/**
     * Attach UUID to model object
     *
     * @return void
     */
	public static function bootHasUuid() {

        $self = new self;

        $self->initializeHasUuid();

        if ( ! is_string($self->attachEvent) ) {

            return;
        }

		static::{$self->attachEvent}(function($model) use ($self) {
            
            $uuidFieldName  = $self->getUuidFieldName();

            if ( ! Schema::hasColumn($model->getTable(), $uuidFieldName) ) {

                return;
            }
            
            $model->{$uuidFieldName} ?: $model->{$uuidFieldName} = $self->generateUuid();

            if ( in_array($self->attachEvent, $self->saveActionForEvents) ) {

                method_exists($self, 'saveQuietly') ? $model->saveQuietly() : $model->saveModelQuietly();
            }
        });
	}


	/**
     * Initialize the trait
     *
     * @return void
     */
	protected function initializeHasUuid() {

		$mapedValues = method_exists($this, 'uuidable') ? $this->uuidable() : null;

		$this->uuidFieldName = $mapedValues['column']   ?? config('model-uuid.column')  ?? 'uuid';
		$this->attachEvent   = $mapedValues['event']    ?? config('model-uuid.event')   ?? 'creating';
	}


	/**
     * Get the UUID column field name
     *
     * @return string
     */
	public function getUuidFieldName() {
        
        return $this->uuidFieldName;
    }


    /**
     * Get the UUID attach event on boot method
     *
     * @return string
     */
	public function getUuidAttachEvent() {
        
        return $this->attachEvent;
    }


    /**
     * Generate UUID
     *
     * @return string
     */
    public function generateUuid() {
        
        return Generator::uuid4();
    }


    /**
     * constarin result by UUID/UUIDs
     *
     * Local Scope Implementation
     *
     * @param  Builder              $builder
     * @param  mixed<string|array>  $uuid
     *
     * @return Builder
     */
    public function scopeByUuid(Builder $builder, $uuid) {

        $method = is_array($uuid) ? 'whereIn' : 'where';

        return $builder->{$method}($this->getUuidFieldName(), $uuid);
    }


    /**
     * Return matching model object by UUID/UUIDs
     *
     * @param  mixed<string|array>  $uuid
     * @return object
     */
    public static function findByUuid($uuid) {

        if ( is_array($uuid) ) {

            return static::whereIn((new self)->getUuidFieldName(), $uuid)->get();
        }
        
        return static::byUuid($uuid)->first();
    }
}