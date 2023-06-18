<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->foreignId('quote_id')->constrained()->cascadeOnDelete();
			$table->unsignedInteger('like_id')->nullable();
			$table->unsignedInteger('comment_id')->nullable();
			$table->boolean('read')->default(false);
			$table->unsignedInteger('end_user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('notifications');
	}
};
