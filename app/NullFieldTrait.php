<?php

namespace App;

trait NullFieldTrait {

  public static function boot() {

      parent::boot();

      static::updating(function($model){
          foreach ($model->attributes as $key => $value) {
              //echo $key;die();
              $model->{$key} = empty($value) ? null : $value;
          }
      });

  }

}
