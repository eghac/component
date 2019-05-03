<?php


namespace Elioth\Entrust;


use Elioth\Entrust\Contracts\EntrustFormInterface;
use Elioth\Entrust\Traits\EntrustFormTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class EntrustForm extends Model implements EntrustFormInterface
{
    use EntrustFormTrait;

    protected $table;

    /**
     * EntrustForm constructor.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('entrust.forms_table');
    }
}