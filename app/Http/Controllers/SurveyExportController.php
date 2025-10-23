<?php

namespace App\Http\Controllers;

use App\Exports\TransactionIdCardExport;
use App\Models\Survey;

class SurveyExportController extends Controller
{
    public function idCards(Survey $survey)
    {
        return (new TransactionIdCardExport($survey))->download();
    }
}
