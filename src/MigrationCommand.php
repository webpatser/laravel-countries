<?php

namespace Webpatser\Countries;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'countries:migration 
                            {--force : Overwrite existing migration files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates migration and seeder files for the countries package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // Add view namespace for stubs
        $stubsPath = base_path('vendor/webpatser/laravel-countries/stubs');
        if (!is_dir($stubsPath)) {
            // Fallback for development/local installations
            $stubsPath = dirname(__DIR__) . '/stubs';
        }
        View::addNamespace('countries', $stubsPath);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->newLine();
        $this->info('🌍 Laravel Countries Migration Generator');
        $this->line('This will create migration and seeder files for the countries package.');
        $this->newLine();

        if (!$this->confirm('Proceed with migration creation?', true)) {
            $this->warn('Migration creation cancelled.');
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('📝 Creating migration files...');

        if ($this->createMigrations()) {
            $this->newLine();
            $this->info('✅ Migration files created successfully!');
            $this->line('');
            $this->line('Next steps:');
            $this->line('1. Run: php artisan migrate');
            $this->line('2. Run: php artisan db:seed --class=CountriesSeeder');
            
            return Command::SUCCESS;
        } else {
            $this->newLine();
            $this->error('❌ Failed to create migration files.');
            $this->line('Please check write permissions in database/migrations and database/seeders directories.');
            
            return Command::FAILURE;
        }
    }

    /**
     * Create the migration files
     *
     * @return bool
     */
    protected function createMigrations(): bool
    {
        $migrationPath = database_path('migrations');
        $seederPath = database_path('seeders');
        
        // Ensure directories exist
        if (!is_dir($migrationPath) || !is_dir($seederPath)) {
            $this->error('Migration or seeder directories do not exist.');
            return false;
        }

        $timestamp = now();
        $migrations = [
            [
                'file' => $migrationPath . '/' . $timestamp->format('Y_m_d_His') . '_setup_countries_table.php',
                'stub' => 'countries::migration',
                'class' => 'SetupCountriesTable'
            ],
            [
                'file' => $migrationPath . '/' . $timestamp->addSecond()->format('Y_m_d_His') . '_optimize_countries_table.php',
                'stub' => 'countries::char_migration', 
                'class' => 'OptimizeCountriesTable'
            ]
        ];

        // Create migrations
        foreach ($migrations as $migration) {
            if (file_exists($migration['file']) && !$this->option('force')) {
                $this->warn("Migration file already exists: " . basename($migration['file']));
                continue;
            }

            try {
                $content = "<?php\n\n" . View::make($migration['stub'])->render();
                file_put_contents($migration['file'], $content);
                $this->line("Created: " . basename($migration['file']));
            } catch (\Exception $e) {
                $this->error("Failed to create migration: " . $e->getMessage());
                return false;
            }
        }

        // Create seeder
        $seederFile = $seederPath . '/CountriesSeeder.php';
        if (file_exists($seederFile) && !$this->option('force')) {
            $this->warn("Seeder file already exists: CountriesSeeder.php");
        } else {
            try {
                $content = "<?php\n\n" . View::make('countries::seeder')->render();
                file_put_contents($seederFile, $content);
                $this->line("Created: CountriesSeeder.php");
            } catch (\Exception $e) {
                $this->error("Failed to create seeder: " . $e->getMessage());
                return false;
            }
        }

        return true;
    }
}