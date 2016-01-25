<?php

namespace Iramgutierrez\API\Generators;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;



class MigrationGenerator{

    public function generate($base , $table)
    {

        if(!File::isDirectory('database/migrations/'.$base))
        {

            if(!File::makeDirectory('database/migrations/'.$base))
            {
                return [
                    'success' => false,
                    'error' => 'Cannot create database/migrations/'.$base.' directory'
                ];
            }

        }

        $nameMigration = 'create_'.$table.'_table_'.time();

        Artisan::call('make:migration', [
            'name' => $nameMigration,
            '--create' => $table,
            '--table' => $table,
            '--path' => 'database/migrations/'.$base
        ]);

        return [
            'success' => true,
            'message' => 'Migration created in: database/migrations/'.$base.'/'.$nameMigration.',php'
        ];

        //$this->info('Migration created in: database/migrations/'.$base.'/'.$nameMigration.',php');

        //return File::append('app/Http/routes.php', $contentRoutes);
    }
}