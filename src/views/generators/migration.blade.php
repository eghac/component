<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();

        // Create table for storing roles
        Schema::create('{{ $rolesTable }}', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('{{ $roleUserTable }}', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('user_id')->references('{{ $userKeyName }}')->on('{{ $usersTable }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('{{ $rolesTable }}')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('{{ $permissionsTable }}', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('{{ $permissionRoleTable }}', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('{{ $permissionsTable }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('{{ $rolesTable }}')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('{{ $modulesTable }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('modules_name', 50);
            $table->string('modules_description', 100)->nullable();
            $table->integer('module_id')->unsigned()->nullable();
            $table->tinyInteger('state')->nullable();

            $table->index(["module_id"], 'fk_module_module_idx');
            $table->nullableTimestamps();

            $table->foreign('module_id', 'fk_module_module_idx')
            ->references('id')->on('{{ $modulesTable }}')
            ->onDelete('no action')
            ->onUpdate('no action');
        });

        Schema::create('{{ $roleModuleTable }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('role_id')->unsigned();
            $table->integer('module_id')->unsigned();

            $table->index(["role_id"], 'fk_rolmodulo_rol_idx');

            $table->index(["module_id"], 'fk_rolmodulo_modulo_idx');


            $table->foreign('role_id', 'fk_rolmodulo_rol_idx')
                ->references('id')->on('{{ $rolesTable }}')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('module_id', 'fk_rolmodulo_modulo_idx')
                ->references('id')->on('{{ $modulesTable }}')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->primary(['role_id', 'module_id']);
        });

        Schema::create('{{ $formsTable }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('forms_name', 50);
            $table->string('forms_description', 100)->nullable();
            $table->integer('module_id')->unsigned()->nullable();
            $table->tinyInteger('state')->nullable();

            $table->index(["module_id"], 'fk_formulario_modulo_idx');
            $table->nullableTimestamps();


            $table->foreign('module_id', 'fk_formulario_modulo_idx')
            ->references('id')->on('{{ $modulesTable }}')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('{{ $fieldsTable }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('fields_name', 45);
            $table->string('fields_description', 100)->nullable();
            $table->tinyInteger('state')->nullable();
            $table->nullableTimestamps();
        });

        Schema::create('{{ $formFieldTable }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('form_id')->unsigned()->nullable();
            $table->integer('field_id')->unsigned()->nullable();

            $table->index(["form_id"], 'fk_formulariocampo_formulario_idx');

            $table->index(["field_id"], 'fk_formulariocampo_campo_idx');


            $table->foreign('form_id', 'fk_formulariocampo_formulario_idx')
            ->references('id')->on('{{ $formsTable }}')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('field_id', 'fk_formulariocampo_campo_idx')
            ->references('id')->on('{{ $fieldsTable }}')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->primary(['form_id', 'field_id']);
        });

        Schema::create('{{ $actionsTable }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('actions_name', 50);
            $table->tinyInteger('state')->nullable();
        });

        Schema::create('{{ $formActionTable }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('form_id')->unsigned();
            $table->integer('action_id')->unsigned()->nullable();

            $table->index(["form_id"], 'fk_formularioaccion_formulario_idx');

            $table->index(["action_id"], 'fk_formularioaccion_accion_idx');


            $table->foreign('form_id', 'fk_formularioaccion_formulario_idx')
            ->references('id')->on('{{ $formsTable }}')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('action_id', 'fk_formularioaccion_accion_idx')
            ->references('id')->on('{{ $actionsTable }}')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->primary(['form_id', 'action_id']);
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('{{ $permissionRoleTable }}');
        Schema::drop('{{ $permissionsTable }}');
        Schema::drop('{{ $roleUserTable }}');
        Schema::drop('{{ $rolesTable }}');
        Schema::drop('{{ $formActionTable }}');
        Schema::drop('{{ $formFieldTable }}');
        Schema::drop('{{ $roleModuleTable }}');
        Schema::drop('{{ $fieldsTable }}');
        Schema::drop('{{ $actionsTable }}');
        Schema::drop('{{ $formsTable }}');
        Schema::drop('{{ $modulesTable }}');
    }
}
