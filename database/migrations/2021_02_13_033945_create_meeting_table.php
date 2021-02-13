<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks');
            $table->date('meeting_date')->nullable();
            $table->timeTz('meeting_time')->nullable();
            $table->string('invited_by')->nullable();
            $table->string('invited_to')->nullable();
            $table->text('meeting_link')->nullable();
            $table->longText('description')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->comment('0=Cancel, 1=Active, 2=Completed');
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
        Schema::dropIfExists('meeting');
    }
}
