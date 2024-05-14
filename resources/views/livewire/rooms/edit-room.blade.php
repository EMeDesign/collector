<?php

use App\Livewire\Forms\RoomForm;
use App\Models\Construction;
use App\Models\Room;
use Livewire\Attributes\Computed;
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
    public Room $room;

    public RoomForm $form;

    #[Computed()]
    public function constructionOptions(): array
    {
        return Construction::query()
            ->where('user_id', auth()->user()->id)
            ->orderByRaw('CONVERT(name USING GBK) ASC')
            ->get()
            ->map(function (Construction $construction) {
                if ($construction->image !== null) {
                    $construction->image = assetUrl($construction->image);
                }

                if ($construction->image === false) {
                    unset($construction->image);
                }

                return $construction;
            })
            ->toArray();
    }

    public function mount(): void
    {
        $this->form->name = $this->room->name;

        $this->form->description = $this->room->description;

        $this->form->position = $this->room->position;

        $this->form->construction_id = $this->room->construction_id;
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
            ->success(trans('tallstackui.success'), trans('tallstackui.file-deleted'))
            ->send();
    }

    /**
     * Edit the room.
     *
     * @return void
     */
    public function edit(): void
    {
        $this->authorize('update', $this->room);

        if ($this->form->save($this->room->id)) {
            $this->redirect('/rooms', navigate: true);
            $this->toast()
                ->success(trans('tallstackui.success'), trans('room.updated-success'))
                ->send();
        } else {
            $this->toast()
                ->error(trans('tallstackui.success'), trans('room.updated-failed'))
                ->send();
        }
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('room.rooms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('room.room-information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('room.edit-your-room') }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="edit({{ $room->id }})">
                            <x-ts-upload label="{{ __('room.image') }}"
                                         hint="{{ __('tallstackui.need-analyze-image') }}"
                                         tip="{{ __('tallstackui.drag-and-drop-image') }}"
                                         :placeholder="__('tallstackui.choose-file')"
                                         accept="image/*"
                                         delete
                                         wire:model="form.image"
                            />

                            <x-ts-input label="{{ __('room.name') }}" hint="{{ __('room.insert-name') }}"
                                        wire:model="form.name"/>

                            <x-ts-input label="{{ __('room.description') }}" hint="{{ __('room.insert-description') }}"
                                        wire:model="form.description"/>

                            <x-ts-number label="{{ __('room.position') }}" hint="{{ __('room.insert-position') }}"
                                         min="0"
                                         wire:model="form.position"/>

                            <x-ts-select.styled label="{{ __('room.select-construction-bind') }}"
                                                hint="{{ __('room.choose-only-one') }}"
                                                :options="$this->constructionOptions"
                                                select="label:name|value:id"
                                                wire:model.blur="form.construction_id"
                                                searchable
                                                required
                            />

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
