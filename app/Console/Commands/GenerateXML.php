<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\XMLReport;

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
        $XMLReportObject = new XMLReport();
        $xml = $XMLReportObject->getXML();
        $structure = public_path('xml');
        $fp = fopen($structure . DIRECTORY_SEPARATOR .'SyncForJira.txt', "a+");      
        $debugstr = $xml;
        fwrite($fp, $debugstr." ");
    }
}
