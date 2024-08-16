<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freelancer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->text('skills')->nullable();
            $table->decimal('hourly_price', 8, 2)->nullable();

            $table->integer('total_reviews_count')->default(0);
            $table->integer('best_review')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancer_profiles');
    }
};
