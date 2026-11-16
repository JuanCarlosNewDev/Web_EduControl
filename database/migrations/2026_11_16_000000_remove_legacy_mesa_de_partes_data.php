<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['expediente_adjuntos', 'expediente_derivaciones', 'expedientes', 'estudiantes'] as $table) {
            Schema::dropIfExists($table);
        }

        foreach (['sessions', 'password_reset_tokens', 'users'] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->delete();
            }
        }

        if (Schema::hasTable('users')) {
            $legacyColumns = array_values(array_filter(
                ['is_active', 'oficina', 'office_number'],
                fn (string $column) => Schema::hasColumn('users', $column),
            ));

            if ($legacyColumns !== []) {
                Schema::table('users', function (Blueprint $table) use ($legacyColumns): void {
                    $table->dropColumn($legacyColumns);
                });
            }
        }

        if (Schema::hasTable('migrations')) {
            DB::table('migrations')->whereIn('migration', [
                '2026_09_08_000003_create_estudiantes_table',
                '2026_10_20_000001_add_role_to_users_table',
                '2026_10_20_000002_create_expedientes_table',
                '2026_10_20_000003_update_expedientes_columns',
                '2026_10_20_000004_add_codigo_to_expedientes_table',
                '2026_10_26_000001_create_expediente_adjuntos_table',
                '2026_10_26_000002_add_registration_details_to_expedientes_table',
                '2026_10_26_000003_add_applicant_review_to_expedientes_table',
                '2026_10_26_000004_create_expediente_derivaciones_table',
                '2026_10_26_000005_add_completion_to_expedientes_table',
                '2026_10_26_000006_add_contact_email_to_expedientes_table',
                '2026_10_26_000007_add_oficina_to_users_table',
                '2026_10_27_000001_add_type_to_expediente_derivaciones_table',
                '2026_10_27_000002_add_status_to_expediente_derivaciones_table',
                '2026_11_03_000001_add_account_status_and_office_number',
            ])->delete();
        }
    }

    public function down(): void
    {
        throw new RuntimeException('Los datos de Mesa de Partes eliminados por esta migración no se pueden restaurar.');
    }
};
