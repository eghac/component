<?php namespace Elioth\Entrust\Contracts;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Elioth\Entrust
 */

interface EntrustRoleInterface
{
    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms();

    /**
     * Many-to-Many relations with the module model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules();

    /**
     * Save the inputted permissions.
     *
     * @param mixed $inputPermissions
     *
     * @return void
     */
    public function savePermissions($inputPermissions);

    /**
     * Save the inputted modules.
     *
     * @param mixed $inputModules
     *
     * @return void
     */
    public function saveModules($inputModules);

     /**
     * Attach permission to current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function attachPermission($permission);

    /**
     * Attach module to current role.
     *
     * @param object|array $module
     *
     * @return void
     */
    public function attachModule($module);

    /**
     * Detach permission form current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function detachPermission($permission);

    /**
     * Detach module form current role.
     *
     * @param object|array $module
     *
     * @return void
     */
    public function detachModule($module);

    /**
     * Attach multiple permissions to current role.
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function attachPermissions($permissions);

    /**
     * Attach multiple modules to current role.
     *
     * @param mixed $modules
     *
     * @return void
     */
    public function attachModules($modules);

    /**
     * Detach multiple permissions from current role
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function detachPermissions($permissions);

    /**
     * Detach multiple modules from current role
     *
     * @param mixed $modules
     *
     * @return void
     */
    public function detachModules($modules);
}
