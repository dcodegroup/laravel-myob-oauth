<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyobTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('myob_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token_type')->nullable();
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->string('scope')->nullable();
            $table->string('current_tenant_id')->nullable();
            $table->integer('expires')->nullable();
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
        Schema::dropIfExists('xero_tokens');
    }
}
