<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkAccountTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.account.profiles'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('language', 5)->default(config('wk-core.language'));
            $table->string('timezone')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('notice_login')->default(config('wk-account.notice_login'));
            $table->text('note')->nullable();
            $table->text('remarks')->nullable();
            $table->json('addresses')->nullable();
            $table->json('images')->nullable();

            $table->timestampsTz();

            $table->foreign('user_id')->references('id')
                  ->on(config('wk-core.table.user'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->index('language');
            $table->index('gender');
        });
        if (
            config('wk-account.onoff.currency')
            && Schema::hasTable(config('wk-core.table.currency.currencies'))
        ) {
            Schema::table(config('wk-core.table.account.profiles'), function (Blueprint $table) {
                $table->foreign('currency_id')->references('id')
                      ->on(config('wk-core.table.currency.currencies'))
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            });
        }

        if (config('wk-account.users.edit')) {
            Schema::table(config('wk-core.table.user'), function (Blueprint $table) {
                $table->timestampTz('login_at')->nullable()->after('remember_token');
                $table->boolean('is_enabled')->default(1)->after('login_at');

                $table->softDeletes();
            });
        }
    }

    public function down() {
        if (config('wk-account.users.edit')) {
            Schema::table(config('wk-core.table.user'), function (Blueprint $table) {
                $table->dropColumn(['login_at','is_enabled','deleted_at']);
            });
        }
        Schema::dropIfExists(config('wk-core.table.account.profiles'));
    }
}
