<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->date('review_date');
            $table->integer('period_start_month');
            $table->integer('period_start_year');
            $table->integer('period_end_month');
            $table->integer('period_end_year');
            $table->text('accomplishments')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals')->nullable();
            $table->integer('technical_skills_rating')->default(0); // 1-5
            $table->text('technical_skills_comments')->nullable();
            $table->integer('communication_rating')->default(0); // 1-5
            $table->text('communication_comments')->nullable();
            $table->integer('teamwork_rating')->default(0); // 1-5
            $table->text('teamwork_comments')->nullable();
            $table->integer('initiative_rating')->default(0); // 1-5
            $table->text('initiative_comments')->nullable();
            $table->integer('reliability_rating')->default(0); // 1-5
            $table->text('reliability_comments')->nullable();
            $table->integer('overall_rating')->default(0); // 1-5
            $table->text('overall_comments')->nullable();
            $table->text('employee_comments')->nullable();
            $table->boolean('employee_acknowledged')->default(false);
            $table->date('employee_acknowledged_date')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, acknowledged
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_reviews');
    }
}