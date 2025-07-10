<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use App\Models\MasterSurvey;

class MigrateSurveyToMasterSurvey extends Command
{
    protected $signature = 'survey:migrate-to-master';
    protected $description = 'Migrasi data name dan code dari surveys ke master_surveys, lalu update master_survey_id di surveys';

    public function handle()
    {
        $surveys = Survey::all();
        $bar = $this->output->createProgressBar($surveys->count());
        $bar->start();

        foreach ($surveys as $survey) {
            $master = MasterSurvey::firstOrCreate([
                'name' => $survey->name,
                'code' => $survey->code,
            ]);
            $survey->master_survey_id = $master->id;
            $survey->save();
            $bar->advance();
        }
        $bar->finish();
        $this->info("\nMigrasi selesai!");
    }
}
