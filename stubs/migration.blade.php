use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the countries table
		Schema::create(\Config::get('countries.table_name'), function(Blueprint $table)
		{
		    $table->string('iso_3166_2', 2)->primary();
		    $table->string('name', 255);
		    $table->string('capital', 255)->nullable();
		    $table->string('iso_3166_3', 3);
		    $table->string('currency_code', 3)->nullable();
		    $table->string('currency_name', 255)->nullable();
		    $table->string('currency_symbol', 10)->nullable();
		    $table->string('calling_code', 10)->nullable();
		    $table->string('region', 50)->nullable();
		    $table->json('languages')->nullable();
		    $table->string('flag', 10)->nullable();
		    $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('countries.table_name'));
	}

}