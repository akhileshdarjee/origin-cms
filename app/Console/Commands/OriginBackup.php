<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class OriginBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'origin:backup 
                            {--d|only-db : Backup only database} 
                            {--f|only-files : Backup only files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database & all user uploaded files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->backup_name = Str::random(40);

        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('app/backups/' . $this->backup_name . '.sql')
        ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $enable_backups = DB::table('oc_settings')
                ->select('field_name', 'field_value')
                ->where('field_name', 'enable_backups')
                ->where('owner', 'admin')
                ->first();

            if ($enable_backups) {
                if (intval($enable_backups->field_value)) {
                    if ($this->option('only-db') && $this->option('only-files')) {
                        $this->error('Please use only one option, -d for database or -f for files');
                    } else {
                        if ($this->option('only-db') || $this->option('only-files')) {
                            if ($this->option('only-db')) {
                                $this->process->mustRun(); // backup mysql database
                            } elseif ($this->option('only-files')) {
                                $this->backupFiles(); // backup user uploaded files
                            }
                        } else {
                            $this->process->mustRun(); // backup mysql database
                            $this->backupFiles(true); // backup user uploaded files
                        }

                        $this->info('Backup has been created successfully.');
                    }
                } else {
                    $this->error('Backups are not enabled. Please enable it from Settings');
                }
            } else {
                $this->error('Backups are not enabled. Please enable it from Settings');
            }
        } catch (ProcessFailedException $e) {
            logger()->error('Backup process failed: ' . $e->getMessage());
            $this->error('Backup process has been failed.');
        }
    }

    public function backupFiles($db = false)
    {
        $source = storage_path('app/public/uploads');
        $destination = storage_path('app/backups/' . $this->backup_name . '.zip');

        if (extension_loaded('zip') === true) {
            if (file_exists($source) === true) {
                $zip = new \ZipArchive();

                if ($zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($source),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    $files->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($source) + 1);

                            if ($zip->addFile($filePath, 'uploads/' . $relativePath)) {
                                continue;
                            }
                        }
                    }

                    if ($db && $zip->addEmptyDir('db')) {
                        $db_backup_file = storage_path('app/backups/' . $this->backup_name . '.sql');
                        $zip->addFile($db_backup_file, 'db/' . $this->backup_name . '.sql');
                        $zip->setArchiveComment('Database & Files');
                    } else {
                        $zip->setArchiveComment('Files');
                    }

                    $zip->close();

                    if ($db && file_exists(storage_path('app/backups/' . $this->backup_name . '.sql'))) {
                        unlink(storage_path('app/backups/' . $this->backup_name . '.sql'));
                    }
                }
            }
        }
    }
}
