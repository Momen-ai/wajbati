<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImagesAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:audit {--fix : Delete invalid or unsafe files and their DB records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit storage/app/public and the images table for files with invalid extensions or corrupt image headers';

    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fix = (bool) $this->option('fix');
        $disk = Storage::disk('public');
        $allFiles = $disk->allFiles();

        $invalidFiles = [];
        $this->info('Scanning '.count($allFiles).' file(s) in public disk...');

        foreach ($allFiles as $file) {
            $filename = basename($file);
            if (str_starts_with($filename, '.')) {
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (! in_array($ext, $this->allowedExtensions, true)) {
                $invalidFiles[] = [
                    'path' => $file,
                    'reason' => "Invalid extension: .{$ext}",
                ];

                continue;
            }

            $fullPath = $disk->path($file);
            $info = @getimagesize($fullPath);
            if ($info === false) {
                $invalidFiles[] = [
                    'path' => $file,
                    'reason' => 'Failed binary image decode (corrupt or disguised non-image file)',
                ];
            }
        }

        // Also check DB references
        $corruptDbRecords = [];
        $images = Image::all();
        foreach ($images as $img) {
            if (! $disk->exists($img->image_path)) {
                $corruptDbRecords[] = [
                    'id' => $img->id,
                    'path' => $img->image_path,
                    'reason' => 'Referenced file does not exist on disk',
                ];
            } else {
                $ext = strtolower(pathinfo($img->image_path, PATHINFO_EXTENSION));
                if (! in_array($ext, $this->allowedExtensions, true)) {
                    $corruptDbRecords[] = [
                        'id' => $img->id,
                        'path' => $img->image_path,
                        'reason' => "Invalid DB record extension: .{$ext}",
                    ];
                }
            }
        }

        if (empty($invalidFiles) && empty($corruptDbRecords)) {
            $this->info('Audit complete: All files and DB image records are healthy and secure.');

            return Command::SUCCESS;
        }

        $this->warn('Found '.count($invalidFiles).' invalid file(s) on disk:');
        foreach ($invalidFiles as $item) {
            $this->line(" - {$item['path']} ({$item['reason']})");
        }

        $this->warn('Found '.count($corruptDbRecords).' invalid database image record(s):');
        foreach ($corruptDbRecords as $record) {
            $this->line(" - ID {$record['id']}: {$record['path']} ({$record['reason']})");
        }

        if ($fix) {
            $this->info('Applying fix: deleting invalid files and DB records...');

            foreach ($invalidFiles as $item) {
                $disk->delete($item['path']);
                Image::where('image_path', $item['path'])->delete();
            }

            foreach ($corruptDbRecords as $record) {
                Image::where('id', $record['id'])->delete();
            }

            $this->info('Fix complete.');
        } else {
            $this->comment('Run with --fix to automatically delete invalid files and their DB records.');
        }

        return Command::SUCCESS;
    }
}
