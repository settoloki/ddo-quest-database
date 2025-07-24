<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function users_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('users'));

        $columns = [
            'id',
            'name',
            'email',
            'email_verified_at',
            'password',
            'google_id',
            'remember_token',
            'created_at',
            'updated_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('users', $column),
                "Column {$column} does not exist in users table"
            );
        }
    }

    #[Test]
    public function users_table_has_correct_indexes()
    {
        $indexes = Schema::getIndexes('users');
        $indexNames = array_column($indexes, 'name');

        // Check for unique email index
        $this->assertContains('users_email_unique', $indexNames);
    }

    #[Test]
    public function cache_table_exists()
    {
        $this->assertTrue(Schema::hasTable('cache'));

        $columns = ['key', 'value', 'expiration'];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('cache', $column),
                "Column {$column} does not exist in cache table"
            );
        }
    }

    #[Test]
    public function cache_locks_table_exists()
    {
        $this->assertTrue(Schema::hasTable('cache_locks'));

        $columns = ['key', 'owner', 'expiration'];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('cache_locks', $column),
                "Column {$column} does not exist in cache_locks table"
            );
        }
    }

    #[Test]
    public function jobs_table_exists()
    {
        $this->assertTrue(Schema::hasTable('jobs'));

        $columns = [
            'id',
            'queue',
            'payload',
            'attempts',
            'reserved_at',
            'available_at',
            'created_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('jobs', $column),
                "Column {$column} does not exist in jobs table"
            );
        }
    }

    #[Test]
    public function job_batches_table_exists()
    {
        $this->assertTrue(Schema::hasTable('job_batches'));

        $columns = [
            'id',
            'name',
            'total_jobs',
            'pending_jobs',
            'failed_jobs',
            'failed_job_ids',
            'options',
            'cancelled_at',
            'created_at',
            'finished_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('job_batches', $column),
                "Column {$column} does not exist in job_batches table"
            );
        }
    }

    #[Test]
    public function failed_jobs_table_exists()
    {
        $this->assertTrue(Schema::hasTable('failed_jobs'));

        $columns = [
            'id',
            'uuid',
            'connection',
            'queue',
            'payload',
            'exception',
            'failed_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('failed_jobs', $column),
                "Column {$column} does not exist in failed_jobs table"
            );
        }
    }

    #[Test]
    public function google_id_column_was_added_to_users()
    {
        $this->assertTrue(Schema::hasColumn('users', 'google_id'));

        // Check that google_id can be null
        $columnType = Schema::getColumnType('users', 'google_id');
        $this->assertNotEmpty($columnType);
    }

    #[Test]
    public function users_table_email_column_is_unique()
    {
        $indexes = Schema::getIndexes('users');
        
        $emailIndex = collect($indexes)->firstWhere('name', 'users_email_unique');
        $this->assertNotNull($emailIndex, 'Email unique index not found');
        $this->assertTrue($emailIndex['unique'], 'Email index is not unique');
        $this->assertEquals(['email'], $emailIndex['columns'], 'Email index columns incorrect');
    }

    #[Test]
    public function database_seeder_runs_without_errors()
    {
        $this->artisan('db:seed')
            ->assertExitCode(0);
    }

    #[Test]
    public function migrations_can_be_rolled_back()
    {
        // First, ensure we have migrations to rollback
        $this->artisan('migrate:status')
            ->assertExitCode(0);

        // Rollback all migrations
        $this->artisan('migrate:rollback', ['--step' => 999])
            ->assertExitCode(0);

        // Re-run migrations
        $this->artisan('migrate')
            ->assertExitCode(0);

        // Verify tables exist after re-migration
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('cache'));
        $this->assertTrue(Schema::hasTable('jobs'));
    }
}
