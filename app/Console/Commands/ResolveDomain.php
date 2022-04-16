<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ResolveDomain extends Command
{
    protected $signature = 'domain:resolve {domain}';
    protected $description = 'Resolve a domain';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $domain = $this->argument('domain');
        $domain = "https://" . $domain;
        // send get request to domain
        Http::timeout(1)->get($domain);
    }
}
