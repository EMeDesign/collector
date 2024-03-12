<?php

use App\Livewire\Forms\ConstructionForm;
use App\Models\Construction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

new
#[Layout('layouts.app')]
class extends Component {
    use Interactions;
    use WithFileUploads;

    #[Locked]
    public Construction $construction;

    public ConstructionForm $form;

    public function mount(Construction $construction): void
    {
        $this->form->name = $this->construction->name;

        $this->form->location = $this->construction->location;

        $this->form->description = $this->construction->description;

        $this->form->position = $this->construction->position;
    }

    /**
     * Delete upload file.
     *
     * @param array $content
     *
     * @return void
     */
    public function deleteUpload(array $content): void
    {
        $this->form->deleteUpload($content);

        $this->toast()
            ->success('Success', 'File Deleted!')
            ->send();
    }

    /**
     * Edit the construction.
     *
     * @return void
     */
    public function edit(): void
    {
        $this->authorize('update', $this->construction);

        if ($this->form->save($this->construction->id)) {
            $this->redirect('/constructions', navigate: true);
            $this->toast()
                ->success('Success', 'Your Construction Has Been Updated!')
                ->send();
        } else {
            $this->toast()
                ->error('Error', 'Your Construction Updated Failed!')
                ->send();
        }
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Constructions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Construction Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Edit your construction's information.") }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="edit({{ $this->construction->id }})">
                            <x-ts-upload label="Image"
                                         hint="We need to analyze your image"
                                         tip="Drag and drop your image here"
                                         accept="image/*"
                                         delete
                                         wire:model="form.image"
                            />

                            <x-ts-input label="Name *" hint="Insert the construction name" wire:model="form.name"/>

                            <x-ts-input label="Location *" hint="Insert the construction location" wire:model="form.location"/>

                            <x-ts-input label="Description *" hint="Insert the construction description"
                                        wire:model="form.description"/>

                            <x-ts-number label="Position *" hint="Insert the room position" min="0"
                                         wire:model="form.position"/>

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ __('Save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
