use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('countries.table_name'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('capital', 191)->nullable();
            $table->string('citizenship', 191)->nullable();
            $table->string('country_code', 3)->default('');
            $table->string('currency', 191)->nullable();
            $table->string('currency_code', 191)->nullable();
            $table->string('currency_sub_unit', 191)->nullable();
            $table->string('currency_symbol', 3)->nullable();
            $table->integer('currency_decimals')->nullable();
            $table->string('full_name', 191)->nullable();
            $table->string('iso_3166_2', 2);
            $table->string('iso_3166_3', 3);
            $table->string('name', 191);
            $table->string('region_code', 3);
            $table->string('sub_region_code', 3);
            $table->boolean('eea')->default(0);
            $table->string('calling_code', 3)->nullable();
            $table->string('flag', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop(config('countries.table_name'));
    }
}