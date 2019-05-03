<?php


namespace Elioth\Entrust;


use Elioth\Entrust\Contracts\EntrustActionInterface;
use Elioth\Entrust\Traits\EntrustActionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class EntrustAction extends Model implements EntrustActionInterface
{
    use EntrustActionTrait;

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
        $this->table = Config::get('entrust.actions_table');
    }

}