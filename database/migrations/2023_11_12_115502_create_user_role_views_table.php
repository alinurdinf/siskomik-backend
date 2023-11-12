<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW user_role_view AS 
                SELECT 
                    users.*, 
                    roles.name as role_name, 
                    roles.display_name  
                FROM role_user 
                INNER JOIN users ON users.id = role_user.user_id 
                INNER JOIN roles ON roles.id = role_user.role_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS user_role_view");
    }
};
