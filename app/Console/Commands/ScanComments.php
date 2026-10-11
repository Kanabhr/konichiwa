<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class ScanComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan blog_comments for HTML, URLs, and image references without modifying data.';

    /**
     * Execute the console command.
     *
     * This command is strictly read-only — it never modifies, deletes, or writes any data.
     */
    public function handle(): int
    {
        try {
            $total = DB::table('blog_comments')->count();

            $htmlCount = DB::table('blog_comments')
                ->whereRaw("message REGEXP '<[a-zA-Z]'")
                ->count();

            $urlCount = DB::table('blog_comments')
                ->whereRaw("message REGEXP 'https?://|www\\.'")
                ->count();

            $imageCount = DB::table('blog_comments')
                ->whereRaw("message REGEXP '\\.(svg|png|jpe?g|gif|webp|bmp|ico|avif|tiff)'")
                ->count();
        } catch (Throwable $e) {
            $this->error('Failed to scan blog_comments: '.$e->getMessage());

            return 1;
        }

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total rows', $total],
                ['Rows with HTML tags', $htmlCount],
                ['Rows with URLs', $urlCount],
                ['Rows with image refs', $imageCount],
            ]
        );

        return 0;
    }
}
