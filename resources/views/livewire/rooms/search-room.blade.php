<?php

use App\Models\Construction;
use App\Models\Room;
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
     * Search input.
     *
     * @var array|null
     */
    public ?array $constructionId = [];

    #[Computed()]
    public function constructions(): array
    {
        return Construction::query()
            ->Creator()
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

    /**
     * Display user's rooms.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function rooms(): LengthAwarePaginator
    {
        return Room::query()
            ->with('construction')
            ->where('user_id', auth()->user()->id)
            ->when($this->search, function (Builder $query) {
                return $query->where('name', 'like', "%{$this->search}%")->orWhere('description', 'like', "%{$this->search}%");
            })
            ->when($this->constructionId, function (Builder $query) {
                return $query->whereIn('construction_id', $this->constructionId);
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
     * Delete the room.
     *
     * @param int $roomId
     * @param bool $withFurniture
     *
     * @return void
     */
    public function delete(int $roomId, bool $withFurniture = false): void
    {
        $room = Room::findOrFail($roomId);

        $this->authorize('delete', $room);

        if ($withFurniture) {
            $room->furniture()->delete();
        } else {
            foreach ($room->furniture as $furniture) {
                $furniture->room()->dissociate();
                $furniture->save();
            }
        }

        $room->delete();

        $this->toast()
            ->success(
                'Success',
                $withFurniture
                    ? 'Your Room Has Been Deleted With Its Furniture!'
                    : 'Your Room Has Been Deleted!'
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
                    'index' => 'description',
                    'label' => 'Description',
                ],
                [
                    'index' => 'construction.name',
                    'label' => 'Construction',
                    'sortable' => false
                ],
                [
                    'index' => 'actions',
                    'label' => 'Actions',
                    'sortable' => false
                ],
            ],

            'rows' => $this->rooms,
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rooms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Rooms Information') }}
                            </h2>
                            <x-ts-button class="mt-5 mb-5"
                                         type="button"
                                         href="{{ route('rooms.create-room') }}"
                                         round
                                         wire:navigate.hover>
                                {{ __('New Room') }}
                            </x-ts-button>
                        </header>

                        <div class="w-1/4 sm:w-1/5">
                            <x-ts-select.styled :options="$this->constructions"
                                                :label="__('Construction')"
                                                select="label:name|value:id"
                                                placeholder="Filter with construction"
                                                wire:model.live="constructionId"
                                                searchable
                                                multiple
                            />
                        </div>

                        <x-ts-table :$headers :$rows :$sort striped filter paginate id="rooms">
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
                                             href="{{ route('rooms.edit-room', ['room' => $row]) }}"
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
                                                         text="{{ __('Delete Without Furniture') }}"
                                                         wire:click="delete({{ $row->id }})"
                                                         wire:confirm="Are you sure you want to delete this room?"
                                    />
                                    <x-ts-dropdown.items icon="trash"
                                                         text="{{ __('Delete With Furniture') }}"
                                                         wire:click="delete({{ $row->id }}, true)"
                                                         wire:confirm="Are you sure you want to delete this room with its furniture?"
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
