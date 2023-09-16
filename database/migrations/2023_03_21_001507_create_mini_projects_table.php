<?php

use App\Models\MiniProjectTag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mini_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MiniProjectTag::class);
            $table->string('slug');
            $table->string('project_name');
            $table->string('url');
            $table->string('thumbnail');
            $table->text('overview');
            $table->date('development_date');
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
        Schema::dropIfExists('mini_projects');
    }
};
