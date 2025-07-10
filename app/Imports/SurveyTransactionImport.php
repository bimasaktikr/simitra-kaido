<?php

namespace App\Imports;

use App\Models\Mitra;
use App\Models\Nilai1;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SurveyTransactionImport implements ToCollection, WithHeadingRow
{
    protected int $surveyId;
    protected int $rate;

    public function __construct(int $surveyId, $rate)
    {
        $this->surveyId = $surveyId;
        $this->rate = $rate;
    }

    public function collection(Collection $rows)
    {
        Log::info('Import Headers:', $rows->first()?->keys()->toArray());

        foreach ($rows as $index => $row) {
            try {
                $jenisKelamin = $row['jenis_kelamin'] == 1 ? 'Laki-Laki' : 'Perempuan';

                // Validate required fields
                if (!isset($row['id_sobat'], $row['email'], $row['nama'])) {
                    Log::warning("[Row {$index}] Missing required mitra data.");
                    Log::info("[Row {$index}] Data: " . json_encode($row));
                    continue;
                }

                // Create or find user
                $user = User::firstOrCreate(
                    ['email' => $row['email']],
                    [
                        'name' => $row['nama'],
                        'password' => Hash::make(Str::random(12)),
                        'status' => 'Aktif',
                    ]
                );

                // Create or find mitra
                // $mitra = Mitra::firstOrCreate(
                //     ['sobat_id' => $row['id_sobat']],
                //     [
                //         'name' => $row['nama'],
                //         'jenis_kelamin' => $jenisKelamin,
                //         'email' => $row['email'],
                //         'pendidikan' => $row['pendidikan'] ?? '-',
                //         'tanggal_lahir' => isset($row['tgl_lahir']) ? Carbon::parse($row['tgl_lahir']) : null,
                //         'user_id' => $user->id,
                //     ]
                // );
                $existingMitra = Mitra::where('email', $row['email'])
                    ->orWhere('sobat_id', $row['id_sobat'])
                    ->first();

                if ($existingMitra) {
                    $mitra = $existingMitra;
                } else {
                    $mitra = Mitra::create([
                        'sobat_id' => $row['id_sobat'],
                        'name' => $row['nama'],
                        'jenis_kelamin' => $jenisKelamin,
                        'email' => $row['email'],
                        'pendidikan' => $row['pendidikan'] ?? '-',
                        'tanggal_lahir' => isset($row['tgl_lahir'])
                            ? (Carbon::hasFormat($row['tgl_lahir'], 'd/m/Y')
                                ? Carbon::createFromFormat('d/m/Y', $row['tgl_lahir'])
                                : Carbon::parse($row['tgl_lahir']))
                            : null,
                        'user_id' => $user->id,
                    ]);
                }

                // Create or update transaction
                $transaction = Transaction::updateOrCreate(
                    [
                        'mitra_id' => $mitra->id,
                        'survey_id' => $this->surveyId,
                    ],
                    [
                        'target' => $row['target'] ?? 1,
                        'rate' =>  $this->rate ?? 0,
                    ]
                );

                if (
                    isset($row['kualitas_data']) &&
                    isset($row['ketepatan_waktu']) &&
                    isset($row['pemahaman_pengetahuan_kerja'])
                ) {
                    $rerata = number_format(
                        (
                            $row['kualitas_data'] +
                            $row['ketepatan_waktu'] +
                            $row['pemahaman_pengetahuan_kerja']
                        ) / 3, 2
                    );

                    Nilai1::updateOrCreate(
                        ['transaction_id' => $transaction->id],
                        [
                            'aspek1' => $row['kualitas_data'],
                            'aspek2' => $row['ketepatan_waktu'],
                            'aspek3' => $row['pemahaman_pengetahuan_kerja'],
                            'rerata' => $rerata,
                        ]
                    );
                }

            } catch (\Throwable $e) {
                Log::error("[Row {$index}] Import failed: " . $e->getMessage());
                continue;
            }
        }
    }
}

