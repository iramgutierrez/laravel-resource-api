<?php

namespace Iramgutierrez\API\Generators;

use Illuminate\Support\Facades\Artisan;
use Iramgutierrez\API\Entities\APIResourceEntity as APIResource;



class DocumentationGenerator{

    public function generateAll()
    {
        $resources = APIResource::where('route' , true)->get();

        $namespaces = $this->generateNamespaces($resources);

        $commands = [];

        foreach($namespaces as $namespace => $controllers)
        {

            $command = 'apidoc -i '.app_path().'/Http/Controllers/'.$namespace.' ';

            foreach($controllers as $controller)
            {
                $command .= '-f "'.$controller.'" ';
            }

            $command .= '-o '.public_path().'/docs/'.$namespace;

            $commands[] = [
                'namespace' => $namespace,
                'command' => $command
            ];
        }
        $return= [];

        $responses = [];

        foreach($commands as $command)
        {
            exec($command['command'] , $return , $status);

            if($status != 0)
            {
                $response = [
                    'success' => false,
                    'error' => 'Cannot generate documentation  for namespace '.$command['namespace'].' in: '.public_path().'/docs/'.$command['namespace']

                ];

            }
            else
            {
                $response = [
                    'success' => true,
                    'message' => 'Documentation generated for namespace '.$command['namespace'].' in: '.public_path().'/docs/'.$command['namespace']
                ];

            }

            $responses[] = $response;
        }

        return $responses;


    }

    private function generateNamespaces($resources)
    {

        $namespaces = [];

        foreach($resources as $resource)
        {
            if(!empty($resource->namespace))
            {
                if(empty($namespaces[$resource->namespace]))
                {
                    $namespaces[$resource->namespace] = [];
                }

                $namespaces[$resource->namespace][] = $resource->base.'Controller.php';


            }

        }

        return $namespaces;

    }
}