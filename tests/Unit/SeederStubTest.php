<?php

/**
 * Guards issue #151: the seeder stub must insert every column the migration
 * stub declares, so a generated CountriesSeeder never leaves a column NULL.
 */
function countryMigrationColumns(): array
{
    $migrationStub = file_get_contents(__DIR__.'/../../stubs/migration.blade.php');

    $types = implode('|', [
        'string',
        'char',
        'text',
        'mediumText',
        'longText',
        'json',
        'jsonb',
        'boolean',
        'integer',
        'unsignedInteger',
        'bigInteger',
        'unsignedBigInteger',
        'smallInteger',
        'tinyInteger',
        'decimal',
        'float',
        'double',
        'date',
        'dateTime',
        'timestamp',
        'uuid',
        'ulid',
    ]);

    preg_match_all('/\$table->(?:'.$types.')\(\s*\'([^\']+)\'/', $migrationStub, $matches);

    return array_values(array_diff(
        array_unique($matches[1]),
        ['id', 'created_at', 'updated_at', 'timestamps']
    ));
}

it('extracts the columns declared by the migration stub', function () {
    $columns = countryMigrationColumns();

    expect($columns)->toBeArray()
        ->and(count($columns))->toBeGreaterThan(0)
        ->and($columns)->toContain('iso_3166_2', 'name', 'languages');
});

it('inserts every migration column in the seeder stub', function () {
    $seederStub = file_get_contents(__DIR__.'/../../stubs/seeder.blade.php');
    $columns = countryMigrationColumns();

    expect(count($columns))->toBeGreaterThan(0);

    $missing = array_values(array_filter(
        $columns,
        fn (string $column) => preg_match('/\''.preg_quote($column, '/').'\'\s*=>/', $seederStub) !== 1
    ));

    expect($missing)->toBe([]);
});

it('inserts the full_name column in the seeder stub', function () {
    $seederStub = file_get_contents(__DIR__.'/../../stubs/seeder.blade.php');

    expect($seederStub)->toMatch('/\'full_name\'\s*=>/');
});
