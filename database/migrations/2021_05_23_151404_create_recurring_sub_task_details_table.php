<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringSubTaskDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_sub_task_details', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_recurring_task_id')->constrained('recurring_tasks');
            $table->integer('recurring_sub_task_id')->constrained('recurring_sub_tasks');
            $table->integer('change_count')->default('0');
            $table->integer('status')->comment('0=Terminated, 1=Completed, 2=Pending');
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
        Schema::dropIfExists('recurring_sub_task_details');
    }
}
