<?php


namespace Elioth\Entrust\Contracts;


interface EntrustModuleInterface
{

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * One To Many relations with form model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forms();

}