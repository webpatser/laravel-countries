use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OptimizeCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(\Config::get('countries.table_name'), function(Blueprint $table) {
			// Add indexes for common lookup fields
			$table->index(['region'], 'countries_region_index');
			$table->index(['currency_code'], 'countries_currency_code_index');
			$table->index(['calling_code'], 'countries_calling_code_index');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(\Config::get('countries.table_name'), function(Blueprint $table) {
			$table->dropIndex('countries_region_index');
			$table->dropIndex('countries_currency_code_index');
			$table->dropIndex('countries_calling_code_index');
		});
	}

}