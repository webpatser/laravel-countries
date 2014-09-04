use Illuminate\Database\Migrations\Migration;

class CharifyCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table(\Config::get('laravel-countries::table_name'), function($table)
            {
                DB::statement("ALTER TABLE countries MODIFY country_code CHAR(3) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY iso_3166_2 CHAR(2) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY iso_3166_3 CHAR(3) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY region_code CHAR(3) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY sub_region_code CHAR(3) NOT NULL DEFAULT ''");
            });
        }
	

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::table(\Config::get('laravel-countries::table_name'), function($table)
            {
                DB::statement("ALTER TABLE countries MODIFY country_code VARCHAR(3) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY iso_3166_2 VARCHAR(2) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY iso_3166_3 VARCHAR(3) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY region_code VARCHAR(3) NOT NULL DEFAULT ''");
                DB::statement("ALTER TABLE countries MODIFY sub_region_code VARCHAR(3) NOT NULL DEFAULT ''");
            });
	}

}
