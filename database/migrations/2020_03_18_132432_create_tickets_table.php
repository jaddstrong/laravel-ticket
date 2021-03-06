<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('ticket_code')->nullable();
            $table->string('ticket_title');
            $table->string('ticket_description');
            $table->set('ticket_importance', ['level1', 'level2', 'level3']);
            $table->string('ticket_assign')->nullable();
            $table->bigInteger('ticket_admin_id')->nullable();
            $table->set('ticket_status', ['New', 'Open', 'Pending', 'Solve', 'Return', 'Drop', 'ReOpen']);
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
        Schema::dropIfExists('tickets');
    }
}
