<?php

namespace Iramgutierrez\API\Generators;

use Illuminate\Support\Facades\File;



class RouteGenerator{

    public function generate($base , $path , $prefix = '' , $middlewares = [])
    {
        $pathname = \Config::get('resource_api.path' , 'API');

        $file = app_path().'/Http/routes.php';

        $delimiter = '/**  GENERATE BY iramgutierrez/laravel-resource-api DO NOT REMOVE **/';

        $contentRoutes = "Route::group(['namespace' => '".$pathname."'";

        if($prefix)
        {
            $contentRoutes .= ", 'prefix' => '".$prefix."'";
        }

        if(count($middlewares))
        {
            $contentRoutes .= ", 'middleware' => [";

            foreach($middlewares as $w => $middleware)
            {
                if($w > 0)
                {
                    $contentRoutes .= ",";
                }

                $contentRoutes .= "'".$middleware."'";
            }

            $contentRoutes .= "]";
        }

        $contentRoutes .= "], function () {\n";

        $contentRoutes .="  ";

        $contentRoutes .= "Route::resource('".$path."' , '".$base."Controller');\n";

        $contentRoutes .= "});";

        $contentRoutes .= "\n";

        $contentRoutes = "\n".$delimiter."\n\n".$contentRoutes."\n".$delimiter."\n";

        //return File::append('app/Http/routes.php', $contentRoutes);

        $lines = file($file);

        $init = false;
        $preInit = false;
        $end = false;
        $content = "\n";


        foreach($lines as $l =>  $line)
        {
            if($preInit)
            {
                $init = true;
            }

            if(strpos($line, $delimiter) !== false)
            {
                $preInit = true;
            }

            if(($init || $preInit) && !$end)
            {
                $content .= $line;
            }

            if(strpos($line, $delimiter) !== false && $init)
            {
                $end = true;
            }
        }

        if($init && $end)
        {

            //$content = "\n".$delimiter."\n".$content.$delimiter."\n";
            //dd($content);
            return File::put($file , str_replace($content , $contentRoutes , file_get_contents($file)) );
        }
        else{
            return File::append($file, $contentRoutes);
        }

    }
}