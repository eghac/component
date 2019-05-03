<?php


namespace Elioth\Entrust\Contracts;


interface EntrustActionInterface
{
    /**
     * Many-to-Many relations with form model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function forms();
}