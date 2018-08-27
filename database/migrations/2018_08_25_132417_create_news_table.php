<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

    public function up() {
        Schema::create('news', function(Blueprint $table) {
            $table->increments('id');
            $table->string('external_id');
            $table->string('resource_name');
            $table->string('title');
            $table->text('content');
            $table->string('main_image_url')->nullable();
            $table->timestamp('publication_date');
            $table->timestamps();
            $table->unique(['external_id', 'resource_name']);
        });
    }

    public function down() {
        Schema::dropIfExists('news');
    }
}
