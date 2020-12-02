<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Subtasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('subtasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->string('title');
            $table->date('due_date');
            $table->enum('status',array('Completed','Pending'));
            $table->boolean('is_deleted',0);
            $table->timestamps();

           
        });

        Schema::table('subtasks', function($table)
        {
            $table->foreign('parent_id')
                    ->references('id')
                    ->on('tasks')
                    ->onDelete('cascade');
        });



    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
