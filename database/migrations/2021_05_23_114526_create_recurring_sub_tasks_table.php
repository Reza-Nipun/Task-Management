<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringSubTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_recurring_task_id')->constrained('recurring_tasks');
            $table->string('sub_task_name')->nullable();
            $table->longText('sub_task_description')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('delivery_date')->nullable();
            $table->integer('status')->comment('0=Inactive, 1=Active')->nullable();
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
        Schema::dropIfExists('recurring_sub_tasks');
    }
}
