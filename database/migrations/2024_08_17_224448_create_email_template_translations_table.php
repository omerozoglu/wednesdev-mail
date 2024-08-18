<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_template_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('email_template_id')->constrained('email_templates');
            $table->string('locale')->index();
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_template_translations');
    }
};
