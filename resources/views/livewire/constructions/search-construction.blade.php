<?php

use App\Models\Construction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

new
#[Layout('layouts.app')]
class extends Component {
    use Interactions;
    use WithFileUploads;
    use WithPagination;

    /**
     * Quantity per page.
     *
     * @var int|null
     */
    public ?int $quantity = 10;

    /**
     * Search input.
     *
     * @var string|null
     */
    public ?string $search = null;

    /**
     * Sort column and sort direction.
     *
     * @var array
     */
    public array $sort = [
        'column' => 'position',
        'direction' => 'asc',
    ];

    /**
     * Display user's rooms.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function constructions(): LengthAwarePaginator
    {
        return Construction::query()
            ->Creator()
            ->when($this->search, function (Builder $query) {
                return $query->where('name', 'like', "%{$this->search}%")->orWhere('description', 'like', "%{$this->search}%");
            })
            ->when(
                value: in_array($this->sort['column'], ['name', 'description']),
                callback: function (Builder $query) {
                    return $query->orderByRaw('CONVERT(name USING GBK) ' . strtoupper($this->sort['direction']));
                },
                default: function (Builder $query) {
                    return $query->orderBy(...array_values($this->sort));
                })
            ->paginate(perPage: $this->quantity)
            ->withQueryString();
    }

    /**
     * Delete the construction.
     *
     * @param int $constructionId
     * @param bool $withRooms
     *
     * @return void
     */
    public function delete(int $constructionId, bool $withRooms = false): void
    {
        $construction = Construction::findOrFail($constructionId);

        $this->authorize('delete', $construction);

        if ($withRooms) {
            foreach ($construction->rooms as $room) {
                $room->furniture()->delete();
                $room->delete();
            }
        } else {
            foreach ($construction->rooms as $room) {
                $room->construction()->dissociate();
                $room->save();
            }
        }

        $construction->delete();

        $this->toast()
            ->success(
                'Success',
                $withRooms
                    ? 'Your Construction Has Been Deleted With Its Rooms!'
                    : 'Your Construction Has Been Deleted!'
            )
            ->send();
    }

    /**
     * Data used in table.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            'headers' => [
                [
                    'index' => 'id',
                    'label' => '#',
                ],
                [
                    'index' => 'position',
                    'label' => 'Position',
                ],
                [
                    'index' => 'image',
                    'label' => 'Image',
                    'sortable' => false
                ],
                [
                    'index' => 'name',
                    'label' => 'name',
                ],
                [
                    'index' => 'location',
                    'label' => 'Location',
                ],
                [
                    'index' => 'description',
                    'label' => 'Description',
                ],
                [
                    'index' => 'actions',
                    'label' => 'Actions',
                    'sortable' => false
                ],
            ],

            'rows' => $this->constructions,
        ];
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
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Constructions Information') }}
                            </h2>
                            <x-ts-button class="mt-5 mb-5"
                                         type="button"
                                         href="{{ route('constructions.create-construction') }}"
                                         round
                                         wire:navigate.hover>
                                {{ __('New Construction') }}
                            </x-ts-button>
                        </header>

                        <x-ts-table :$headers :$rows :$sort striped filter paginate id="constructions">
                            @interact('column_image', $row)
                            @if($row->image)
                                <img class="inline max-w-48 max-h-48" src="{{ assetUrl($row->image)}}" alt="Image"/>
                            @else
                                {{ __('No Available Image') }}
                            @endif
                            @endinteract
                            @interact('column_actions', $row)
                            <div class="flex justify-between" wire:key="$row->id">
                                <x-ts-button round
                                             color="green"
                                             icon="pencil"
                                             position="left"
                                             href="{{ route('constructions.edit-construction', ['construction' => $row]) }}"
                                             wire:navigate.hover
                                >
                                    {{ __('Edit') }}
                                </x-ts-button>

                                <x-ts-dropdown position="bottom">
                                    <x-slot:action>
                                        <x-ts-button x-on:click="show = !show"
                                                     round
                                                     color="red"
                                                     icon="trash"
                                                     position="left"
                                        >
                                            {{ __('Trash') }}
                                        </x-ts-button>
                                    </x-slot:action>
                                    <x-ts-dropdown.items icon="trash"
                                                         text="{{ __('Delete Without Rooms') }}"
                                                         wire:click="delete({{ $row->id }})"
                                                         wire:confirm="Are you sure you want to delete this construction?"
                                    />
                                    <x-ts-dropdown.items icon="trash"
                                                         text="{{ __('Delete With Rooms') }}"
                                                         wire:click="delete({{ $row->id }}, true)"
                                                         wire:confirm="Are you sure you want to delete this construction with its rooms?"
                                                         separator
                                    />
                                </x-ts-dropdown>
                            </div>
                            @endinteract
                        </x-ts-table>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
