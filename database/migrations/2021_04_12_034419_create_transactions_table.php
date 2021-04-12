<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_origin_id')
                ->constrained('users')
                ->onDelete('no action');
            $table->foreignId('user_receiver_id')
                ->constrained('users')
                ->onDelete('no action');
            $table->bigInteger('amount')
                ->unsigned()
                ->notNullable();
            $table->enum('status',
                [
                    'paid',
                    'pending',
                    'analyzing',
                    'contestation',
                    'refunded'
                ]
            );
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
}
