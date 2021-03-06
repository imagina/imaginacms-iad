<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIadAdCategoryTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('iad__ad_category', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('ad_id')->unsigned();
      $table->foreign('ad_id')->references('id')->on('iad__ads')->onDelete('restrict');
      $table->integer('category_id')->unsigned();
      $table->foreign('category_id')->references('id')->on('iad__categories')->onDelete('restrict');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('iad__ad_category');
  }
}
