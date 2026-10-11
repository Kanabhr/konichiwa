<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Alter blog_comments.message from TEXT to VARCHAR(500).
     *
     * Safety check: aborts before any schema change if rows exist whose
     * message already exceeds 500 characters, listing the affected IDs.
     *
     * Requirements: 3.4, 3.5
     */
    public function up(): void
    {
        // CHAR_LENGTH() is MySQL/MariaDB; SQLite (used in tests) uses LENGTH().
        // Both functions measure byte-length for ASCII/Latin text and are functionally
        // equivalent for the overflow check performed here.
        $lengthFn  = DB::getDriverName() === 'sqlite' ? 'LENGTH' : 'CHAR_LENGTH';
        $oversized = DB::table('blog_comments')
            ->whereRaw("{$lengthFn}(message) > 500")
            ->get(['id']);

        if ($oversized->isNotEmpty()) {
            $ids   = $oversized->pluck('id')->implode(', ');
            $count = $oversized->count();

            $this->command->error(
                "Migration aborted: {$count} row(s) in blog_comments exceed 500 characters. IDs: {$ids}"
            );

            throw new \RuntimeException('Migration halted: oversized message rows detected.');
        }

        Schema::table('blog_comments', function (Blueprint $table) {
            $table->string('message', 500)->change();
        });
    }

    /**
     * Revert blog_comments.message back to TEXT.
     */
    public function down(): void
    {
        Schema::table('blog_comments', function (Blueprint $table) {
            $table->text('message')->change();
        });
    }
};
