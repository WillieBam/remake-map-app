<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id('news_id')->autoIncrement();
            $table->string('title');
            $table->string('content');
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            $table->foreignId('country_id')->nullable()->constrained('countries', 'country_id')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
