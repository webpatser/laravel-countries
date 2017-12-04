use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table($table = config('countries.table_name'))->delete();

        DB::table($table)->insert($this->records());
    }

    /**
     * Get collection of records.
     *
     * @return array
     */
    private function records(): array
    {
        return collect(app('countries')->getList())->map(function(array $country, int $id) {
            return [
                'id' => $id,
                'capital' => $country['capital'] ?? null,
                'citizenship' => $country['citizenship'] ?? null,
                'country_code' => $country['country_code'],
                'currency' => $country['currency'] ?? null,
                'currency_code' => $country['currency_code'] ?? null,
                'currency_sub_unit' => $country['currency_sub_unit'] ?? null,
                'currency_symbol' => $country['currency_symbol'] ?? null,
                'currency_decimals' => $country['currency_decimals'] ?? null,
                'full_name' => $country['full_name'] ?? null,
                'iso_3166_2' => $country['iso_3166_2'],
                'iso_3166_3' => $country['iso_3166_3'],
                'name' => $country['name'],
                'region_code' => $country['region_code'],
                'sub_region_code' => $country['sub_region_code'],
                'eea' => (bool)$country['eea'],
                'calling_code' => $country['calling_code'],
                'flag' => $country['flag'] ?? null,
            ];
        })->toArray();
    }
}
