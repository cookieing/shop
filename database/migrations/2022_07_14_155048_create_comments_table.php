<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('评论的用户');
            $table->integer('goods_id')->comment('所属商品');
            $table->enum('rate',['好评','中评','差评'])-> default('好评')->comment('评价评级');
            $table->string('content')->comment('评价内容');
            $table->string('reply')->comment('商家回复');
            $table->string('pics')->nullable()->comment('多个评价图');
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
        Schema::dropIfExists('comments');
    }
}
