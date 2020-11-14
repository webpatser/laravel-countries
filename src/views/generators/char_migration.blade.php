use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;

class CharifyCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
						if (!Type::hasType('char')) {
								Type::addType('char', StringType::class);
						}
            Schema::table(\Config::get('countries.table_name'), function($table)
            {
								$table->char('country_code', 3)->default('')->change();
								$table->char('iso_3166_2', 3)->default('')->change();
								$table->char('iso_3166_3', 3)->default('')->change();
								$table->char('region_code', 3)->default('')->change();
								$table->char('sub_region_code', 3)->default('')->change();
            });
        }
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::table(\Config::get('countries.table_name'), function($table)
            {
								$table->string('country_code', 3)->default('')->change();
								$table->string('iso_3166_2', 2)->default('')->change();
								$table->string('iso_3166_3', 3)->default('')->change();
								$table->string('region_code', 3)->default('')->change();
								$table->string('sub_region_code', 3)->default('')->change();
            });
	}

}
