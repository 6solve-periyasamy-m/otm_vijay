<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name', 90);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('base_price_per_person', 12)->nullable();
            $table->decimal('margin', 12)->nullable();
            $table->decimal('single_occupancy_surcharge', 12, 0)->nullable();
            $table->decimal('deposit', 12)->nullable();
            $table->boolean('stock_control_active')->default(true);
            $table->integer('stock')->nullable();
            $table->string('booking_form_url', 255)->nullable();
            $table->foreignId('tour_category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('tour_merchandise_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->date('date_from');
            $table->date('date_to');
            $table->text('invoice_footer')->nullable();
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
        Schema::dropIfExists('tours');
    }
}
