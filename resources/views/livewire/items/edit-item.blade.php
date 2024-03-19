<?php

use App\Livewire\Forms\ItemForm;
use App\Models\Category;
use App\Models\Furniture;
use App\Models\Item;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

new
#[Layout('layouts.app')]
class extends Component {
    use Interactions;
    use WithFileUploads;

    #[Locked()]
    public Item $item;

    public ItemForm $form;

    public function mount(): void
    {
        $this->form->setForm($this->item);
    }

    #[Computed()]
    public function unitOptions()
    {
        return Unit::query()
            ->orderByRaw('CONVERT(name USING GBK) ASC')
            ->get()
            ->toArray();
    }

    #[Computed()]
    public function furnitureOptions(): array
    {
        return Furniture::query()
            ->Creator()
            ->orderByRaw('CONVERT(name USING GBK) ASC')
            ->get()
            ->map(function (Furniture $furniture) {
                if ($furniture->image !== null) {
                    $furniture->image = assetUrl($furniture->image);
                }

                if ($furniture->image === false) {
                    unset($furniture->image);
                }

                return $furniture;
            })
            ->toArray();
    }

    #[Computed()]
    public function categoryOptions()
    {
        return Category::query()
            ->orderByRaw('CONVERT(name USING GBK) ASC')
            ->get()
            ->toArray();
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
     * Edit the item.
     *
     * @return void
     */
    public function edit(): void
    {
        $this->authorize('update', $this->item);

        if ($this->form->save($this->item->id)) {
            $this->redirect('/items', navigate: true);
            $this->toast()
                ->success('Success', 'Your Item Has Been Updated!')
                ->send();
        } else {
            $this->toast()
                ->error('Error', 'Your Item Updated Failed!')
                ->send();
        }
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Item Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Edit your item's information.") }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="edit()">
                            <x-ts-upload label="Image"
                                         hint="We need to analyze your image"
                                         tip="Drag and drop your image here"
                                         accept="image/*"
                                         delete
                                         wire:model="form.image"
                            />

                            <x-ts-input label="Name *"
                                        hint="Insert the item name"
                                        wire:model="form.name"
                                        invalidate
                                        required
                            />

                            <x-ts-input label="Description *"
                                        hint="Insert the item description"
                                        wire:model="form.description"
                                        invalidate
                                        required
                            />

                            <x-ts-number label="Quantity *"
                                         hint="Insert the item quantity"
                                         min="0"
                                         wire:model="form.quantity"
                                         invalidate
                                         required
                            />

                            <x-ts-select.styled label="Select One Unit To Bind"
                                                hint="You can choose only one"
                                                :options="$this->unitOptions"
                                                select="label:name|value:id"
                                                wire:model.live="form.unit_id"
                                                invalidate
                                                required
                                                searchable
                            >
                                <x-slot:after>
                                    <livewire:new-unit-button />
                                </x-slot:after>
                            </x-ts-select.styled>

                            <x-ts-select.styled label="Select One Furniture To Bind"
                                                hint="You can choose only one"
                                                :options="$this->furnitureOptions"
                                                select="label:name|value:id"
                                                wire:model="form.furniture_id"
                                                invalidate
                                                required
                                                searchable
                            />

                            <x-ts-select.styled label="Select One Category To Bind"
                                                hint="You can choose only one"
                                                :options="$this->categoryOptions"
                                                select="label:name|value:id"
                                                wire:model="form.category_id"
                                                invalidate
                                                required
                                                searchable
                            />

                            <x-ts-date label="Obtained Date"
                                       hint="Select your Obtained Date"
                                       wire:model="form.obtained_at"
                                       helpers
                            />

                            <x-ts-date label="Expired Date"
                                       hint="Select your Expired Date"
                                       wire:model="form.expired_at"
                                       helpers
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
