<?php

namespace Elioth\Entrust\Traits;
//namespace EliotHumerez\Entrust\Traits;
use Illuminate\Support\Facades\Config;

trait EntrustModuleTrait {

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Config::get('entrust.role'), Config::get('entrust.role_module_table'));
    }

    /**
     * One-To-Many relations with form model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forms()
    {
        return $this->hasMany(Config::get('entrust.form'), Config::get('entrust.module_foreign_key'));
    }

    /**
     * Boot the permission model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the permission model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($module) {
            if (!method_exists(Config::get('entrust.module'), 'bootSoftDeletes')) {
                $module->roles()->sync([]);
                $module->forms()->sync([]);
            }
            return true;
        });
    }
}