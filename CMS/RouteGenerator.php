<?php

namespace CMS\Generators;

use Illuminate\Support\Facades\File;

use CMS\Entities\BaseEntity;

class RouteGenerator{

    public function generate($base , $path , $prefix = '' , $middlewares = [])
    {
        $contentRoutes = "Route::group(['namespace' => 'CMS'";

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