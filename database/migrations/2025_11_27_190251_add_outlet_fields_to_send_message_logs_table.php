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
        Schema::table('send_message_logs', function (Blueprint $table) {
            $table->string('recipient_type')->default('all')->after('message'); // 'all' or 'outlet'
            $table->unsignedBigInteger('outlet_id')->nullable()->after('recipient_type');
            $table->string('outlet_name')->nullable()->after('outlet_id');
            $table->integer('recipients_count')->default(0)->after('outlet_name');
            
            // Add foreign key constraint
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('send_message_logs', function (Blueprint $table) {
            $table->dropForeign(['outlet_id']);
            $table->dropColumn(['recipient_type', 'outlet_id', 'outlet_name', 'recipients_count']);
        });
    }
};
