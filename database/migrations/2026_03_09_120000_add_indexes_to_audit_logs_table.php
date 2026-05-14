<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            if (! $this->indexExists('audit_logs', 'audit_logs_user_created_idx')) {
                $table->index(['user_id', 'created_at'], 'audit_logs_user_created_idx');
            }
            if (! $this->indexExists('audit_logs', 'audit_logs_auditable_created_idx')) {
                $table->index(['auditable_type', 'auditable_id', 'created_at'], 'audit_logs_auditable_created_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_logs_user_created_idx');
            $table->dropIndex('audit_logs_auditable_created_idx');
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            $result = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name=? AND name=?", [$table, $indexName]);
            return count($result) > 0;
        }
        if ($driver === 'mysql') {
            $result = DB::select('SHOW INDEX FROM '.$table.' WHERE Key_name = ?', [$indexName]);
            return count($result) > 0;
        }
        return false;
    }
};

