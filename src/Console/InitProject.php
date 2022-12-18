<?php

namespace Zdslab\Laravelinit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use App\Models\User;

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
    protected $description = 'Initialiser le projet';
    public $dataTypes = [];
    public $dataRows = [];
    public $relationShip = [];
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
    public function handle(Filesystem $filesystem)
    {
        $this->info('Publishing the ZDSLab/LaravelInit assets, database, and config files');

        $this->call('vendor:publish', [
            '--provider' => Zdslab\Laravelinit\InitServiceProvider::class
        ]);


        // //Création des models
        // $this->initModels();
        // // Migration creation
        // $this->initMigrations();
        // //Voyager install
        // $this->initVoyager();
        // //Creation des controllers
        // $this->initController();
        // //Views
        // $this->initViews();
        // //init Bread
        // $this->initBread();
        // $this->updateFile($filesystem);
        return 0;
    }
    public function initData()
    {
        // $dataRows['boutons']
        // $dataRows['templates']
        // $dataRows['keywords']
        // $dataRows['sections']
        // $dataRows['sliders']
        // $dataRows['pages'] 
        // $dataRows['page_sections']


        $relationShip = [];

        $relation = [
            'display'       => ['width' => "6"],
            "model"         => "App\Models\Section",
            "table"         => "sections",
            "type"          => "belongsToMany",
            "column"        => "id",
            "key"           => "id",
            "label"         => "name",
            "pivot_table"   => "page_sections",
            "pivot"         => "1",
            "taggable"      => "0"
        ];
        $relationShip['pages'][] = ['field' => 'page_belongstomany_section_relationship', 'type' => 'relationship', 'display_name' => 'Sections', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 1, 'details' => json_encode($relation, JSON_FORCE_OBJECT), 'order' => 8];
        $relation["model"] = "App\Models\Keyword";
        $relation["pivot_table"] = "keyword_page";
        $relation["table"] = "keywords";
        $relation["taggable"] = "on";
        $relation["label"] = "word";
        $relationShip['pages'][] = ['field' => 'page_belongstomany_keyword_relationship', 'type' => 'relationship', 'display_name' => 'Mots cles', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 1, 'details' => json_encode($relation, JSON_FORCE_OBJECT), 'order' => 9];
        $relation["model"] = "App\Models\Template";
        $relation["pivot_table"] = "templates";
        $relation["table"] = "templates";
        $relation["type"] = "belongsTo";
        $relation["column"] = "template_id";
        $relation["taggable"] = "0";
        $relation["key"] = "id";
        $relation["label"] = "name";
        $relationShip['pages'][] = ['field' => 'page_belongsto_template_relationship', 'type' => 'relationship', 'display_name' => 'Template', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 1, 'details' => json_encode($relation, JSON_FORCE_OBJECT), 'order' => 1];
        $relation["model"] = "App\Models\Bouton";
        $relation["type"] = "belongsToMany";
        $relation["pivot_table"] = "bouton_slider";
        $relation["table"] = "boutons";
        $relation["column"] = "";
        $relation["taggable"] = "0";
        $relation["key"] = "id";
        $relation["label"] = "libelle";
        $relationShip['sliders'][] = ['field' => 'slider_belongstomany_bouton_relationship', 'type' => 'relationship', 'display_name' => 'Boutons', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 1, 'details' => json_encode($relation, JSON_FORCE_OBJECT), 'order' => 10];
        // $this->dataTypes = $dataTypes;
        // $this->dataRows = $dataRows;
        $this->relationShip = $relationShip;
    }

    public function initModels()
    {
        // $models = [
        //     'Keyword.php' => $Keyword,
        //     'Template.php' => $Template,
        //     'Bouton.php' => $Bouton,
        //     'Page.php' => $Page,
        //     'PageSection.php' => $PageSection,
        //     'Section.php' => $Section,
        //     'Slider.php' => $Slider
        // ];

        // $this->putFileInFolder($models, app_path("Models/"));
    }

    public function initVoyager()
    {
        $this->call('voyager:install');
    }

    public function initMigrations()
    {
        $this->info('Début de la migration');
        
        $lesMigrations = [
            "100" => 'templates',
            "200" => 'pages',
            "300" => 'sections',
            "400" => 'page_sections',
            "500" => 'keywords',
            "600" => 'keyword_page',
            "700" => 'sliders',
            "800" => 'boutons',
            "900" => 'bouton_slider'
        ];
        // $this->putFileInFolder($migrations, base_path('database/migrations/'));
        $this->info('Fin de la migration');
    }

    public function initHelper()
    {
        // $this->putFileInFolder($files, app_path('Helpers/'));
    }

    public function initController()
    {
        // $controllers = [
        //     'DataController.php' => $dataController,
        //     'PageSectionOrderController.php' => $pageSectionOrderController,
        // ];

        // $this->putFileInFolder($controllers, app_path('Http/Controllers/'));
    }
    function putFileInFolder($files, $path)
    {
        foreach ($files as $name => $content) :
            $chemin =  $path . $name;
            $path_info = pathinfo($chemin);
            $dir = $path_info['dirname'];
            if (!file_exists($dir)) :
                mkdir($dir, 0775, true);
            endif;
            file_put_contents($chemin, trim($content));
        endforeach;
    }

    public function initViews()
    {
        // $this->putFileInFolder($views, base_path('resources/views/'));
    }


    public function initBread()
    {
        $this->initData();
        $data = [];
        $dataMenus = [];
        $menus = ['keywords', 'boutons', 'templates', 'pages' . 'sliders', 'sections'];
        $pasDeMenus = ['page_sections'];
        foreach ($this->dataTypes as $tp) :
            $data[$tp['name']] = [];
            $pid = DB::table('data_types')
                ->insertGetId($tp);
            $data[$tp['name']]['id'] = $pid;
            if (array_key_exists($tp['name'], $this->dataRows)) :
                foreach ($this->dataRows[$tp['name']] as $tr) :
                    $tr['data_type_id'] = $pid;
                    DB::table('data_rows')
                        ->insert($tr);
                endforeach;
            endif;
            if (!in_array($tp['name'], $pasDeMenus)) :
                $dataMenus[] = [
                    'title' => $tp['display_name_singular'],
                    'url' => '',
                    'route' => "voyager.$tp[name].index",
                    'icon_class' => $tp['icon']
                ];
            endif;
            $this->permission($tp['name']);
        endforeach;
        //dump($data);
        //*
        foreach ($this->relationShip as $k => $tab) :
            foreach ($tab as $r) :
                $r['data_type_id'] = $data[$k]['id'];
                DB::table('data_rows')
                    ->insert($r);
            endforeach;
        endforeach;
        //*/
        $this->menuItem($dataMenus);
        $create = $this->ask('Voulez-vous creer un Utilisateur? Oui => 1 Non => 0');
        $this->createUser(boolval($create));
        $this->createLoginImage();
        $this->makeFirstPage();
        $this->info('Fin de l\'installation');
    }

    public function menuItem($menuTab)
    {
        $pid = DB::table('menu_items')
            ->insertGetId([
                "menu_id" => 1,
                "title" => "Portail",
                'url' => "",
                'target' => "_self",
                'icon_class' => "voyager-home",
                'parent_id' => NULL,
                'order'    => 2
            ]);
        $order = 1;
        foreach ($menuTab as $m) :
            $m['target'] = "_self";
            $m['menu_id'] = 1;
            $m['parent_id'] = $pid;
            $m['order'] = $order++;
            DB::table('menu_items')
                ->insert($m);
        endforeach;
    }
    public function makeFirstPage()
    {
        $tid = DB::table('templates')
            ->insertGetId([
                'name' => "Master",
                'vue' => "master"
            ]);
        $sid = DB::table('sections')
            ->insertGetId([
                'name' => "About",
                'vue' => "about",
                "title" => "A propos de Woody Builder"
            ]);

        $pid = DB::table('pages')
            ->insertGetId([
                'name' => "Home",
                'slug' => "home",
                "title" => "Woody Builder",
                "template_id" => $tid
            ]);
        DB::table('page_sections')
            ->insert(
                [
                    'page_id' => $pid,
                    'section_id' => $sid,
                    'rang' => 1
                ]
            );
    }
    public function permission($name)
    {
        $bread = ['browse', 'read', 'edit', 'add', 'delete'];
        $date = date('Y-m-d H:i:s');
        foreach ($bread as $breadItem) {
            $pid = DB::table('permissions')
                ->insertGetId([
                    "key" => $breadItem . "_" . $name,
                    "table_name" => $name,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            DB::table('permission_role')
                ->insert([
                    'permission_id' => $pid,
                    'role_id' => 1,
                ]);
        }
    }

    protected function createUser($create = false)
    {
        if ($create) :
            $email = $this->ask('Entrez votre email');
            $name = $this->ask('Entrez votre Nom');
            $password = $this->secret('Entrez votre mot de passe');
            $confirmPassword = $this->secret('Confirmez votre mot de passe');
            if ($password == $confirmPassword) :
                $newUser = new User;
                $newUser->name = $name;
                $newUser->role_id = 1;
                $newUser->email = $email;
                $newUser->password = Hash::make($password); //bcrypt($password) ;
                $newUser->save();
                $this->info('Utilisateur cree avec succes');
            else :
                $this->info('Les mots de passe saisis ne concordent pas');
            endif;
        endif;
    }
    public function updateFile(Filesystem $filesystem)
    {
        $this->initHelper();
        $this->updateRoute($filesystem);
        $this->updateConfigVoyager($filesystem);
        $this->updateComposerJson($filesystem);
    }
    public function updateRoute(Filesystem $filesystem)
    {
        $path = base_path('routes/web.php');
        $search = 'use Illuminate\Support\Facades\Route;';
        $replace = $search . PHP_EOL . "use App\Http\Controllers\DataController;" . PHP_EOL . "use TCG\Voyager\Facades\Voyager;" . PHP_EOL . "use App\Http\Controllers\PageSectionOrderController;" . PHP_EOL . PHP_EOL;
        $filesystem->replaceInFile($search, $replace, $path);

        $search = "Route::group(['prefix' => 'admin'], function () {";
        $replace = PHP_EOL . "Route::get('/', [DataController::class, 'index'])->name('home');" . PHP_EOL . " Route::get('/{slug}.html', [DataController::class, 'makePage'])" . PHP_EOL . "->where('slug', '[A-Za-z-]+')" . PHP_EOL . "->name('page');" . PHP_EOL . PHP_EOL . $search;
        $filesystem->replaceInFile($search, $replace, $path);

        $search = 'Voyager::routes();';
        $replace = $search . PHP_EOL . "Route::get('page-sections/order/{page_id?}', [PageSectionOrderController::class, 'order'])->name('voyager.page-sections.order');" . PHP_EOL . PHP_EOL;
        $filesystem->replaceInFile($search, $replace, $path);
    }

    public function updateConfigVoyager(Filesystem $filesystem)
    {
        $path = base_path('config/voyager.php');
        $search = "// 'namespace' => 'App\\\\Models\\\\',";
        $replace = " 'namespace' => 'App\\\\Models\\\\',";
        $filesystem->replaceInFile($search, $replace, $path);
    }
    public function updateComposerJson(Filesystem $filesystem)
    {
        $path = base_path('composer.json');
        $composerjson = json_decode(file_get_contents($path));
        $composerjson->autoload->files = ["app/Helpers/helpers.php"];
        $filesystem->put($path, json_encode($composerjson, JSON_UNESCAPED_SLASHES));
    }
    protected function createLoginImage()
    {
        // $loginDir = "public/login-assets";
        // if (!is_dir($loginDir)) :
        //     mkdir($loginDir, 0775, true);
        // endif;
        // file_put_contents("$loginDir/vz-logo.png", $binary);
        // file_put_contents("$loginDir/bg.png", $binary);
    }
}
