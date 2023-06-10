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
		Schema::create('quotes', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->json('body');
			$table->string('image')->nullable();
			$table->unsignedBigInteger('user_id')->constrained()->cascadeOnDelete();
			$table->unsignedBigInteger('movie_id')->constrained()->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quotes');
	}
};
