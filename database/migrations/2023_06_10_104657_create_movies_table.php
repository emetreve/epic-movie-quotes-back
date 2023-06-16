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
		Schema::create('movies', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->json('name')->unique();
			$table->unsignedBigInteger('user_id')->constrained()->cascadeOnDelete();
			$table->string('poster')->nullable();
			$table->text('year');
			$table->json('description');
			$table->json('director');
			$table->text('revenue');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('movies');
	}
};
