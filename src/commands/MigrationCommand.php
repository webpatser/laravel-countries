<?php

namespace Webpatser\Countries;

use Illuminate\Console\Command;

class MigrationCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'countries:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Laravel-countries specifications.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $app = app();
        $app['view']->addNamespace('countries',substr(__DIR__,0,-8).'views');
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line('');
        $this->info('The migration process will create a migration file and a seeder for the countries list');

        $this->line('');

        if ( $this->confirm("Proceed with the migration creation? [Yes|no]") )
        {
            $this->line('');

            $this->info( "Creating migration and seeder..." );
            if( $this->createMigration( 'countries' ) )
            {
                $this->line('');

                $this->info( "Migration successfully created!" );
            }
            else{
                $this->error(
                    "Coudn't create migration.\n Check the write permissions".
                    " within the app/database/migrations directory."
                );
            }

            $this->line('');
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Create the migration
     *
     * @param  string $name
     * @return bool
     */
    protected function createMigration()
    {
        //Create the migration
        $app = app();
        $migrationFiles = [
            $this->laravel->path."/../database/migrations/*_setup_countries_table.php" => 'countries::generators.migration',
            $this->laravel->path."/../database/migrations/*_charify_countries_table.php" => 'countries::generators.char_migration',
        ];

        $seconds = 0;

        foreach ($migrationFiles as $migrationFile => $outputFile) {
            if (sizeof(glob($migrationFile)) == 0) {
                $migrationFile = str_replace('*', date('Y_m_d_His', strtotime('+' . $seconds . ' seconds')), $migrationFile);

                $fs = fopen($migrationFile, 'x');
                if ($fs) {
                    $output = "<?php\n\n" .$app['view']->make($outputFile)->with('table', 'countries')->render();

                    fwrite($fs, $output);
                    fclose($fs);
                } else {
                    return false;
                }

                $seconds++;
            }
        }

        //Create the seeder
        $seeder_file = $this->laravel->path."/../database/seeds/CountriesSeeder.php";
        $output = "<?php\n\n" .$app['view']->make('countries::generators.seeder')->render();

        if (!file_exists( $seeder_file )) {
            $fs = fopen($seeder_file, 'x');
            if ($fs) {
                fwrite($fs, $output);
                fclose($fs);
            } else {
                return false;
            }
        }

        return true;
    }
    
    /**
     * BC for older laravel versions
     */
    public function fire()
    {
        $this->handle();
    }
}
