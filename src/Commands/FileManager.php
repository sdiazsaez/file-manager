<?php

namespace Larangular\FileManager\Commands;

use Illuminate\Console\Command;

class FileManager extends Command {

    protected $signature = 'file-manager:load';
    protected $description = 'Scrape and load missing UF values from last record date.';


    public function handle() {
    }
}
