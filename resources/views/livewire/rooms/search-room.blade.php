<?php

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
     * Display user's rooms.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function rooms(): LengthAwarePaginator
    {
        return Room::query()
            ->where('user_id', auth()->user()->id)
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
     * Delete the room.
     *
     * @param int $roomId
     *
     * @return void
     */
    public function delete(int $roomId): void
    {
        $room = Room::findOrFail($roomId);

        $this->authorize('delete', $room);

        $room->delete();

        $this->toast()
            ->success('Success', 'Your Room Has Been Deleted!')
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

                                <x-ts-button round
                                             color="red"
                                             icon="trash"
                                             position="left"
                                             wire:click="delete({{ $row->id }})"
                                >
                                    {{ __('Trash') }}
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
