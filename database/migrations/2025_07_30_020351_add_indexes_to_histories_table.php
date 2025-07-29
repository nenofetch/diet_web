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
        Schema::table('histories', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index('category', 'histories_category_index');
            $table->index('user_id', 'histories_user_id_index');
            $table->index('created_at', 'histories_created_at_index');
            $table->index('tgl_input', 'histories_tgl_input_index');
            
            // Composite index for common query patterns
            $table->index(['category', 'created_at'], 'histories_category_created_at_index');
            $table->index(['user_id', 'category'], 'histories_user_category_index');
            $table->index(['tgl_input', 'category'], 'histories_date_category_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            // Remove indexes
            $table->dropIndex('histories_category_index');
            $table->dropIndex('histories_user_id_index');
            $table->dropIndex('histories_created_at_index');
            $table->dropIndex('histories_tgl_input_index');
            $table->dropIndex('histories_category_created_at_index');
            $table->dropIndex('histories_user_category_index');
            $table->dropIndex('histories_date_category_index');
        });
    }
};
