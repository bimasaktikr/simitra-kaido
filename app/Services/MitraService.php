<?php
// app/Services/EmployeeSelectionService.php
namespace App\Services;

use App\Models\MitraTeladan;
use App\Models\Team;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Transaction;
use App\Models\Nilai1;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Log;

class MitraService
{
    public function getTopMitra(int $year, int $quarter, int $teamId)
    {
        // Fetch mitra summary using Eloquent
        $summary = $this->getMitraSummary($quarter, $year, $teamId);
        logger('[getMitraSummary Params]', [
            'quarter' => $quarter,
            'year' => $year,
            'teamId' => $teamId,
        ]);
        logger('[getMitraSummary Result]', $summary->toArray());

        // Rank mitra
        $ranked = $this->rankMitra($summary);
        logger('[rankMitra Result]', $ranked->toArray());

        // Final results
        $final = $this->getFinalResult($ranked);
        logger('[getFinalResult Result]', $final->toArray());
        // dd($final);
        return collect($final);  // Return as Collection
    }

    public function getQuarterRange(int $year, int $quarter): array
    {
        return [
            Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->startOfDay(),
            Carbon::create($year, $quarter * 3, 1)->endOfMonth()->endOfDay()
        ];
    }

    protected function getMitraSummary($quarter, $year, $teamId)
    {
        $query = Transaction::query()
            ->join('surveys as s', 'transactions.survey_id', '=', 's.id')
            ->join('nilai1s as n', 'transactions.id', '=', 'n.transaction_id')
            ->where('s.triwulan', $quarter)
            ->where('s.year', $year)
            ->where('s.team_id', $teamId)
            ->select('transactions.mitra_id')
            ->selectRaw('COUNT(DISTINCT s.id) as surveys_count')
            ->selectRaw('AVG(n.rerata) as avg_rating')
            ->selectRaw('AVG(transactions.rate) as avg_rate')
            ->groupBy('transactions.mitra_id');

        logger('Mitra Summary SQL:', [$query->toSql(), $query->getBindings()]);
        $result = $query->get();
        logger('Mitra Summary Result:', $result->toArray());
        return $result;
    }

    protected function rankMitra($summary)
    {
        return $summary->sortByDesc('avg_rating')->sortByDesc('surveys_count')->values();
    }

    protected function getFinalResult($ranked)
    {
        if ($ranked->isEmpty()) {
            return collect();
        }
        $topAvg = $ranked->first()->avg_rating;
        $topByRating = $ranked->where('avg_rating', $topAvg);
        if ($topByRating->count() > 1) {
            $maxSurveys = $topByRating->max('surveys_count');
            return $topByRating->where('surveys_count', $maxSurveys);
        }
        return $topByRating;
    }

    public function getWinnerTeam(int $year, int $quarter)
    {
        return MitraTeladan::where('year', $year)
            ->where('quarter', $quarter)
            ->whereNotNull('avg_rating_2')
            ->orderByDesc('avg_rating_2')
            ->value('team_id');
    }

    public function checkMitraTeladan(int $year, int $quarter): array
    {
        $teamIds = Team::pluck('id');
        $empty = $filled = [];

        foreach ($teamIds as $id) {
            $exists = MitraTeladan::where('year', $year)
                ->where('quarter', $quarter)
                ->where('team_id', $id)
                ->exists();

            $exists ? $filled[] = $id : $empty[] = $id;
        }

        return [
            'mitra_teladan_empty' => $empty,
            'mitra_teladan' => $filled,
        ];
    }

    public function setFinal(int $id): ?MitraTeladan
    {
        $mitra = MitraTeladan::findOrFail($id);
        $avg = app(Nilai2Service::class)->getAverageRating($id);

        $mitra->update([
            'status_phase_2' => true,
            'avg_rating_2' => $avg,
        ]);

        return $mitra;
    }

    public function importMitra(array $data, $survey)
    {
        $rows = array_slice($data[0], 1); // Skip header

        foreach ($rows as $row) {
            $dob = DateTime::createFromFormat('d/m/Y', $row[5])?->format('Y-m-d');

            $user = User::firstOrCreate(
                ['email' => $row[3]],
                ['password' => bcrypt('malangkota3573'), 'status' => 'Aktif']
            );

            $mitra = Mitra::firstOrCreate(
                ['sobat_id' => $row[0]],
                [
                    'name' => $row[1],
                    'email' => $row[3],
                    'user_id' => $user->id,
                    'jenis_kelamin' => $row[2],
                    'pendidikan' => $row[4],
                    'tanggal_lahir' => $dob,
                ]
            );

            $transaction = Transaction::create([
                'survey_id' => $survey->id,
                'mitra_id' => $mitra->sobat_id,
                'payment' => $survey->payment,
                'target' => $row['target'] ?? 1,
            ]);

            Nilai1::create([
                'transaction_id' => $transaction->id,
                'aspek1' => $row[6] ?? 0,
                'aspek2' => $row[7] ?? 0,
                'aspek3' => $row[8] ?? 0,
                'rerata' => round((($row[6] ?? 0) + ($row[7] ?? 0) + ($row[8] ?? 0)) / 3, 2),
            ]);
        }

        return true;
    }
}
