<?php

namespace IramGutierrez\API\Generators;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;



class MigrationGenerator{

    public function generate($namespace , $base , $table)
    {
        if(!File::isDirectory(database_path('migrations').'/'.$namespace.'/'.$base))
        {
            if(!File::isDirectory(database_path('migrations').'/'.$namespace))
            {
              if(!File::makeDirectory(database_path('migrations').'/'.$namespace))
              {
                  return [
                      'success' => false,
                      'error' => 'Cannot create '.database_path('migrations').'/'.$namespace.' directory'
                  ];
              }

            }

            if(!File::makeDirectory(database_path('migrations').'/'.$namespace.'/'.$base))
            {
                return [
                    'success' => false,
                    'error' => 'Cannot create '.database_path('migrations').'/'.$namespace.'/'.$base.' directory'
                ];
            }

        }

        $nameMigration = 'create_'.$table.'_table_'.time();

        $migration = Artisan::call('make:migration', [
            'name' => $nameMigration,
            '--create' => $table,
            '--table' => $table,
            '--path' => 'database/migrations/'.$namespace.'/'.$base
        ]);

        $files = File::files(database_path('migrations').'/'.$namespace.'/'.$base);

        $fileMigration = end($files);

        $find = '$table->increments(\'id\');';

        $find .= "\n";

        $replace = $find;

        $replace .= '            $table->string(\'name\');';

        $replace .= "\n";

        File::put($fileMigration , str_replace($find , $replace , file_get_contents($fileMigration)) );

        return [
            'success' => true,
            'message' => 'Migration created in: '.$fileMigration
        ];
    }
}
