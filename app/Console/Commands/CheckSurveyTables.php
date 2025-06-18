<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckSurveyTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:check-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if survey tables are properly created';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking survey tables...');

        // Cek apakah tabel surveys ada
        if (!Schema::hasTable('surveys')) {
            $this->error('Table surveys does not exist!');
            return 1;
        }

        $this->info('Table surveys exists.');

        // Cek struktur tabel surveys
        $surveysColumns = Schema::getColumnListing('surveys');
        $this->info('surveys columns: ' . implode(', ', $surveysColumns));

        // Cek expected columns
        $expectedSurveysColumns = ['id', 'nama', 'jenis_kelamin', 'usia', 'jenjang', 'kelas', 'email', 'created_at', 'updated_at'];
        $missingSurveysColumns = array_diff($expectedSurveysColumns, $surveysColumns);

        if (!empty($missingSurveysColumns)) {
            $this->error('Missing columns in surveys table: ' . implode(', ', $missingSurveysColumns));
        }

        // Cek apakah tabel survey_responses ada
        if (!Schema::hasTable('survey_responses')) {
            $this->error('Table survey_responses does not exist!');
            return 1;
        }

        $this->info('Table survey_responses exists.');

        // Cek struktur tabel survey_responses
        $responsesColumns = Schema::getColumnListing('survey_responses');
        $this->info('survey_responses columns: ' . implode(', ', $responsesColumns));

        // Cek expected columns
        $expectedResponsesColumns = ['id', 'survey_id', 'pertanyaan_id', 'jawaban', 'created_at', 'updated_at'];
        $missingResponsesColumns = array_diff($expectedResponsesColumns, $responsesColumns);

        if (!empty($missingResponsesColumns)) {
            $this->error('Missing columns in survey_responses table: ' . implode(', ', $missingResponsesColumns));
        }

        // Cek foreign key
        try {
            $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'survey_responses'
                AND REFERENCED_TABLE_NAME = 'surveys'");

            if (empty($foreignKeys)) {
                $this->error('Foreign key constraint between survey_responses and surveys is missing!');
            } else {
                $this->info('Foreign key constraint exists.');
            }
        } catch (\Exception $e) {
            $this->error('Error checking foreign keys: ' . $e->getMessage());
        }

        $this->info('Check completed.');
        return 0;
    }
}
