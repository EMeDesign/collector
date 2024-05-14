<?php

use App\Models\Furniture;
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
    public ?array $roomId = [];

    #[Computed()]
    public function rooms(): array
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
     * Display user's rooms.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function furniture(): LengthAwarePaginator
    {
        return Furniture::query()
            ->with('room')
            ->where('user_id', auth()->user()->id)
            ->when($this->search, function (Builder $query) {
                return $query
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->when($this->roomId, function (Builder $query) {
                return $query->whereIn('room_id', $this->roomId);
            })
            ->when(
                value: in_array($this->sort['column'], ['name', 'description']),
                callback: function (Builder $query) {
                    return $query->orderByRaw(\Illuminate\Support\Str::of('CONVERT(name USING GBK) ')->append(Str::upper($this->sort['direction'])));
                },
                default: function (Builder $query) {
                    return $query->orderBy(...array_values($this->sort));
                })
            ->paginate(perPage: $this->quantity)
            ->withQueryString();
    }

    /**
     * Delete the furniture.
     *
     * @param int $furnitureId
     *
     * @return void
     */
    public function delete(int $furnitureId): void
    {
        $furniture = Furniture::findOrFail($furnitureId);

        $this->authorize('delete', $furniture);

        $furniture->delete();

        $this->toast()
            ->success(trans('tallstackui.success'), trans('furniture.deleted-success'))
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
                    'label' => trans('room.position-table'),
                ],
                [
                    'index' => 'image',
                    'label' => trans('furniture.image-table'),
                    'sortable' => false
                ],
                [
                    'index' => 'name',
                    'label' => trans('furniture.name-table'),
                ],
                [
                    'index' => 'description',
                    'label' => trans('furniture.description-table'),
                ],
                [
                    'index' => 'room.name',
                    'label' => trans('furniture.room-table'),
                    'sortable' => false
                ],
                [
                    'index' => 'actions',
                    'label' => trans('tallstackui.actions'),
                    'sortable' => false
                ],
            ],

            'rows' => $this->furniture,
        ];
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
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('furniture.furniture-information') }}
                            </h2>
                            <x-ts-button class="mt-5 mb-5"
                                         type="button"
                                         href="{{ route('furniture.create-furniture') }}"
                                         round
                                         wire:navigate.hover>
                                {{ __('furniture.new-furniture') }}
                            </x-ts-button>
                        </header>

                        <div class="w-1/4 sm:w-1/5">
                            <x-ts-select.styled :options="$this->rooms"
                                                :label="__('furniture.room')"
                                                select="label:name|value:id"
                                                placeholder="{{ __('furniture.filter-with-room') }}"
                                                wire:model.live="roomId"
                                                searchable
                                                multiple
                            />
                        </div>

                        <x-ts-table :$headers :$rows :$sort striped filter paginate id="furniture">
                            @interact('column_image', $row)
                            @if($row->image)
                                <img class="inline max-w-48 max-h-48" src="{{ assetUrl($row->image)}}" alt="{{ __('furniture.image') }}"/>
                            @else
                                {{ __('furniture.no-image') }}
                            @endif

                            @endinteract
                            @interact('column_actions', $row)
                            <div class="flex justify-between" wire:key="$row->id">
                                <x-ts-button round
                                             color="green"
                                             icon="pencil"
                                             position="left"
                                             href="{{ route('furniture.edit-furniture', ['furniture' => $row]) }}"
                                             wire:navigate.hover
                                >
                                    {{ __('tallstackui.edit') }}
                                </x-ts-button>

                                <x-ts-button round
                                             color="red"
                                             icon="trash"
                                             position="left"
                                             wire:click="delete({{ $row->id }})"
                                >
                                    {{ __('tallstackui.trash') }}
                                </x-ts-button>
                            </div>
                            @endinteract
                        </x-ts-table>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
