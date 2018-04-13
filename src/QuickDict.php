<?php

namespace Sukohi\QuickDict;

use Illuminate\Filesystem\Filesystem;

class QuickDict {

    private $_files;

    public function __construct()
    {
        $this->_files = new Filesystem;
    }

    public function migrate() {

        $table_names = $this->getTableNames();
        $created = [];
        $exists = [];

        foreach ($table_names as $table) {

            $name = $this->getMigrationName($table);

            if(!$this->has_migration($name)) {

                $stub = $this->getMigrationStab($table);
                $path = database_path('migrations') .'/'. date('Y_m_d_His') .'_' .$name. '.php';
                $this->_files->put($path, $stub);
                $created[] = pathinfo($path, PATHINFO_FILENAME);

            } else {

                $exists[] = studly_case($name);

            }

        }

        return [
            'created' => $created,
            'exists' => $exists
        ];

    }

    public function seed() {

        $table_names = $this->getTableNames();
        $created = [];
        $exists = [];
        $adding_classes = [];
        $adding_tables = [];

        foreach ($table_names as $table) {

            $class_name = $this->getSeederClassName($table);
            $seeder_path = database_path('seeds/'. $class_name .'.php');

            if(!$this->_files->exists($seeder_path)) {

                $stub = $this->getSeederStab($table);
                $this->_files->put($seeder_path, $stub);
                $created[] = pathinfo($seeder_path, PATHINFO_FILENAME);
                $adding_classes[] = $class_name;
                $adding_tables[] = $table;

            } else {

                $exists[] = $class_name;

            }

        }

        return [
            'created' => $created,
            'exists' => $exists,
            'adding_classes' => $adding_classes,
            'adding_tables' => $adding_tables,
        ];

    }

    public function getTableNames() {

        $master_data = $this->getMasterData();
        return array_keys($master_data);

    }

    public function getMigrationName($table) {

        return 'create_'. $table .'_table';

    }

    public function getMigrationClassName($table) {

        return studly_case($this->getMigrationName($table));

    }

    public function getSeederName($table) {

        return $table .'_table_seeder';

    }

    public function getSeederClassName($table) {

        return studly_case($this->getSeederName($table));

    }

    private function getMigrationStab($table) {

        $stub = $this->_files->get(__DIR__ .'/stubs/migration.stub');
        $targets = [
            'DummyClass',
            'DummyTable'
        ];
        $replacements = [
            $this->getMigrationClassName($table),
            $table
        ];
        return str_replace($targets, $replacements, $stub);

    }

    private function getSeederStab($table) {

        $stub = $this->_files->get(__DIR__ .'/stubs/seeder.stub');
        $targets = [
            'DummyClass',
            'DummyTable'
        ];
        $replacements = [
            $this->getSeederClassName($table),
            $table
        ];
        return str_replace($targets, $replacements, $stub);

    }

    private function getMigrationFilenames() {

        $filenames = [];
        $files = $this->_files->files(database_path('migrations'));

        foreach ($files as $file) {

            $filenames[] = $file->getFilename();

        }

        return $filenames;

    }

    private function has_migration($name) {

        $migration_filenames = $this->getMigrationFilenames();

        foreach ($migration_filenames as $migration_filename) {

            if(str_contains($migration_filename, $name)) {

                return true;

            }

        }

        return false;

    }

    private function getMasterData() {

        return config('quick_dict', []);

    }
}