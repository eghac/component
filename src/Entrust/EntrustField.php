<?php


namespace Elioth\Entrust;


use Elioth\Entrust\Contracts\EntrustFieldInterface;
use Elioth\Entrust\Traits\EntrustFieldTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class EntrustField extends Model implements EntrustFieldInterface
{
    use EntrustFieldTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('entrust.fields_table');
    }

}