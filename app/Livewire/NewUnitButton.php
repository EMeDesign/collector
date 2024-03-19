<?php

namespace App\Livewire;

use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class NewUnitButton extends Component
{
    use Interactions;
    #[On('confirmed')]
    public function createUnit(string $term): void
    {
        if (Unit::query()->where('name', $term)->doesntExist()) {
            $unit = Unit::create(['name' => $term]);
            $this->toast()
                ->success('Success', 'Your Unit Has Been Created!')
                ->send();
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
            <x-ts-button x-on:click="show = false; $dispatch('confirmed', { term: search })">
                <span x-html="`Create unit <b>${search}</b>`"></span>
            </x-ts-button>
        </div>
        HTML;
    }
}
