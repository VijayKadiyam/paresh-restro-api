<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_addons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
            $table->integer('addon_menu_id');
            $table->integer('qty');
            $table->string('description')->nullable();
            $table->integer('status_id');
            $table->integer('amount');
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
        Schema::dropIfExists('ticket_addons');
    }
}
