<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            // اضافه کردن فیلد user_id به جدول notes
            $table->unsignedBigInteger('user_id')->after('id')->nullable();

            // اضافه کردن کلید خارجی (این خط را حذف کنید اگر قبلاً اضافه شده است)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            // حذف فیلد user_id
            $table->dropColumn('user_id');
        });
    }
}
