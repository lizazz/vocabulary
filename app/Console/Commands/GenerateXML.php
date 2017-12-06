<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateXML extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bash:generateXML';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $structure = public_path('xml');
        $fp = fopen($structure . DIRECTORY_SEPARATOR .'SyncForJira.txt', "a+");      
        $debugstr = 'hello';
        fwrite($fp, $debugstr." ");
    }
}
