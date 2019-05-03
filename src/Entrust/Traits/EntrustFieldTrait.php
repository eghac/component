<?php


namespace Elioth\Entrust\Traits;


use Illuminate\Support\Facades\Config;

trait EntrustFieldTrait
{

    public function forms()
    {
        return $this->belongsToMany(Config::get('entrust.form'), Config::get('entrust.form_field_table'));
    }

    /**
     * Boot the role model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($field) {
            if (!method_exists(Config::get('entrust.field'), 'bootSoftDeletes')) {
                $field->forms()->sync([]);
            }

            return true;
        });
    }
}