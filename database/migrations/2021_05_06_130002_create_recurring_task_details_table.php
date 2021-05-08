<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringTaskDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_task_details', function (Blueprint $table) {
            $table->id();
            $table->integer('recurring_task_id');
            $table->date('recurring_date');
            $table->integer('change_count')->default('0');
            $table->integer('status')->comment('0=Terminated, 1=Completed, 2=Pending');
            $table->text('remarks')->nullable();
            $table->date('actual_complete_date')->nullable();
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
        Schema::dropIfExists('recurring_task_details');
    }
}
