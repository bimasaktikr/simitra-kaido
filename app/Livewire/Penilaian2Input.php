<?php

namespace App\Livewire;

use App\Models\Nilai2;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Penilaian2Input extends Component
{
    protected $listeners = [
        'openNilai2Modal',
        'closeNilai2Modal' => 'closeNilai2Modal',
    ];

    public $showModal = false;
    public $mitraTeladanId = null;
    public $aspek1 = 0;
    public $aspek2 = 0;
    public $aspek3 = 0;
    public $aspek4 = 0;
    public $aspek5 = 0;
    public $aspek6 = 0;
    public $aspek7 = 0;
    public $aspek8 = 0;
    public $aspek9 = 0;
    public $aspek10 = 0;
    public $debugPayload = null;
    public $is_final = false;
    public $aspekDescriptions = [
        1 => 'Adaptif',
        2 => 'Akuntabel',
        3 => 'Amanah',
        4 => 'Disiplin',
        5 => 'Harmonis',
        6 => 'Inovatif',
        7 => 'Kolaboratif',
        8 => 'Kompeten',
        9 => 'Loyal',
        10 => 'Pelayanan',
    ];

    public function mount($mitraTeladanId = null)
    {
        $this->mitraTeladanId = $mitraTeladanId;
        $this->aspek1 = 0;
        $this->aspek2 = 0;
        $this->aspek3 = 0;
        $this->aspek4 = 0;
        $this->aspek5 = 0;
        $this->aspek6 = 0;
        $this->aspek7 = 0;
        $this->aspek8 = 0;
        $this->aspek9 = 0;
        $this->aspek10 = 0;
    }

    public function openNilai2Modal($payload)
    {
        $this->debugPayload = $payload; // For Livewire debug
        logger('openNilai2Modal triggered', ['payload' => $payload]);
        // $this->dispatchBrowserEvent('nilai2-modal-debug', ['payload' => $payload]);
        // Accept both array and int for compatibility
        $this->mitraTeladanId = is_array($payload) ? $payload[0] : $payload;
        $this->showModal = true;
        $this->aspek1 = $this->aspek2 = $this->aspek3 = $this->aspek4 = $this->aspek5 = 0;
        $this->aspek6 = $this->aspek7 = $this->aspek8 = $this->aspek9 = $this->aspek10 = 0;
        $this->is_final = false;
    }

    public function saveNilai2()
    {
        $this->validate([
            'aspek1' => 'required|integer|min:1|max:10',
            'aspek2' => 'required|integer|min:1|max:10',
            'aspek3' => 'required|integer|min:1|max:10',
            'aspek4' => 'required|integer|min:1|max:10',
            'aspek5' => 'required|integer|min:1|max:10',
            'aspek6' => 'required|integer|min:1|max:10',
            'aspek7' => 'required|integer|min:1|max:10',
            'aspek8' => 'required|integer|min:1|max:10',
            'aspek9' => 'required|integer|min:1|max:10',
            'aspek10' => 'required|integer|min:1|max:10',
        ]);

        $rerata = (
            $this->aspek1 + $this->aspek2 + $this->aspek3 + $this->aspek4 + $this->aspek5 +
            $this->aspek6 + $this->aspek7 + $this->aspek8 + $this->aspek9 + $this->aspek10
        ) / 10;

        Nilai2::create([
            'mitra_teladan_id' => $this->mitraTeladanId,
            'user_id' => Auth::id(),
            'aspek1' => $this->aspek1,
            'aspek2' => $this->aspek2,
            'aspek3' => $this->aspek3,
            'aspek4' => $this->aspek4,
            'aspek5' => $this->aspek5,
            'aspek6' => $this->aspek6,
            'aspek7' => $this->aspek7,
            'aspek8' => $this->aspek8,
            'aspek9' => $this->aspek9,
            'aspek10' => $this->aspek10,
            'rerata' => $rerata,
            'is_final' => $this->is_final,
        ]);

        \Filament\Notifications\Notification::make()
            ->title('Nilai berhasil disimpan')
            ->success()
            ->body('Nilai 2 telah berhasil disimpan.')
            ->send();

        $this->dispatch('closeNilai2Modal');
        $this->dispatch('nilai2Saved');
    }

    public function closeNilai2Modal()
    {
        $this->showModal = false;
    }

    public function cancel()
    {
        $this->emit('closeNilai2Modal');
    }

    public function render()
    {
        return view('livewire.penilaian2-input');
    }
}
