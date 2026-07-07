<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('vehicle_type', ['car', 'motorcycle']);
            $table->string('country')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['name', 'vehicle_type']);
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained()->cascadeOnDelete();
            $table->enum('vehicle_type', ['car', 'motorcycle'])->index();
            $table->string('model')->index();
            $table->string('variant');
            $table->unsignedSmallInteger('year')->index();
            $table->string('fuel_type')->index();
            $table->string('transmission')->nullable();
            $table->string('category')->nullable();
            $table->unsignedInteger('engine_cc')->nullable();
            $table->decimal('power_hp', 8, 2)->nullable();
            $table->decimal('torque_nm', 8, 2)->nullable();
            $table->unsignedTinyInteger('seat_capacity')->nullable();
            $table->decimal('fuel_tank_capacity', 8, 2)->nullable();
            $table->decimal('fuel_efficiency', 8, 2)->nullable();
            $table->decimal('ev_range', 8, 2)->nullable();
            $table->unsignedBigInteger('annual_tax')->default(0);
            $table->unsignedBigInteger('annual_service_cost')->default(0);
            $table->decimal('depreciation_rate', 5, 2)->default(0);
            $table->unsignedBigInteger('new_price');
            $table->unsignedBigInteger('used_price');
            $table->string('image')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->timestamps();
            $table->unique(['manufacturer_id', 'model', 'variant', 'year']);
        });

        Schema::create('comparison_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('comparison_name');
            $table->unsignedBigInteger('budget')->nullable();
            $table->text('recommendation')->nullable();
            $table->decimal('economic_score', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('comparison_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('history_id')->constrained('comparison_histories')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->decimal('economic_score', 5, 2)->nullable();
            $table->unique(['history_id', 'vehicle_id']);
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'vehicle_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('notification_user', function (Blueprint $table) {
            $table->foreignId('notification_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->primary(['notification_id', 'user_id']);
        });

        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('favorite_vehicle_type', ['car', 'motorcycle'])->nullable();
            $table->string('favorite_brand')->nullable();
            $table->unsignedBigInteger('budget_range')->nullable();
            $table->string('preferred_fuel')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('notification_user');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('comparison_items');
        Schema::dropIfExists('comparison_histories');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('manufacturers');
    }
};
