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

        try {

            $this->createMigration();

            $this->line('');

            if (version_compare(app()->version(), '5.5.0', '<')) {
                $this->call('optimize', []);
            }

            $this->line('');

            $this->info("Migration successfully created!");

        } catch (MigrationFailedException $exception) {

            $this->error($exception->getMessage());

        }

        $this->line('');
    }

    /**
     * Create the migration
     *
     * @return void
     * @throws \Webpatser\Countries\MigrationFailedException
     */
    protected function createMigration(): void
    {
        $seconds = 0;

        foreach ($this->migrationFiles() as $migrationFile => $outputFile) {

            if (count(glob($migrationFile)) !== 0) {
                continue;
            }

            $this->addMigrationFile($this->migrationFileName($migrationFile, $seconds), $outputFile);

            $seconds++;
        }

        $this->addSeeder();
    }

    /**
     * Get migration files.
     *
     * @return array
     */
    private function migrationFiles(): array
    {
        return [
            $this->laravel['path']."/../database/migrations/*_create_countries_table.php" => 'countries::generators.migration'
        ];
    }

    /**
     * Get name of the migration file.
     *
     * @param  string  $migrationFile
     * @param  int  $seconds
     * @return string
     */
    private function migrationFileName(string $migrationFile, int $seconds): string
    {
        return str_replace('*', date('Y_m_d_His', strtotime('+'.$seconds.' seconds')), $migrationFile);
    }

    /**
     * Add new migration file.
     *
     * @param  string  $migrationFile
     * @param  string  $outputFile
     * @return void
     * @throws \Webpatser\Countries\MigrationFailedException
     */
    private function addMigrationFile(string $migrationFile, string $outputFile): void
    {
        if (!$fs = fopen($migrationFile, 'x')) {
            throw new MigrationFailedException;
        }

        $output = "<?php\n\n".$this->laravel['view']->make($outputFile)->render();

        fwrite($fs, $output);
        fclose($fs);
    }

    /**
     * Add seeder file.
     *
     * @return void
     * @throws \Webpatser\Countries\MigrationFailedException
     */
    private function addSeeder(): void
    {
        $seeder = $this->laravel['path']."/../database/seeds/CountriesTableSeeder.php";
        $output = "<?php\n\n".$this->laravel['view']->make('countries::generators.seeder')->render();

        if (file_exists($seeder)) {
            throw new MigrationFailedException;
        }

        if (!$fs = fopen($seeder, 'x')) {
            throw new MigrationFailedException;
        }

        fwrite($fs, $output);
        fclose($fs);
    }
}
