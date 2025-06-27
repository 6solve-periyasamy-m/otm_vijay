<?php

use App\Models\Booking\Booking;
use App\Models\Location\Currency;
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
        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });
        foreach (Booking::all() as $booking) {
            if ($booking->booking_currency !== null) {
                $booking->currency_id = Currency::where('code', '=', $booking->booking_currency)->first()?->id;
            }
        }
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropColumn('booking_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->string('booking_currency')->nullable();
        });
        foreach (Booking::all() as $booking) {
            $booking->booking_currency = Currency::find($booking->currency_id)?->code;
        }
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('currency_id');
        });
    }
};
