<?php

namespace Iramgutierrez\API\Generators;

use Illuminate\Support\Facades\File;



class RouteGenerator{

    public function generate($base , $path , $prefix = '' , $middlewares = [])
    {
        $pathname = \Config::get('resource_api.path' , 'API');

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

        return File::append('app/Http/routes.php', $contentRoutes);
    }
}