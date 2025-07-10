<?php
// app/Services/EmployeeSelectionService.php
namespace App\Services;

use App\Models\Mitra;
use App\Models\MitraTeladan;
use App\Models\Nilai2;
use App\Models\Team;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Nilai2Service
{
    /**
     * Save a new Nilai2 record.
     *
     * @param array $data
     * @return bool
     */
    public function saveNilai2(array $data)
    {
        try {
            DB::beginTransaction();

            Nilai2::create($data);

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error saving Nilai2: ' . $th->getMessage(), ['exception' => $th]);
            return false;
        }
    }

    public function getAverageRating($id)
    {
        return Nilai2::where('mitra_teladan_id', $id)
                        ->avg('rerata');
    }

    public function getEmployeeDone($id)
    {
        return Nilai2::where('mitra_teladan_id', $id)
                        ->pluck('employee_id');
    }

    public function getStatus($id)
    {
        // Retrieve is_final status mapped by team_penilai_id
        return Nilai2::where('employee_id', $id)
            ->pluck('is_final', 'employee_id')
            ->map(function($isFinal) {
                return (int) $isFinal; // Ensure the value is returned as an integer (1 or 0)
            })
            ->toArray();
    }

    public function checkFinal($id)
    {
        // Find the MitraTeladan by its ID
        $mitraTeladan = MitraTeladan::findOrFail($id);

        // Check if all related Nilai2 entries are final
        $allFinal = $mitraTeladan->nilai2->every(function ($nilai2) {
            return $nilai2->is_final;
        });

        // Return true if all are final, otherwise false
        return $allFinal;
    }

    public function userHasNilai2($mitraTeladanId, $userId)
    {
        return Nilai2::where('mitra_teladan_id', $mitraTeladanId)
            ->where('user_id', $userId)
            ->exists();
    }

}
