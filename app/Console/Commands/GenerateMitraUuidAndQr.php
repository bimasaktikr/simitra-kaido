<?php

namespace App\Console\Commands;

use App\Models\Mitra;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateMitraUuidAndQr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mitra:generate-uuid-qr {--force : Force regenerate for all mitras}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate UUID dan QR code untuk semua mitra yang belum memilikinya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');

        if ($force) {
            $mitras = Mitra::all();
            $this->info('🔄 Mode FORCE: Akan regenerate UUID & QR untuk SEMUA mitra...');
        } else {
            $mitras = Mitra::whereNull('uuid')
                ->orWhereNull('qr_path')
                ->get();
            $this->info('🔄 Generating UUID & QR code untuk mitra yang belum memilikinya...');
        }

        if ($mitras->isEmpty()) {
            $this->info('✅ Semua mitra sudah memiliki UUID dan QR code!');
            return Command::SUCCESS;
        }

        $this->info("📊 Found {$mitras->count()} mitra(s) to process...");

        $progressBar = $this->output->createProgressBar($mitras->count());
        $progressBar->start();

        $generated = 0;
        $errors = 0;

        foreach ($mitras as $mitra) {
            try {
                // Generate UUID jika belum ada atau force
                if (empty($mitra->uuid) || $force) {
                    $mitra->uuid = (string) Str::uuid();
                }

                // Save akan trigger observer untuk generate QR
                $mitra->save();

                $generated++;
                $progressBar->advance();

            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("❌ Error processing Mitra ID {$mitra->id}: " . $e->getMessage());
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✅ Selesai!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Berhasil', $generated],
                ['Error', $errors],
                ['Total', $mitras->count()],
            ]
        );

        return Command::SUCCESS;
    }
}
