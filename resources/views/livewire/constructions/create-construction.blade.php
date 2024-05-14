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
            ->success(trans('tallstackui.success'), trans('tallstackui.file-deleted'))
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
                ->success(trans('tallstackui.success'), trans('construction.created-success'))
                ->send();
        } else {
            $this->toast()
                ->error(trans('tallstackui.error'), trans('construction.created-failed'))
                ->send();
        }
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('construction.constructions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('construction.construction-information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("construction.create-your-construction") }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="create()">
                            <x-ts-upload label="{{ __('construction.image') }}"
                                         hint="{{ __('tallstackui.need-analyze-image') }}"
                                         tip="{{ __('tallstackui.drag-and-drop-image') }}"
                                         :placeholder="__('tallstackui.choose-file')"
                                         accept="image/*"
                                         delete
                                         wire:model="form.image"
                            />

                            <x-ts-input label="{{ __('construction.name') }}" hint="{{ __('construction.insert-name') }}" wire:model="form.name"/>

                            <x-ts-input label="{{ __('construction.location') }}" hint="{{ __('construction.insert-location') }}" wire:model="form.location"/>

                            <x-ts-input label="{{ __('construction.description') }}" hint="{{ __('construction.insert-description') }}"
                                        wire:model="form.description"/>

                            <x-ts-number label="{{ __('construction.position') }}" hint="{{ __('construction.insert-position') }}" min="0"
                                         wire:model="form.position"/>

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ __('tallstackui.save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
