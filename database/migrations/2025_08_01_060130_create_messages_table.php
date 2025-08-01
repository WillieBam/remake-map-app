<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id')->autoIncrement();
            $table->String('content');
            $table->unsignedInteger('views')->default(0);
            
            $table->timestamps();
            $table ->foreignId('user_id')->nullable()->constrained('users','user_id')->onDelete('set null');
            $table->foreignId('country_id')->nullable()->constrained('countries','country_id')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
