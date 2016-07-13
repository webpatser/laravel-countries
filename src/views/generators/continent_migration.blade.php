use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddContinentCountriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the users table
        Schema::table(\Config::get('countries.table_name'), function(Blueprint $table)
        {
            $table->string('continent', 16)->default('')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(\Config::get('countries.table_name'), function(Blueprint $table)
        {
            $table->dropColumn('continent');
        });
    }

}
