<?php

namespace App\Livewire;

use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class NewUnitButton extends Component
{
    use Interactions;
    #[On('confirmed-unit')]
    public function createUnit(string $term): void
    {
        if (Unit::query()->where('name', $term)->doesntExist()) {
            $unit = Unit::create(['name' => $term]);
            $this->toast()
                ->success('Success', 'Your Unit Has Been Created!')
                ->send();

            $this->dispatch('new-unit-created');
        } else {
            $this->toast()
                ->error('Error', 'This Unit Has Already Existed!')
                ->send();
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div class="px-2 mb-2 flex justify-center items-center">
            <x-ts-button x-on:click.prevent="show = false; $dispatch('confirmed-unit', { term: search })" >
                <span x-html="`Create unit <b>${search}</b>`"></span>
            </x-ts-button>
        </div>
        HTML;
    }
}
