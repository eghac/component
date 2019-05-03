<?php


namespace Elioth\Entrust\Contracts;


interface EntrustFormInterface
{
    public function module();

    public function fields();

    public function actions();

}