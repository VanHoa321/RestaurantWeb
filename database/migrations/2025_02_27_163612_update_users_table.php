<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_name')->nullable(false)->after('name');
            $table->string('phone', 10)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade')->after('avatar');
            $table->dateTime('last_login')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('description',255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn([
                'user_name', 
                'phone', 
                'avatar', 
                'group_id', 
                'last_login', 
                'is_active', 
                'description'
            ]);
        });
    }
};
