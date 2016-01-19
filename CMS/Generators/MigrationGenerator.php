<?php

namespace CMS\Generators;

class MigrationGenerator{

    public function generateDir($table)
    {
        $isDir = true;

        $success = false;

        $message = '';

        if(!is_dir('database/migrations/'.$table) && !mkdir('database/migrations/'.$table, 0766 , true))
        {
            $isDir = false;

            $message = 'No se pudo crear el directorio app/CMS/database/'.$this->table;

        }

        if($isDir)
        {
            $nameMigration = 'create_' . $table . '_table_' . time();

            $this->callSilent('make:migration', [
                'name' => $nameMigration,
                '--create' => $table,
                '--table' => $table,
                '--path' => 'database/migrations/' . $table
            ]);

            $success = true;

            $message = 'Migration created in: database/migrations/' . $table . '/' . $nameMigration . ',php';
        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }
}