<?php

namespace Sukohi\QuickDict\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use Sukohi\QuickDict\QuickDict;

class QuickDictCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dict';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new migrations and seeds of master data.';

    private $_dict;
    private $_composer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_dict = new QuickDict;
        $this->_composer = new Composer(new Filesystem);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migration_results = $this->_dict->migrate();
        $seeder_results = $this->_dict->seed();

        foreach ($migration_results['created'] as $file) {

            $this->info('Created Migration: '. $file);

        }

        foreach ($migration_results['exists'] as $class_name) {

            $this->info("A {$class_name} class already exists.");

        }

        foreach ($seeder_results['created'] as $file) {

            $this->info('Created Seeder: '. $file);

        }

        foreach ($seeder_results['exists'] as $class_name) {

            $this->info("A {$class_name} class already exists.");

        }

        $this->_composer->dumpAutoloads();

        if(!empty($seeder_results['adding_tables'])) {

            $this->alert('Please add the followings to `DatabaseSeeder.php` if you would like to manage your master data through seeder command.');
            $this->info(implode("\n", $seeder_results['adding_classes']) ."\n");

            Artisan::call('migrate');

            foreach ($seeder_results['adding_tables'] as $adding_table) {

                $dict_values = config('quick_dict.'. $adding_table, []);
                $now = now();

                foreach ($dict_values as $key => $value) {

                    \DB::table($adding_table)->insert([
                        'key' => $key,
                        'value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

                }

            }

        }

        $this->info('Done!');
    }

}
