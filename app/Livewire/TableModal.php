<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product; // Assuming you have a Product model
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;



class ProductModal extends Component implements hasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public $name;
    public $description;

    public function render()
    {
        return view('livewire.product-modal');
    }

    public function finalizeMitra(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->action(fn () => $this->post->delete());
    }

}
