<?php

namespace IramGutierrez\API\Generators;

use Illuminate\Support\Facades\File;

use IramGutierrez\API\Entities\APIResourceEntity as APIResource;



class RouteGenerator{

    public function generateAll()
    {
        $resources = APIResource::where('route' , true)->get();

        $router = $this->generateRouter($resources);

        $file = base_path().'/routes/web.php';

        $contentRoutes .= "\n";

        $delimiter = '/**  GENERATE BY iramgutierrez/laravel-resource-api DO NOT REMOVE **/';

        $contentRoutes = "";

        $contentRoutes .= $delimiter;

        $contentRoutes .= "\n";

        $contentRoutes .= "\n";

        /* NAMESPACES */

        foreach($router['namespaces'] as $namespace => $ns)
        {
            $contentRoutes .= '$app->group([\'namespace\' => \''.$namespace."'";

            $contentRoutes .=  '] , function() use ($app) {';

            $contentRoutes .= "\n";

            /* NAMESPACES PREFIXES */

            foreach($ns['prefixes'] as $prefix => $p)
            {
                $contentRoutes .= '    $app->group([\'prefix\' => \''.$prefix."'";

                $contentRoutes .=  '] , function() use ($app) {';

                $contentRoutes .= "\n";

                /* NAMESPACES PREFIXES MIDDLEWARE */

                foreach($p['middlewares'] as $middleware => $mw)
                {
                    $middleware = "['".str_replace(',' , "','" , $middleware)."']";

                    $contentRoutes .= '        $app->group([\'middleware\' => '.$middleware;

                    $contentRoutes .=  '] , function() use ($app)  {';

                    $contentRoutes .= "\n";

                    /* NAMESPACES PREFIXES MIDDLEWARE ROUTES */

                    foreach($mw['routes'] as $r => $route)
                    {
                        $contentRoutes .= "\n";
                        $contentRoutes .= "            /**  ".$route['path']." Resource **/";
                        $contentRoutes .= "\n";
                        $contentRoutes .= '            $app->get(\''.$route['path']."' , '".$route['controller']."@index');";
                        $contentRoutes .= "\n";
                        $contentRoutes .= '            $app->post(\''.$route['path']."' , '".$route['controller']."@store');";
                        $contentRoutes .= "\n";
                        $contentRoutes .= '            $app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
                        $contentRoutes .= "\n";
                        $contentRoutes .= '            $app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
                        $contentRoutes .= "\n";
                        $contentRoutes .= '            $app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
                        $contentRoutes .= "\n";
                        $contentRoutes .= "            /**  ".$route['path']." Resource **/";
                        $contentRoutes .= "\n";
                    }

                    /* NAMESPACES PREFIXES MIDDLEWARE ROUTES */

                    $contentRoutes .=  '        });';

                    $contentRoutes .= "\n";

                }

                /* NAMESPACES PREFIXES MIDDLEWARE */

                /* NAMESPACES PREFIXES ROUTES */

                foreach($p['routes'] as $r => $route)
                {
                    $contentRoutes .= "\n";
                    $contentRoutes .= "        /**  ".$route['path']." Resource **/";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->get(\''.$route['path']."' , '".$route['controller']."@index');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->post(\''.$route['path']."' , '".$route['controller']."@store');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= "        /**  ".$route['path']." Resource **/";
                    $contentRoutes .= "\n";
                }

                /* NAMESPACES PREFIXES ROUTES */

                $contentRoutes .=  '    });';

                $contentRoutes .= "\n";

            }

            /* NAMESPACES PREFIXES */

            /* NAMESPACES MIDDLEWARES */

            foreach($ns['middlewares'] as $middleware => $mw)
            {
                $middleware = "['".str_replace(',' , "','" , $middleware)."']";

                $contentRoutes .= '    $app->group([\'middleware\' => '.$middleware;

                $contentRoutes .=  '] , function()  use ($app) {';

                $contentRoutes .= "\n";

                /* NAMESPACES MIDDLEWARES ROUTES */

                foreach($mw['routes'] as $r => $route)
                {
                    $contentRoutes .= "\n";
                    $contentRoutes .= "        /**  ".$route['path']." Resource **/";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->get(\''.$route['path']."' , '".$route['controller']."@index');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->post(\''.$route['path']."' , '".$route['controller']."@store');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= "        /**  ".$route['path']." Resource **/";
                    $contentRoutes .= "\n";

                }

                /* NAMESPACES MIDDLEWARES ROUTES */

                $contentRoutes .=  '    });';

                $contentRoutes .= "\n";

            }

            /* NAMESPACES MIDDLEWARES */

            /* NAMESPACES ROUTES */

            foreach($ns['routes'] as $r => $route)
            {
                $contentRoutes .= "\n";
                $contentRoutes .= "    /**  ".$route['path']." Resource **/";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->get(\''.$route['path']."' , '".$route['controller']."@index');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->post(\''.$route['path']."' , '".$route['controller']."@store');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
                $contentRoutes .= "\n";
                $contentRoutes .= "    /**  ".$route['path']." Resource **/";
                $contentRoutes .= "\n";
            }

            /* NAMESPACES ROUTES */

            $contentRoutes .=  '});';

            $contentRoutes .= "\n";

        }

        /* NAMESPACES */

        /* PREFIXES */

        foreach($router['prefixes'] as $prefix => $p)
        {
            $contentRoutes .= '$app->group([\'prefix\' => \''.$prefix."'";

            $contentRoutes .=  '] , function()  use ($app) {';

            $contentRoutes .= "\n";

            /* PREFIXES MIDDLEWARES */

            foreach($p['middlewares'] as $middleware => $mw)
            {
                $middleware = "['".str_replace(',' , "','" , $middleware)."']";

                $contentRoutes .= '    $app->group([\'middleware\' => '.$middleware;

                $contentRoutes .=  '] , function()  use ($app) {';

                $contentRoutes .= "\n";

                /* PREFIXES MIDDLEWARES ROUTES */

                foreach($mw['routes'] as $r => $route)
                {
                    $contentRoutes .= "\n";
                    $contentRoutes .= "        /**  ".$route['path']." Resource **/";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->get(\''.$route['path']."' , '".$route['controller']."@index');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->post(\''.$route['path']."' , '".$route['controller']."@store');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= '        $app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
                    $contentRoutes .= "\n";
                    $contentRoutes .= "        /**  ".$route['path']." Resource **/";
                    $contentRoutes .= "\n";
                }

                /* PREFIXES MIDDLEWARES ROUTES */

                $contentRoutes .=  '    });';

                $contentRoutes .= "\n";

            }

            /* PREFIXES MIDDLEWARES */

            /* PREFIXES ROUTES */

            foreach($p['routes'] as $r => $route)
            {
                $contentRoutes .= "\n";
                $contentRoutes .= "    /**  ".$route['path']." Resource **/";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->get(\''.$route['path']."' , '".$route['controller']."@index');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->post(\''.$route['path']."' , '".$route['controller']."@store');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
                $contentRoutes .= "\n";
                $contentRoutes .= "    /**  ".$route['path']." Resource **/";
                $contentRoutes .= "\n";
            }

            /* PREFIXES ROUTES */

            $contentRoutes .=  '});';

            $contentRoutes .= "\n";


        }

        /* PREFIXES */

        /* MIDDLEWARES */

        foreach($router['middlewares'] as $middleware => $mw)
        {
            $middleware = "['".str_replace(',' , "','" , $middleware)."']";

            $contentRoutes .= '$app->group([\'middleware\' => '.$middleware;

            $contentRoutes .=  '] , function()  use ($app) {';

            $contentRoutes .= "\n";

            /* MIDDLEWARES ROUTES */

            foreach($mw['routes'] as $r => $route)
            {
                $contentRoutes .= "\n";
                $contentRoutes .= "    /**  ".$route['path']." Resource **/";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->get(\''.$route['path']."' , '".$route['controller']."@index');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->post(\''.$route['path']."' , '".$route['controller']."@store');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
                $contentRoutes .= "\n";
                $contentRoutes .= '    $app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
                $contentRoutes .= "\n";
                $contentRoutes .= "    /**  ".$route['path']." Resource **/";
                $contentRoutes .= "\n";
            }

            /* MIDDLEWARES ROUTES */

            $contentRoutes .=  '});';

            $contentRoutes .= "\n";
        }

        /* MIDDLEWARES */

        /* ROUTES */

        foreach($router['routes'] as $r => $route)
        {
            $contentRoutes .= "\n";
            $contentRoutes .= "/**  ".$route['path']." Resource **/";
            $contentRoutes .= "\n";
            $contentRoutes .= '$app->get(\''.$route['path']."' , '".$route['controller']."@index');";
            $contentRoutes .= "\n";
            $contentRoutes .= '$app->post(\''.$route['path']."' , '".$route['controller']."@store');";
            $contentRoutes .= "\n";
            $contentRoutes .= '$app->get(\''.$route['path']."/{id}' , '".$route['controller']."@show');";
            $contentRoutes .= "\n";
            $contentRoutes .= '$app->put(\''.$route['path']."/{id}' , '".$route['controller']."@update');";
            $contentRoutes .= "\n";
            $contentRoutes .= '$app->delete(\''.$route['path']."/{id}' , '".$route['controller']."@destroy');";
            $contentRoutes .= "\n";
            $contentRoutes .= "/**  ".$route['path']." Resource **/";
            $contentRoutes .= "\n";
        }

        /* ROUTES */

        $contentRoutes .= "\n";

        $contentRoutes .= $delimiter;

        $contentRoutes .= "\n";

        $contentRoutes .= "\n";

        $lines = file($file);

        $init = false;
        $preInit = false;
        $end = false;
        $content = "";


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

            return File::put($file , str_replace($content , $contentRoutes , file_get_contents($file)) );
        }
        else{
            return File::append($file, $contentRoutes);
        }



    }

    private function generateRouter($resources)
    {

        $routes = [
            'namespaces' => [],
            'prefixes' => [],
            'middlewares' => [],
            'routes' => []
        ];

        foreach($resources as $resource)
        {
            if(!empty($resource->namespace))
            {
                if(empty($routes['namespaces'][$resource->namespace]))
                {
                    $routes['namespaces'][$resource->namespace] = [
                        'prefixes' => [],
                        'middlewares' => [],
                        'routes' => []
                    ];
                }

                if(!empty($resource->prefix))
                {

                    if(empty($routes['namespaces'][$resource->namespace]['prefixes'][$resource->prefix]))
                    {
                        $routes['namespaces'][$resource->namespace]['prefixes'][$resource->prefix] = [
                            'middlewares' => [],
                            'routes' => []
                        ];
                    }

                    if(!empty($resource->middlewares))
                    {

                        if(empty($routes['namespaces'][$resource->namespace]['prefixes'][$resource->prefix]['middlewares'][$resource->middlewares]))
                        {
                            $routes['namespaces'][$resource->namespace]['prefixes'][$resource->prefix]['middlewares'][$resource->middlewares] = [
                                'routes' => []
                            ];
                        }

                        $routes['namespaces'][$resource->namespace]['prefixes'][$resource->prefix]['middlewares'][$resource->middlewares]['routes'][] = [
                            'path' => snake_case(str_plural($resource->base)),
                            'controller' => $resource->base.'Controller',
                            'documentation' => $resource->documentation
                        ];



                    }
                    else
                    {

                        $routes['namespaces'][$resource->namespace]['prefixes'][$resource->prefix]['routes'][] = [
                            'path' => snake_case(str_plural($resource->base)),
                            'controller' => $resource->base.'Controller',
                            'documentation' => $resource->documentation
                        ];

                    }

                }
                else if(!empty($resource->middlewares))
                {
                    if(empty($routes['namespaces'][$resource->namespace]['middlewares'][$resource->middlewares]))
                    {
                        $routes['namespaces'][$resource->namespace]['middlewares'][$resource->middlewares] = [
                            'routes' => []
                        ];
                    }

                    $routes['namespaces'][$resource->namespace]['middlewares'][$resource->middlewares]['routes'][] = [
                        'path' => snake_case(str_plural($resource->base)),
                        'controller' => $resource->base.'Controller',
                        'documentation' => $resource->documentation
                    ];
                }
                else
                {

                    $routes['namespaces'][$resource->namespace]['routes'][] = [
                        'path' => snake_case(str_plural($resource->base)),
                        'controller' => $resource->base.'Controller',
                        'documentation' => $resource->documentation
                    ];

                }


            }
            else if(!empty($resource->prefix))
            {

                if(empty($routes['prefixes'][$resource->prefix]))
                {
                    $routes['prefixes'][$resource->prefix] = [
                        'middlewares' => [],
                        'routes' => []
                    ];
                }

                if(!empty($resource->middlewares))
                {

                    if(empty($routes['prefixes'][$resource->prefix]['middlewares'][$resource->middlewares]))
                    {
                        $routes['prefixes'][$resource->prefix]['middlewares'][$resource->middlewares] = [
                            'routes' => []
                        ];
                    }

                    $routes['prefixes'][$resource->prefix]['middlewares'][$resource->middlewares]['routes'][] = [
                        'path' => snake_case(str_plural($resource->base)),
                        'controller' => $resource->base.'Controller',
                        'documentation' => $resource->documentation
                    ];



                }
                else
                {

                    $routes['prefixes'][$resource->prefix]['routes'][] = [
                        'path' => snake_case(str_plural($resource->base)),
                        'controller' => $resource->base.'Controller',
                        'documentation' => $resource->documentation
                    ];

                }

            }
            else if(!empty($resource->middlewares))
            {
                if(empty($routes['middlewares'][$resource->middlewares]))
                {
                    $routes['middlewares'][$resource->middlewares] = [
                        'routes' => []
                    ];
                }

                $routes['middlewares'][$resource->middlewares]['routes'][] = [
                    'path' => snake_case(str_plural($resource->base)),
                    'controller' => $resource->base.'Controller',
                    'documentation' => $resource->documentation
                ];
            }
            else
            {

                $routes['routes'][] = [
                    'path' => snake_case(str_plural($resource->base)),
                    'controller' => $resource->base.'Controller',
                    'documentation' => $resource->documentation
                ];

            }

        }

        return $routes;

    }
}