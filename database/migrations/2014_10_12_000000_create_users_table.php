<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mobile', 13)->unique()->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('name', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->enum('type', User::TYPES)->default(User::TYPE_USER);
            $table->string('avatar', 100)->nullable();
            $table->string('website')->nullable();
            $table->string('verify_code', 6)->nullable();
            $table->timestamp('verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
