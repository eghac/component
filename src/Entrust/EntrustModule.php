<?php namespace EliotH\Entrust;

use Elioth\Entrust\Contracts\EntrustModuleInterface;
use Elioth\Entrust\Traits\EntrustModuleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class EntrustModule extends Model implements EntrustModuleInterface {
    use EntrustModuleTrait;

    protected $table;

    /**
     * EntrustModule constructor.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('entrust.modules_table');
    }

}
