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
    public function categoryOptions(): array
    {
        return Category::query()
            ->orderByRaw('CONVERT(name USING GBK) ASC')
            ->get()
            ->toArray();
    }

    #[Computed()]
    #[On('new-keywords-added')]
    public function keywordOptions(): array
    {
        return auth()->user()->keywords()
            ->distinct()
            ->get(['keyword_id', 'name'])
            ->toArray();
    }

    #[Computed()]
    #[On('new-unit-created')]
    public function unitOptions(): array
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
                ->success(trans('tallstackui.success'), trans('item.updated-success'))
                ->send();
        } else {
            $this->toast()
                ->error(trans('tallstackui.error'), trans('item.updated-failed'))
                ->send();
        }
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('item.items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('item.item-information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("item.edit-your-item") }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="edit()">
                            <x-ts-upload label="{{ __('item.image') }}"
                                         hint="{{ __('tallstackui.need-analyze-image') }}"
                                         tip="{{ __('tallstackui.drag-and-drop-image') }}"
                                         :placeholder="__('tallstackui.choose-file')"
                                         accept="image/*"
                                         delete
                                         wire:model="form.image"
                            />

                            <x-ts-input label="{{ __('item.name') }}"
                                        hint="{{ __('item.insert-name') }}"
                                        wire:model="form.name"
                                        invalidate
                                        required
                            />

                            <x-ts-input label="{{ __('item.description') }}"
                                        hint="{{ __('item.insert-description') }}"
                                        wire:model="form.description"
                                        invalidate
                                        required
                            />

                            <x-ts-number label="{{ __('item.quantity') }}"
                                         hint="{{ __('item.insert-quantity') }}"
                                         min="0"
                                         wire:model="form.quantity"
                                         invalidate
                                         required
                            />

                            <x-ts-select.styled label="{{ __('item.select-unit-bind') }}"
                                                hint="{{ __('item.choose-only-one') }}"
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

                            <x-ts-select.styled label="{{ __('item.select-furniture-bind') }}"
                                                hint="{{ __('item.choose-only-one') }}"
                                                :options="$this->furnitureOptions"
                                                select="label:name|value:id"
                                                wire:model="form.furniture_id"
                                                invalidate
                                                required
                                                searchable
                            />

                            <x-ts-select.styled label="{{ __('item.select-category-bind') }}"
                                                hint="{{ __('item.choose-only-one') }}"
                                                :options="$this->categoryOptions"
                                                select="label:name|value:id"
                                                wire:model="form.category_id"
                                                invalidate
                                                required
                                                searchable
                            />

                            <x-ts-select.styled label="{{ __('item.select-keyword-bind') }}"
                                                hint="{{ __('item.choose-more-than-one') }}"
                                                :options="$this->keywordOptions"
                                                select="label:name|value:keyword_id"
                                                wire:model.live="form.keywords"
                                                invalidate
                                                searchable
                                                multiple
                            >
                                <x-slot:after>
                                    <livewire:new-keyword-button />
                                </x-slot:after>
                            </x-ts-select.styled>

                            <x-ts-date label="{{ __('item.obtained_date') }}"
                                       hint="{{ __('item.select-obtained-date') }}"
                                       wire:model="form.obtained_at"
                                       helpers
                            />

                            <x-ts-date label="{{ __('item.expired_date') }}"
                                       hint="{{ __('item.select-expired-date') }}"
                                       wire:model="form.expired_at"
                                       helpers
                            />

                            <x-ts-toggle label="{{ __('item.private') }}" wire:model="form.is_private"/>

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
