<?php

use App\Livewire\Forms\ConstructionForm;
use App\Models\Construction;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

new
#[Layout('layouts.app')]
class extends Component {
    use Interactions;
    use WithFileUploads;

    public ConstructionForm $form;

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
     * Create new construction.
     *
     * @return void
     */
    public function create(): void
    {
        $this->authorize('create', Construction::class);

        if ($this->form->save()) {
            $this->redirect('/constructions', navigate: true);
            $this->toast()
                ->success('Success', 'Your Construction Has Been Created!')
                ->send();
        } else {
            $this->toast()
                ->error('Error', 'Your Construction Created Failed!')
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
                                {{ __("Create your new construction") }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="create()">
                            <x-ts-upload label="Image"
                                         hint="We need to analyze your image"
                                         tip="Drag and drop your image here"
                                         accept="image/*"
                                         delete
                                         wire:model="form.image"
                            />

                            <x-ts-input label="Name *" hint="Insert the room name" wire:model="form.name"/>

                            <x-ts-input label="Location *" hint="Insert the construction location" wire:model="form.location"/>

                            <x-ts-input label="Description *" hint="Insert the room description"
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
