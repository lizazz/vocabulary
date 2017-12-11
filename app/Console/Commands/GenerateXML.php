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
        foreach ($xml as $userid => $currentuser) {
            $xmlDoc = new \DOMDocument("1.0", "utf-8");
            $root = $xmlDoc->createElement("users");
            $xmlDoc->appendChild($root);
            $user = $xmlDoc->createElement("user");
            $user->appendChild($xmlDoc->createElement("id", $userid));
            $user->appendChild($xmlDoc->createElement("email", $currentuser['email']));
            $user->appendChild($xmlDoc->createElement("ipaddress", $currentuser['ipaddress']));
            $user->appendChild($xmlDoc->createElement("browser", $currentuser['browser']));
            $user->appendChild($xmlDoc->createElement("country", $currentuser['country']));
            $user->appendChild($xmlDoc->createElement("cookie", $currentuser['cookie']));
            $vocabulary = $xmlDoc->createElement('vocabulary');
            foreach ($currentuser['vocabulary'] as $words) {
                $word = $xmlDoc->createElement("word");
                $word->appendChild($xmlDoc->createElement("original", $words['original']));
                $word->appendChild($xmlDoc->createElement("id", $words['id']));
                if(is_array($words['hashes']) && count($words['hashes']) > 0){
                    $hashes = $xmlDoc->createElement("hashes");
                    foreach ($words['hashes'] as $algoritm => $value) {
                        $hashes->appendChild($xmlDoc->createElement($algoritm, $value));
                    }
                    $word->appendChild($hashes);
                }
                $vocabulary->appendChild($word);
            }
            $user->appendChild($vocabulary);
            $root->appendChild($user);
            $xmlstring = $xmlDoc->saveXML();
            $xmlDoc->save($structure . DIRECTORY_SEPARATOR .'users_' . $userid . '.xml');
        }
    }
}
