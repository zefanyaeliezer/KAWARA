<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (! Schema::hasColumn('news', 'thumbnail_data')) {
                $table->longText('thumbnail_data')->nullable()->after('thumbnail');
            }

            if (! Schema::hasColumn('news', 'thumbnail_mime')) {
                $table->string('thumbnail_mime', 100)->nullable()->after('thumbnail_data');
            }
        });

        // Automatically migrate existing local thumbnail files into the database
        $existingNews = DB::table('news')
            ->whereNotNull('thumbnail')
            ->whereNull('thumbnail_data')
            ->get(['id', 'thumbnail']);

        foreach ($existingNews as $item) {
            $path = (string) $item->thumbnail;

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                continue;
            }

            if (Storage::disk('public')->exists($path)) {
                $contents = Storage::disk('public')->get($path);
                $mime = Storage::disk('public')->mimeType($path) ?: 'image/jpeg';

                DB::table('news')
                    ->where('id', $item->id)
                    ->update([
                        'thumbnail_data' => base64_encode($contents),
                        'thumbnail_mime' => $mime,
                        'thumbnail' => null,
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $columns = array_filter(
                ['thumbnail_data', 'thumbnail_mime'],
                fn (string $column) => Schema::hasColumn('news', $column)
            );

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
