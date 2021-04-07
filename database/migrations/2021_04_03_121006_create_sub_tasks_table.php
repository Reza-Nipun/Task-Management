<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_task_id')->constrained('tasks');
            $table->string('sub_task_name')->nullable();
            $table->longText('sub_task_description')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('assign_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->integer('status')->comment('0=Terminated, 1=Completed, 2=Pending')->nullable();
            $table->text('remarks')->nullable();
            $table->date('actual_complete_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_tasks');
    }
}
