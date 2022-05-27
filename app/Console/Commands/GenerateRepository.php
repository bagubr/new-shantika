<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

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
     * @return int
     */
    public function handle()
    {
        $dir = app_path().'/Repositories';
        if(!is_dir($dir)) {
            mkdir($dir);
        }
        $name = $this->argument('name');
        $filename = $name.'.php';
        $path = $dir.'/'.$filename;
        if(file_exists($path)) {
            $this->warn('File already exists');
            exit;
        }
        $content = "<?php

namespace App\Repositories;

class $name {

}
        ";
        file_put_contents($path, $content);
        $this->info('Repository generated successfully!');
    }

}
