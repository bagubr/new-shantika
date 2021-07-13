<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api_request {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class';

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
        $dir = app_path().'/Http/Requests/Api';
        if(!is_dir($dir)) {
            mkdir($dir);
        }
        $name = $this->argument('name');
        $name = explode("/",$name);
        $name = $name[count($name) - 1];
        $filename = $name.'.php';
        $path = $dir.'/'.$filename;
        if(file_exists($path)) {
            $this->warn('File already exists');
            exit;
        }
        $content = '<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class '.$name.' extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
        ';
        file_put_contents($path, $content);
        $this->info('Request generated successfully!');
    }

}
