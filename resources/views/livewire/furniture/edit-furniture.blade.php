<?php

use App\Livewire\Forms\FurnitureForm;
use App\Models\Furniture;
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
    public Furniture $furniture;

    public FurnitureForm $form;

    #[Computed()]
    public function roomOptions(): array
    {
        return Room::query()
            ->where('user_id', auth()->user()->id)
            ->orderByRaw('CONVERT(name USING GBK) ASC')
            ->get()
            ->map(function (Room $room) {
                if ($room->image !== null) {
                    $room->image = assetUrl($room->image);
                }

                if ($room->image === false) {
                    unset($room->image);
                }

                return $room;
            })
            ->toArray();
    }

    /**
     * Set the furniture's data.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->form->setFurniture($this->furniture);
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
     * Edit the furniture.
     *
     * @return void
     */
    public function edit(): void
    {
        $this->authorize('update', $this->furniture);

        if ($this->form->save($this->furniture->id)) {
            $this->redirect('/furniture', navigate: true);
            $this->toast()
                ->success(trans('tallstackui.success'), trans('furniture.updated-success'))
                ->send();
        } else {
            $this->toast()
                ->error(trans('tallstackui.error'), trans('furniture.updated-failed'))
                ->send();
        }
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('furniture.furniture') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('furniture.furniture-information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("furniture.edit-your-furniture") }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="edit()">
                            <x-ts-upload label="{{ __('furniture.image') }}"
                                         hint="{{ __('tallstackui.need-analyze-image') }}"
                                         tip="{{ __('tallstackui.drag-and-drop-image') }}"
                                         :placeholder="__('tallstackui.choose-file')"
                                         accept="image/*"
                                         delete
                                         wire:model="form.image"
                            />

                            <x-ts-input label="{{ __('furniture.name') }}" hint="{{ __('furniture.insert-name') }}" wire:model.blur="form.name"/>

                            <x-ts-input label="{{ __('furniture.description') }}"
                                        hint="{{ __('furniture.insert-description') }}"
                                        wire:model.blur="form.description"
                            />

                            <x-ts-number label="{{ __('furniture.position') }}"
                                         hint="{{ __('furniture.insert-position') }}"
                                         min="0"
                                         wire:model.blur="form.position"
                            />

                            <x-ts-select.styled label="{{ __('furniture.select-room-bind') }}"
                                                hint="{{ __('furniture.choose-only-one') }}"
                                                :options="$this->roomOptions"
                                                select="label:name|value:id"
                                                wire:model.blur="form.room_id"
                                                searchable
                                                required
                            />

                            <x-ts-toggle label="{{ __('furniture.private') }}" wire:model="form.is_private"/>

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
