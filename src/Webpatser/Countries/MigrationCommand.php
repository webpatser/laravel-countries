<?php

namespace Webpatser\Countries;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

class MigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Laravel-countries specifications.';

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct();

        $this->setLaravel($app);

        $this->laravel['view']->addNamespace('countries', __DIR__.'/../../views');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('');
        $this->info('The migration process will create a migration file and a seeder for the countries list');

        $this->line('');

        if (!$this->confirm("Proceed with the migration creation? [Yes|no]")) {
            return;
        }

        $this->line('');

        $this->info("Creating migration and seeder...");

        if ($this->createMigration()) {

            $this->line('');

            if (version_compare(app()->version(), '5.5.0', '<')) {
                $this->call('optimize', []);
            }

            $this->line('');

            $this->info("Migration successfully created!");

        } else {

            $this->error(
                "Could not create migration.\n Check the write permissions".
                " to the database/migrations directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the migration
     *
     * @return bool
     */
    protected function createMigration(): bool
    {
        $migrationFiles = [
            $this->laravel['path']."/../database/migrations/*_create_countries_table.php" => 'countries::generators.migration'
        ];

        $seconds = 0;

        foreach ($migrationFiles as $migrationFile => $outputFile) {

            if (count(glob($migrationFile)) !== 0) {
                continue;
            }

            $migrationFile = str_replace('*', date('Y_m_d_His', strtotime('+'.$seconds.' seconds')), $migrationFile);

            if (!$fs = fopen($migrationFile, 'x')) {
                return false;
            }

            $output = "<?php\n\n".$this->laravel['view']->make($outputFile)->render();

            fwrite($fs, $output);
            fclose($fs);

            $seconds++;
        }

        return $this->addSeeder();
    }

    /**
     * Add seeder file.
     *
     * @return bool
     */
    private function addSeeder(): bool
    {
        $seeder = $this->laravel['path']."/../database/seeds/CountriesTableSeeder.php";
        $output = "<?php\n\n".$this->laravel['view']->make('countries::generators.seeder')->render();

        if (file_exists($seeder)) {
            return true;
        }

        if (!$fs = fopen($seeder, 'x')) {
            return false;
        }

        fwrite($fs, $output);
        fclose($fs);

        return true;
    }
}
