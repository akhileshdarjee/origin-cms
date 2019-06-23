<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OriginMigrate extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'origin:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call migration and reset app modules';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('migrate');
        cache()->forget('app_modules');
    }
}
