<?php

namespace App\Filament\Imports;

use App\Models\Employee;
use App\Models\User;
use App\Models\Team;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EmployeeImporter extends Importer
{
    protected static ?string $model = Employee::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:200']),
            ImportColumn::make('nip')
                ->requiredMapping()
                ->rules(['required', 'max:200']),
            ImportColumn::make('jenis_kelamin')
                ->requiredMapping()
                ->rules(['required', 'max:200']),
            ImportColumn::make('user_email')
                ->label('User Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('team_name')
                ->label('Team Name')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('tanggal_lahir')
                ->requiredMapping()
                ->rules(['required', 'date']),
        ];
    }

    public function resolveRecord(): ?Employee
    {
        $user = User::where('email', $this->data['user_email'] ?? null)->first();
        $team = null;
        if (!empty($this->data['team_name'])) {
            $team = Team::where('name', $this->data['team_name'])->first();
        }

        return new Employee([
            'name' => $this->data['name'],
            'nip' => $this->data['nip'],
            'jenis_kelamin' => $this->data['jenis_kelamin'],
            'user_id' => $user ? $user->id : null,
            'team_id' => $team ? $team->id : null,
            'tanggal_lahir' => $this->data['tanggal_lahir'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your employee import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
