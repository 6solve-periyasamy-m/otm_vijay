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
        Schema::table('sent_quotes', function (Blueprint $table) {
            $table->string('from_email')->nullable()->after('recipient');
            $table->string('from_name')->nullable()->after('from_email');
            $table->text('cc')->nullable()->after('from_name');
            $table->text('bcc')->nullable()->after('cc');
            $table->string('subject')->nullable()->after('bcc');
            $table->longText('email_body')->nullable()->after('subject');
            $table->json('additional_attachments')->nullable()->after('email_body');
            $table->enum('mail_status', ['pending', 'sent', 'failed'])->default('pending')->after('additional_attachments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sent_quotes', function (Blueprint $table) {
            $table->dropColumn([
                'from_email', 
                'from_name', 
                'cc', 
                'bcc', 
                'subject', 
                'email_body',
                'additional_attachments',
                'mail_status'
            ]);
        });
    }
};
