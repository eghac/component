<?php namespace Elioth\Entrust\Traits;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait EntrustFormTrait {

    //Big block of caching functionality.
    public function cachedFields()
    {
        $formPrimaryKey = $this->primaryKey;
        $cacheKey = 'entrust_fields_for_form_' . $this->$formPrimaryKey;
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags(Config::get('entrust.form_field_table'))->remember($cacheKey, Config::get('cache.ttl', 60), function () {
                return $this->fields()->get();
            });
        } else return $this->fields()->get();
    }

    public function cachedActions()
    {
        $actionPrimaryKey = $this->primaryKey;
        $cacheKey = 'entrust_actions_for_form_' . $this->$actionPrimaryKey;
        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags(Config::get('entrust.form_action_table'))->remember($cacheKey, Config::get('cache.ttl', 60), function () {
                return $this->actions()->get();
            });
        } else return $this->actions()->get();
    }

//    public function save(array $options = [])
//    {   //both inserts and updates
//        if (!parent::save($options)) {
//            return false;
//        }
//        if (Cache::getStore() instanceof TaggableStore) {
//            Cache::tags(Config::get('entrust.form_field_table'))->flush();
//        }
//        return true;
//    }
//
//    public function delete(array $options = [])
//    {   //soft or hard
//        if (!parent::delete($options)) {
//            return false;
//        }
//        if (Cache::getStore() instanceof TaggableStore) {
//            Cache::tags(Config::get('entrust.form_field_table'))->flush();
//        }
//        return true;
//    }
//
//    public function restore()
//    {   //soft delete undo's
//        if (!parent::restore()) {
//            return false;
//        }
//        if (Cache::getStore() instanceof TaggableStore) {
//            Cache::tags(Config::get('entrust.form_field_table'))->flush();
//        }
//        return true;
//    }

    /**
     * One-To-Many relations with module model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(Config::get('entrust.module'), Config::get('entrust.module_foreign_key'));
    }

    public function fields()
    {
        return $this->belongsToMany(Config::get('entrust.field'), Config::get('entrust.form_field_table'));
    }

    public function actions()
    {
        return $this->belongsToMany(Config::get('entrust.action'), Config::get('entrust.form_action_table'));
    }


    public static function boot()
    {
        parent::boot();

        static::deleting(function($form) {
            if (!method_exists(Config::get('entrust.form'), 'bootSoftDeletes')) {
                $form->module()->sync([]);
                $form->fields()->sync([]);
                $form->actions()->sync([]);
            }
            return true;
        });
    }

    /**
     * Checks if the form has a field by its name.
     *
     * @param string|array $name Field name or array of field names.
     * @param bool $requireAll All fields in the array are required.
     *
     * @return bool
     */
    public function hasField($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $fieldName) {
                $hasField = $this->hasField($fieldName);

                if ($hasField && !$requireAll) {
                    return true;
                } elseif (!$hasField && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the permissions were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the permissions were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedFields() as $field) {
                if ($field->fields_name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasAction($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $actionName) {
                $hasAction = $this->hasAction($actionName);

                if ($hasAction && !$requireAll) {
                    return true;
                } elseif (!$hasAction && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the permissions were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the permissions were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedActions() as $action) {
                if ($action->actions_name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Save the inputted permissions.
     *
     * @param mixed $inputPermissions
     *
     * @return void
     */
    public function saveFields($inputFields)
    {
        if (!empty($inputFields)) {
            $this->fields()->sync($inputFields);
        } else {
            $this->fields()->detach();
        }

        if (Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('entrust.form_field_table'))->flush();
        }
    }

    public function saveActions($inputActions)
    {
        if (!empty($inputActions)) {
            $this->actions()->sync($inputActions);
        } else {
            $this->actions()->detach();
        }

        if (Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('entrust.form_action_table'))->flush();
        }
    }

    /**
     * Attach field to current role.
     *
     * @param object|array $field
     *
     * @return void
     */
    public function attachField($field)
    {
        if (is_object($field)) {
            $field = $field->getKey();
        }

        if (is_array($field)) {
            return $this->attachFields($field);
        }

        $this->fields()->attach($field);
    }


    public function attachAction($action)
    {
        if (is_object($action)) {
            $action = $action->getKey();
        }

        if (is_array($action)) {
            return $this->attachActions($action);
        }

        $this->actions()->attach($action);
    }
    /**
     * Detach permission from current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function detachField($field)
    {
        if (is_object($field)) {
            $field = $field->getKey();
        }

        if (is_array($field)) {
            return $this->detachFields($field);
        }

        $this->fields()->detach($field);
    }

    public function detachAction($action)
    {
        if (is_object($action)) {
            $action = $action->getKey();
        }

        if (is_array($action)) {
            return $this->detachActions($action);
        }

        $this->actions()->detach($action);
    }

    /**
     * Attach multiple permissions to current role.
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function attachFields($fields)
    {
        foreach ($fields as $field) {
            $this->attachField($field);
        }
    }

    public function attachActions($actions)
    {
        foreach ($actions as $action) {
            $this->attachAction($action);
        }
    }

    /**
     * Detach multiple permissions from current role
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function detachFields($fields = null)
    {
        if (!$fields) $fields = $this->fields()->get();

        foreach ($fields as $field) {
            $this->detachField($field);
        }
    }

    public function detachActions($actions = null)
    {
        if (!$actions) $actions = $this->actions()->get();

        foreach ($actions as $action) {
            $this->detachAction($action);
        }
    }

}