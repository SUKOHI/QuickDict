<?php

namespace Sukohi\QuickDict\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Sukohi\QuickDict\QuickDict;

class QuickDictRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dict:refresh {table?} {--A|all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all master data';

    private $_dict;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_dict = new QuickDict;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table_names = $this->_dict->getTableNames();
        $target_table = $this->argument('table');
        $all_tables = $this->option('all');

        foreach ($table_names as $table) {

            if($target_table == $table || $all_tables) {

                if (\Schema::hasTable($table)) {

                    \DB::table($table)->truncate();
                    Artisan::call('db:seed', [
                        '--class' => $this->_dict->getSeederClassName($table)
                    ]);

                } else {

                    $this->error('Table ' . $table . ' doesn\'t exist');

                }

            }

        }

        $this->info('Done!');
    }

}
