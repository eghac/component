<?php namespace Elioth\Entrust\Traits;

use Illuminate\Support\Facades\Config;

trait EntrustFormTrait {

    /**
     * One-To-Many relations with module model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(Config::get('entrust.module'), Config::get('entrust.module_foreign_key'));
    }
}