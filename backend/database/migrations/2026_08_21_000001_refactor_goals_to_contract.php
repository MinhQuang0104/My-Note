<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('content');
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->renameColumn('target_date', 'start_date');
            $table->renameColumn('is_completed', 'is_active');
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->string('type', 20)->default('boolean')->after('description');
            $table->decimal('target_value', 10, 2)->nullable()->after('type');
            $table->string('unit', 50)->nullable()->after('target_value');
            $table->string('repeat_rule', 20)->default('daily')->after('unit');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('color', 20)->nullable()->after('is_active');
            $table->string('icon', 64)->nullable()->after('color');
            $table->json('tags')->nullable()->after('icon');
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'start_date']);
        });

        Schema::table('goal_entries', function (Blueprint $table) {
            $table->renameColumn('entry_date', 'log_date');
            $table->renameColumn('label', 'value');
        });

        DB::table('goal_entries')->get()->each(function (object $entry): void {
            $rawValue = (string) $entry->value;
            $value = preg_match('/^\d+(\.\d+)?$/', $rawValue) === 1 ? $rawValue : '0';
            DB::table('goal_entries')->where('id', $entry->id)->update(['value' => $value]);
        });

        Schema::table('goal_entries', function (Blueprint $table) {
            $table->decimal('value', 10, 2)->default(0)->change();
            $table->string('status', 20)->default('not_done')->after('value');
            $table->index(['goal_id', 'log_date']);
            $table->index(['user_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('tags');
        });

        Schema::table('goal_entries', function (Blueprint $table) {
            $table->dropIndex(['goal_entries_goal_id_log_date_index']);
            $table->dropIndex(['goal_entries_user_id_log_date_index']);
            $table->dropColumn('status');
            $table->string('value')->change();
            $table->renameColumn('log_date', 'entry_date');
            $table->renameColumn('value', 'label');
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->dropIndex(['goals_user_id_start_date_index']);
            $table->dropIndex(['goals_user_id_is_active_index']);
            $table->dropColumn(['type', 'target_value', 'unit', 'repeat_rule', 'end_date', 'color', 'icon', 'tags']);
            $table->renameColumn('name', 'title');
            $table->renameColumn('start_date', 'target_date');
            $table->renameColumn('is_active', 'is_completed');
        });
    }
};
