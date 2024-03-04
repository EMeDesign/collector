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
    public ?Room $room = null;

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
        if ($this->room instanceof Room) {
            $this->form->room_id = $this->room->id;
        }
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
     * Create new furniture.
     *
     * @return void
     */
    public function create(): void
    {
        $this->authorize('create', Furniture::class);

        if ($this->form->save()) {
            $this->redirect('/furniture', navigate: true);
            $this->toast()
                ->success('Success', 'Your Furniture Has Been Created!')
                ->send();
        } else {
            $this->toast()
                ->error('Error', 'Your Furniture Created Failed!')
                ->send();
        }
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Furniture') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Furniture Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Create your new furniture") }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="create()">
                            <x-ts-upload label="Image"
                                         hint="We need to analyze your image"
                                         tip="Drag and drop your image here"
                                         accept="image/*"
                                         delete
                                         wire:model.blur="form.image"
                            />

                            <x-ts-input label="Name *" hint="Insert the furniture name" wire:model.blur="form.name"/>

                            <x-ts-input label="Description *"
                                        hint="Insert the furniture description"
                                        wire:model.blur="form.description"
                            />

                            <x-ts-number label="Position *"
                                         hint="Insert the furniture position"
                                         min="0"
                                         wire:model.blur="form.position"
                            />

                            <x-ts-select.styled label="Select One Room To Bind"
                                                hint="You can choose only one"
                                                :options="$this->roomOptions"
                                                select="label:name|value:id"
                                                wire:model.blur="form.room_id"
                                                searchable
                            />

                            <x-ts-toggle label="Is Private" wire:model="form.is_private"/>

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
