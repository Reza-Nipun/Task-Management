<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name')->nullable();
            $table->longText('task_description')->nullable();
            $table->string('assigned_by');
            $table->string('assigned_to');
            $table->date('assign_date');
            $table->date('delivery_date');
            $table->date('reschedule_delivery_date')->nullable();
            $table->date('change_count')->nullable();
            $table->integer('status')->comment('0=Terminated, 1=Complete, 2=Pending');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
