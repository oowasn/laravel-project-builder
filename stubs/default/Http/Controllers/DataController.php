<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use ZDSLab\Init\Models\{
    Slider,
    Page,
};
use App\Models\User;

class DataController extends BaseController
{

    public static function index(){
        return self::makePage('home');
    }
    public static function makePage($slug = NULL){
        $slug = $slug ?? 'home';
        $query = Page::with(
            [
                'sections' => function($q){
                    $q->orderBy('rang', 'ASC');
                },
                'template',
                'keywords:word'
            ]
        );
        $page = $query->where('slug', strtolower($slug))->get()->toArray()[0] ?? [];
        if(!$page):
            $slug = 'home';
            $query = Page::with(
                [
                    'sections' => function($q){
                        $q->orderBy('rang', 'ASC');
                    },
                    'template',
                    'keywords:word'
                ]
            );
            $page = $query->where('slug', 'home')->get()->toArray()[0] ;
        endif;

        $data['template'] = $page['template']['view'] ?? 'master';
        $data['pageName'] = $page['name'] ?? env('APP_NAME');
        $data['pageTitle'] = $page['title'] ?? NULL;
        $data['page'] = $page;
        $data['pageKeywords'] = $page['keywords'] ?? NULL ;
        $data['pageDescription'] = $page['description'] ?? NULL ;
        $lesSections = $page['sections'] ?? [];
        $data['tabSection'] = [];
        $data['slug'] = $slug;

        foreach($lesSections AS $sect):
            $data['tabSection'][] = $sect['view'];
        endforeach;

        $data['tabSection'] = $page['sections'] ?? [];
        $data['data'] = self::getData($data['tabSection']);
        $data['tabSection'] = Arr::keyBy($data['tabSection'], 'view');
        
        return view('page', $data);
    }
        
    public static function getSlider(){
        return self::arrayByKey(Slider::with('boutons')->where('active', true)
            ->orderBy('rang', 'ASC')->get()->toArray());
    }
    
    public static function getData(array $sections): array {
        $data = [];
        foreach($sections AS $section):
            $method  = "get".ucfirst($section['view']);
            if(method_exists(self::class, $method)):
                $data[$section['view']] = call_user_func("self::$method") ;
            endif;
        endforeach;
        return $data;
    }

    public static function getThumbnailImage(string $image, $suffixe = 'cropped'): string{
        $extension = pathinfo($image)[ "extension"];
        return str_replace(".$extension", "-$suffixe.$extension", $image);
    }

    public static function arrayByKey(array $array, string $key = 'id'): array{
        $data = [];
        foreach($array AS $k => $r):
            if(array_key_exists($key, $r)):
                $data[$r[$key]] = $r;
            else:
            $data[$k] = $r;
            endif;
        endforeach;

        return $data;
    }

    public static function arrayGroupByKey(array $array, string $key = NULL): array{
        if(!$key):
            return $array;
        endif;
        
        $data = [];
        foreach($array AS $k => $r):
            if(array_key_exists($key, $r)):
                $data[$r[$key]][] = $r;
            else:
                $data[$k] = $r;
            endif;
        endforeach;
        
        return $data;
    }
}