<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE `profile_business` MODIFY `emp_count` VARCHAR(50) NULL');
        DB::statement('ALTER TABLE `profile_business` MODIFY `entity_type` VARCHAR(80) NULL');
        DB::statement('ALTER TABLE `profile_business` MODIFY `business_type` VARCHAR(80) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE `profile_business` MODIFY `emp_count` INT NULL');
        DB::statement('ALTER TABLE `profile_business` MODIFY `entity_type` INT NULL');
        DB::statement('ALTER TABLE `profile_business` MODIFY `business_type` INT NULL');
    }
};
