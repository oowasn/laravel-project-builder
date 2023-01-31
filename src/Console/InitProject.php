<?php

namespace ZDSLab\Init\Console;

// Models 
use App\Models\{User,};
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Filesystem\Filesystem;

class InitProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize project';

    public $dataTypes = array();
    public $dataRows = array();
    public $relationShip = array();

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Installing ZDSLab InitPackage...');

        $this->call('voyager:install');
        
        $this->info('Set Application route to ZDS routes into routes/web.php');
        
        copy(__DIR__.'/../../stubs/default/routes/web.php', base_path('routes/web.php'));

        $this->info('Pubish ZDS Controllers and helpers');

        $this->putFileInFolder(
            __DIR__.'/../../stubs/default/Http/Controllers', 
            app_path('Http/Controllers')
        );

        // $this->putFileInFolder(
        //     __DIR__.'/../../stubs/default/Http/Helpers', 
        //     app_path('Http/Controllers')
        // );

        $this->info('Publishing configuration...');

        $this->info('Installed ZDSLab InitPackage');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "ZDSLab\Init\InitServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

       $this->call('vendor:publish', $params);
    }

    private function putFileInFolder($source, $destination)
    {
        $scan = scandir($source);
        foreach($scan as $file) {
            if (!is_dir("$source/$file")) {
                copy(__DIR__."$source/$file", base_path("$destination/$file"));
            }
        }

        // foreach ($files as $name => $content) :
        //     $chemin =  $path . $name;
        //     $path_info = pathinfo($chemin);
        //     $dir = $path_info['dirname'];
        //     if (!file_exists($dir)) :
        //         mkdir($dir, 0775, true);
        //     endif;
        //     file_put_contents($chemin, trim($content));
        // endforeach;
    }

}
