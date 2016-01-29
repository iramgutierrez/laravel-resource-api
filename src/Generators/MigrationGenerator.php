<?php

namespace IramGutierrez\API\Generators;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;



class MigrationGenerator{

    public function generate($base , $table)
    {
        if(!File::isDirectory(database_path('migrations').'/'.$base))
        {

            if(!File::makeDirectory(database_path('migrations').'/'.$base))
            {
                return [
                    'success' => false,
                    'error' => 'Cannot create '.database_path('migrations').'/'.$base.' directory'
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
            'message' => 'Migration created in: '.database_path('migrations').'/'.$base.'/'.$nameMigration.'.php'
        ];
    }
}