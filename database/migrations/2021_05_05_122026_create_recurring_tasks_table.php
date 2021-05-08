<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->longText('task_description')->nullable();
            $table->string('attachment')->nullable();
            $table->string('assigned_by');
            $table->string('assigned_to');
            $table->integer('recurring_type')->comment('0=Monthly, 1=Weekly');
            $table->integer('last_date_of_month')->comment('0=No, 1=Yes')->nullable();
            $table->integer('monthly_recurring_date')->nullable();
            $table->string('weekly_recurring_day')->nullable();
            $table->integer('status')->comment('1=Active, 0=Inactive');
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
        Schema::dropIfExists('recurring_tasks');
    }
}
