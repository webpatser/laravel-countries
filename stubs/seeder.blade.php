namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Countries\Countries;

class CountriesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Empty the countries table
        DB::table(\Config::get('countries.table_name'))->delete();

        // Get all countries from JSON file (force JSON source for seeding)
        $jsonPath = base_path('vendor/webpatser/laravel-countries/src/Models/countries.json');
        if (!file_exists($jsonPath)) {
            // Fallback for development installations  
            $jsonPath = dirname(__DIR__, 3) . '/laravel-countries/src/Models/countries.json';
        }
        
        if (!file_exists($jsonPath)) {
            throw new \RuntimeException('Countries JSON file not found for seeding');
        }
        
        $countries = json_decode(file_get_contents($jsonPath), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in countries file: ' . json_last_error_msg());
        }
        foreach ($countries as $countryCode => $country) {
            DB::table(\Config::get('countries.table_name'))->insert([
                'iso_3166_2' => $countryCode,
                'name' => $country['name'],
                'capital' => $country['capital'] ?? null,
                'iso_3166_3' => $country['iso_3166_3'],
                'currency_code' => $country['currency_code'] ?? null,
                'currency_name' => $country['currency_name'] ?? null,
                'currency_symbol' => $country['currency_symbol'] ?? null,
                'calling_code' => $country['calling_code'] ?? null,
                'region' => $country['region'] ?? null,
                'languages' => json_encode($country['languages'] ?? []),
                'flag' => $country['flag'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}