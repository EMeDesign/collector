<?php

namespace App\Livewire;

use App\Models\Keyword;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class NewKeywordButton extends Component
{
    use Interactions;
    #[On('confirmed-keywords')]
    public function createKeyword(string $term): void
    {
        $keyword = Keyword::query()->where('name', $term)->get();

        if ($keyword->isEmpty()) {
            $keyword = Keyword::create(['name' => $term]);
        }

        auth()->user()->keywords()->attach($keyword);

        $this->toast()
            ->success('Success', 'Your Keyword Has Been Created!')
            ->send();

        $this->dispatch('new-keywords-added');
    }

    public function render()
    {
        return <<<'HTML'
        <div class="px-2 mb-2 flex justify-center items-center">
            <x-ts-button x-on:click.prevent="show = false; $dispatch('confirmed-keywords', { term: search })">
                <span x-html="`Create keyword <b>${search}</b>`"></span>
            </x-ts-button>
        </div>
        HTML;
    }
}
