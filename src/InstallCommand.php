<?php

namespace Webpatser\Countries;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The console command signature.
     */
    protected $signature = 'countries:install 
                            {--table-name= : Custom table name for countries}
                            {--force : Force installation and overwrite existing files}
                            {--no-migration : Skip migration generation}
                            {--no-seeder : Skip seeder generation}';

    /**
     * The console command description.
     */
    protected $description = 'Install Laravel Countries package with database integration';

    /**
     * Installation configuration
     */
    private array $config = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->displayWelcome();
        
        if (!$this->gatherConfiguration()) {
            $this->warn('Installation cancelled.');
            return Command::FAILURE;
        }

        // Publish config if not already done during the flow
        $this->newLine();
        $this->line('📝 Publishing configuration file...');
        if (!$this->publishConfig()) {
            $this->error('❌ Failed to publish config');
            return Command::FAILURE;
        }
        $this->info('✅ Configuration published');

        // Always try to display installed countries if there's data in the database
        $this->displayInstalledCountries();

        $this->displaySuccess();
        return Command::SUCCESS;
    }

    /**
     * Display welcome message
     */
    private function displayWelcome(): void
    {
        $this->newLine();
        $this->line('┌─────────────────────────────────────────────────────┐');
        $this->line('│                                                     │');
        $this->line('│      🌍 Laravel Countries Database Installer       │');
        $this->line('│                                                     │');
        $this->line('│  Import all 249 countries from JSON into your      │');
        $this->line('│  database for fast queries and relationships       │');
        $this->line('│                                                     │');
        $this->line('└─────────────────────────────────────────────────────┘');
        $this->newLine();
    }

    /**
     * Gather configuration and execute steps immediately
     */
    private function gatherConfiguration(): bool
    {
        $this->info('📋 Database Setup');
        $this->newLine();

        // Step 1: Get table name first
        $defaultTableName = 'countries';
        $tableName = $this->option('table-name') ?? $this->ask(
            'What should be the database table name?', 
            $defaultTableName
        );

        // Validate table name
        if (!$this->isValidTableName($tableName)) {
            $this->error('Invalid table name. Please use only letters, numbers, and underscores.');
            return false;
        }

        $this->config['table_name'] = $tableName;
        $this->config['cache_ttl'] = 0;
        $this->config['data_source'] = 'database';
        $this->config['generate_migration'] = true;
        $this->config['generate_seeder'] = true;

        // Check what's already set up
        $tableExists = Schema::hasTable($tableName);
        $hasData = $tableExists ? \DB::table($tableName)->count() > 0 : false;

        $this->newLine();
        
        if ($hasData) {
            $count = \DB::table($tableName)->count();
            $this->info("✅ Database already contains {$count} countries");
            if (!$this->confirm('Do you want to reimport the data?', false)) {
                $this->config['run_migrations'] = false;
                $this->config['run_seeder'] = false;
                return true; // Skip to config publishing
            }
        }

        // Step 2: Confirm installation
        if (!$this->confirm('Should I install countries in database?', true)) {
            return false;
        }

        $this->newLine();

        // Step 3: Create migrations immediately
        $this->line('📝 Creating migration files...');
        if (!$this->generateMigrations()) {
            $this->error('❌ Failed to create migrations');
            return false;
        }
        $this->info('✅ Migration files created');

        // Step 4: Ask and run migrations
        if ($this->confirm('Should I run migrations?', true)) {
            $this->config['run_migrations'] = true;
            $this->line('🚀 Running migrations...');
            if (!$this->runMigrations()) {
                $this->error('❌ Failed to run migrations');
                return false;
            }
            $this->info('✅ Migrations completed - tables created');
            
            // Step 5: Ask and run seeder
            if ($this->confirm('Should I seed the tables with country data?', true)) {
                $this->config['run_seeder'] = true;
                $this->line('📊 Creating seeder file...');
                if (!$this->generateSeeder()) {
                    $this->error('❌ Failed to create seeder');
                    return false;
                }
                
                $this->line('📊 Importing 249 countries...');
                if (!$this->runSeeder()) {
                    $this->error('❌ Failed to import countries');
                    return false;
                }
                $this->info('✅ All 249 countries imported successfully');
                
                // Verify installation by showing flags from database
                $this->displayInstalledCountries();
                
                // Step 6: Update config
                $this->line('⚙️  Updating configuration...');
                if (!$this->updateConfigValues()) {
                    $this->error('❌ Failed to update config');
                    return false;
                }
                $this->info('✅ Configuration updated');
            } else {
                $this->config['run_seeder'] = false;
            }
        } else {
            $this->config['run_migrations'] = false;
            $this->config['run_seeder'] = false;
        }

        return true;
    }


    /**
     * Publish configuration file
     */
    private function publishConfig(): bool
    {
        try {
            $configPath = config_path('countries.php');
            
            if (File::exists($configPath) && !$this->option('force')) {
                if (!$this->confirm('Configuration file already exists. Overwrite?', false)) {
                    return true; // Skip but don't fail
                }
            }

            $this->call('vendor:publish', [
                '--tag' => 'countries-config',
                '--force' => $this->option('force')
            ]);

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to publish config: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate migration files
     */
    private function generateMigrations(): bool
    {
        if (!$this->config['generate_migration']) {
            return true;
        }

        try {
            // Add view namespace for stubs
            $stubsPath = base_path('vendor/webpatser/laravel-countries/stubs');
            if (!is_dir($stubsPath)) {
                // Fallback for development/local installations
                $stubsPath = dirname(__DIR__) . '/stubs';
            }
            View::addNamespace('countries', $stubsPath);

            $migrationPath = database_path('migrations');
            $timestamp = now();

            $migrations = [
                [
                    'file' => $migrationPath . '/' . $timestamp->format('Y_m_d_His') . '_setup_' . $this->config['table_name'] . '_table.php',
                    'stub' => 'countries::migration',
                    'class' => 'Setup' . Str::studly($this->config['table_name']) . 'Table'
                ],
                [
                    'file' => $migrationPath . '/' . $timestamp->addSecond()->format('Y_m_d_His') . '_optimize_' . $this->config['table_name'] . '_table.php',
                    'stub' => 'countries::char_migration',
                    'class' => 'Optimize' . Str::studly($this->config['table_name']) . 'Table'
                ]
            ];

            foreach ($migrations as $migration) {
                if (File::exists($migration['file']) && !$this->option('force')) {
                    $this->warn("Migration already exists: " . basename($migration['file']));
                    continue;
                }

                $content = $this->generateMigrationContent($migration['stub'], $migration['class']);
                File::put($migration['file'], $content);
            }

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to generate migrations: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate seeder file
     */
    private function generateSeeder(): bool
    {
        if (!$this->config['generate_seeder']) {
            return true;
        }

        try {
            $stubsPath = base_path('vendor/webpatser/laravel-countries/stubs');
            if (!is_dir($stubsPath)) {
                // Fallback for development/local installations
                $stubsPath = dirname(__DIR__) . '/stubs';
            }
            View::addNamespace('countries', $stubsPath);
            
            $seederPath = database_path('seeders');
            $seederFile = $seederPath . '/CountriesSeeder.php';

            if (File::exists($seederFile) && !$this->option('force')) {
                if (!$this->confirm('Seeder already exists. Overwrite?', false)) {
                    return true;
                }
            }

            $content = $this->generateSeederContent();
            File::put($seederFile, $content);

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to generate seeder: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update configuration file with custom values
     */
    private function updateConfigValues(): bool
    {
        try {
            $configPath = config_path('countries.php');
            
            if (!File::exists($configPath)) {
                $this->error('Configuration file not found. Please run the publish step first.');
                return false;
            }

            $configContent = File::get($configPath);
            
            // Update table name
            $configContent = preg_replace(
                "/'table_name'\s*=>\s*'[^']*'/",
                "'table_name' => '{$this->config['table_name']}'",
                $configContent
            );

            // Update cache TTL
            $configContent = preg_replace(
                "/'cache_ttl'\s*=>\s*\d+/",
                "'cache_ttl' => {$this->config['cache_ttl']}",
                $configContent
            );

            // Update data source
            $configContent = preg_replace(
                "/'data_source'\s*=>\s*'[^']*'/",
                "'data_source' => '{$this->config['data_source']}'",
                $configContent
            );

            File::put($configPath, $configContent);
            return true;
        } catch (\Exception $e) {
            $this->error('Failed to update config: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate migration content with custom table name
     */
    private function generateMigrationContent(string $stub, string $className): string
    {
        $content = View::make($stub)->render();
        
        // Replace class name
        $content = preg_replace(
            '/class\s+\w+\s+extends/',
            "class {$className} extends",
            $content
        );

        // Replace table name references
        $content = str_replace(
            "\\Config::get('countries.table_name')",
            "'{$this->config['table_name']}'",
            $content
        );

        return "<?php\n\n" . $content;
    }

    /**
     * Generate seeder content with custom table name
     */
    private function generateSeederContent(): string
    {
        $content = View::make('countries::seeder')->render();
        
        // Replace table name references
        $content = str_replace(
            "\\Config::get('countries.table_name')",
            "'{$this->config['table_name']}'",
            $content
        );

        return "<?php\n\n" . $content;
    }

    /**
     * Display installed countries with flags from database
     */
    private function displayInstalledCountries(): void
    {
        try {
            // Check if table exists and has data
            if (!\Schema::hasTable($this->config['table_name'])) {
                return; // Table doesn't exist, skip silently
            }

            $totalCount = \DB::table($this->config['table_name'])->count();
            if ($totalCount === 0) {
                return; // No data, skip silently  
            }

            $this->newLine();
            $this->line('🌍 All countries installed with flags:');
            $this->newLine();

            // Fetch ALL countries with flags from database
            $countries = \DB::table($this->config['table_name'])
                ->select('iso_3166_2', 'name', 'flag')
                ->orderBy('iso_3166_2')
                ->get();

            // Build flag string - all 249 flags
            $allFlags = '';
            foreach ($countries as $country) {
                $allFlags .= $country->flag ?? '';
            }

            // Display ALL flags in one beautiful line
            if ($allFlags) {
                $this->line("   $allFlags");
                $this->newLine();
            }

            // Show total count
            $this->info("   📊 Total: {$totalCount} countries installed with flags and data");
            
        } catch (\Exception $e) {
            // Silent fail - don't show error if database isn't set up yet
            return;
        }
    }

    /**
     * Run database migrations
     */
    private function runMigrations(): bool
    {
        if (!$this->config['run_migrations']) {
            return true;
        }

        try {
            $this->call('migrate');
            return true;
        } catch (\Exception $e) {
            $this->error('Failed to run migrations: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Run countries seeder
     */
    private function runSeeder(): bool
    {
        if (!$this->config['run_seeder']) {
            return true;
        }

        try {
            $this->call('db:seed', ['--class' => 'CountriesSeeder']);
            return true;
        } catch (\Exception $e) {
            $this->error('Failed to run seeder: ' . $e->getMessage());
            $this->line('You can run it manually later: php artisan db:seed --class=CountriesSeeder');
            return false;
        }
    }

    /**
     * Check if step should be skipped
     */
    private function shouldSkipStep(string $method): bool
    {
        return match($method) {
            'generateMigrations' => !$this->config['generate_migration'],
            'generateSeeder' => !$this->config['generate_seeder'],
            'runMigrations' => !$this->config['run_migrations'],
            'runSeeder' => !$this->config['run_seeder'],
            default => false
        };
    }

    /**
     * Validate table name
     */
    private function isValidTableName(string $tableName): bool
    {
        return preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $tableName) === 1;
    }

    /**
     * Display success message with next steps
     */
    private function displaySuccess(): void
    {
        $this->newLine();
        $this->info('🎉 Laravel Countries database installation complete!');
        $this->newLine();
        
        $this->line('🚀 Usage Examples:');
        $this->line('   $countries = Countries::getList();     // All 249 countries');
        $this->line('   $us = Countries::getOne(\'US\');        // Fast lookup');
        $this->line('   $euroCountries = Countries::getByCurrency(\'EUR\');');
        
        $this->newLine();
        $this->line('📖 Documentation: https://documentation.downsized.nl/laravel-uuid');
        $this->line('🐛 Issues: https://github.com/webpatser/laravel-countries/issues');
        $this->newLine();
        
        $this->info('Happy coding! 🚀');
    }
}