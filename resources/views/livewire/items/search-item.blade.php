<?php

use App\Models\Furniture;
use App\Models\Item;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public bool $modal;

    public int $owner = 0;

    public int $categoryId = 0;

    public array $keywords = [];

    public array $ownerOptions = [
        ['id' => 0, 'name' => '所有的'],
        ['id' => 1, 'name' => '仅自己的'],
        ['id' => 2, 'name' => '非自己的']
    ];

    public int $recipient_id = 0;

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
        'column' => 'name',
        'direction' => 'asc',
    ];

    /**
     * Search input.
     *
     * @var array|null
     */
    public ?array $furnitureId = [];

    /**
     * @return array
     */
    #[Computed()]
    public function keywordOptions(): array
    {
        return auth()->user()
            ->keywords()
            ->get()
            ->toArray();
    }

    /**
     * @return array
     */
    #[Computed()]
    public function categoryOptions(): array
    {
        return \App\Models\Category::all()->toArray();
    }

    /**
     * @return array
     */
    #[Computed()]
    public function friendOptions(): array
    {
        return auth()->user()
            ->getFriends()
            ->map(function (User $user) {
                $user->description = $user->email;
                return $user;
            })
            ->toArray();
    }

    #[Computed()]
    public function furniture(): array
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
     * Display user's rooms.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function Items(): LengthAwarePaginator
    {
        return Item::query()
            ->where('user_id', auth()->user()->id)
            ->when(!empty($this->owner), function (Builder $query) {
                return match ($this->owner) {
                    1 => $query->where('owner_id', 0),
                    2 => $query->where('owner_id', '!=', 0)
                };
            })
            ->when(!empty($this->keywords), function (Builder $query) {
                return $query->whereHas('keywords', function (Builder $query) {
                    $query->whereIn('name', $this->keywords);
                });
            })
            ->when(!empty($this->categoryId), function (Builder $query) {
                return $query->where('category_id', $this->categoryId);
            })
            ->when($this->search, function (Builder $query) {
                return $query->where(function (Builder $query) {
                    $query->where('name', 'like', "%{$this->search}%")->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->furnitureId, function (Builder $query) {
                return $query->whereIn('furniture_id', $this->furnitureId);
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
            ->through(function (Item $item) {
                return $item->transQuantityToString();
            })
            ->withQueryString();
    }

    /**
     * Delete the item.
     *
     * @param int $itemId
     *
     * @return void
     */
    public function delete(int $itemId): void
    {
        $item = Item::findOrFail($itemId);

        $this->authorize('delete', $item);

        $item->delete();

        $this->toast()
            ->success(trans('tallstackui.success'), trans('item.deleted-success'))
            ->send();
    }

    /**
     * Share the item.
     *
     * @param \App\Models\Item $item
     *
     * @return void
     */
    public function share(Item $item): void
    {
        try {
            $recipient = User::findOrFail($this->recipient_id);
        } catch (ModelNotFoundException $e) {
            $this->toast()
                ->success(trans('tallstackui.error'), trans('item.shared-failed'))
                ->send();

            return;
        }

        $attributes =  $item->attributesToArray();

        if (
            $recipient->items()
                ->where('owner_id', $attributes['user_id'])
                ->where('name', $attributes['name'])
                ->where('furniture_id', $attributes['furniture_id'])
                ->where('category_id', $attributes['category_id'])
                ->exists()
        ) {
            $this->toast()
                ->success(trans('tallstackui.error'), trans('item.shared-failed-repeat'))
                ->send();

            return;
        }

        $info = \Illuminate\Support\Arr::except($attributes, ['id', 'user_id', 'owner_id', 'created_at', 'updated_at']);

        $newItem = (new Item())->fill($info);
        $newItem->user_id = $this->recipient_id;
        $newItem->owner_id = $item->user_id;
        $newItem->save();

        $recipient->notifyNow(new \App\Notifications\ShareItemNotification($newItem));

        $this->modal = !$this->modal;

        $this->toast()
            ->success(trans('tallstackui.success'), trans('item.shared-success'))
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
                    'index' => 'quantity',
                    'label' => trans('item.quantity-table'),
                ],
                [
                    'index' => 'image',
                    'label' => trans('item.image-table'),
                    'sortable' => false
                ],
                [
                    'index' => 'name',
                    'label' => trans('item.name-table'),
                ],
                [
                    'index' => 'description',
                    'label' => trans('item.description-table'),
                ],
                [
                    'index' => 'furniture.name',
                    'label' => trans('item.furniture-table'),
                    'sortable' => false
                ],
                [
                    'index' => 'owner.name',
                    'label' => trans('item.owner-table'),
                    'sortable' => false
                ],
                [
                    'index' => 'actions',
                    'label' => trans('tallstackui.actions'),
                    'sortable' => false
                ],
            ],

            'rows' => $this->items,
        ];
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
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('item.item-information') }}
                            </h2>
                            <x-ts-button class="mt-5 mb-5"
                                         type="button"
                                         href="{{ route('items.create-item') }}"
                                         round
                                         wire:navigate.hover>
                                {{ __('item.new-item') }}
                            </x-ts-button>
                        </header>

                        <div class="mb-3 mt-3 flex justify-between">
                            <div class="w-1/4 sm:w-1/5">
                                <x-ts-select.styled :options="$this->furniture"
                                                    :label="__('item.furniture')"
                                                    select="label:name|value:id"
                                                    placeholder="{{ __('item.filter-with-furniture') }}"
                                                    wire:model.live="furnitureId"
                                                    searchable
                                                    multiple
                                />
                            </div>

                            <div class="w-1/4 sm:w-1/5">
                                <x-ts-select.styled :options="$this->categoryOptions"
                                                    :label="__('item.category')"
                                                    select="label:name|value:id"
                                                    placeholder="{{ __('item.filter-with-category') }}"
                                                    wire:model.live="categoryId"
                                                    searchable
                                />
                            </div>

                            <div class="w-1/4 sm:w-1/5">
                                <x-ts-select.styled :options="$this->ownerOptions"
                                                    :label="__('item.owner')"
                                                    select="label:name|value:id"
                                                    placeholder="{{ __('item.filter-with-owner') }}"
                                                    wire:model.live="owner"
                                                    required
                                />
                            </div>

                            <div class="w-1/4 sm:w-1/5">
                                <x-ts-select.styled :options="$this->keywordOptions"
                                                    :label="__('item.keyword')"
                                                    select="label:name|value:name"
                                                    placeholder="{{ __('item.filter-with-keyword') }}"
                                                    wire:model.live="keywords"
                                                    searchable
                                                    multiple
                                />
                            </div>
                        </div>


                        <x-ts-table :$headers :$rows :$sort striped filter paginate id="items">
                            @interact('column_image', $row)
                            @if($row->image)
                                <img class="inline max-w-48 max-h-48" src="{{ assetUrl($row->image)}}"
                                     alt="{{ __('item.image') }}"/>
                            @else
                                {{ __('item.no-image') }}
                            @endif

                            @endinteract
                            @interact('column_actions', $row)
                            <div class="flex justify-between" wire:key="$row->id">
                                <x-ts-modal title="{{ __('tallstackui.share') }} {{ $row->name }}" persistent wire>
                                    <form wire:submit="share({{ $row->id }})">
                                        <x-ts-select.styled label="{{ __('friend.select-user-to-request') }}"
                                                            hint="{{ __('friend.choose-only-one') }}"
                                                            :options="$this->friendOptions"
                                                            select="label:name|value:id"
                                                            wire:model.live="recipient_id"
                                                            searchable
                                                            required
                                        />

                                        <div class="flex justify-between items-center gap-4 mt-3">
                                            <x-primary-button>
                                                {{ __('tallstackui.send') }}
                                            </x-primary-button>

                                            <x-danger-button wire:click.prevent="$toggle('modal')">
                                                {{ __('tallstackui.cancel') }}
                                            </x-danger-button>
                                        </div>
                                    </form>
                                </x-ts-modal>

                                <x-ts-button round
                                             color="green"
                                             icon="share"
                                             position="left"
                                             wire:click.prevent="$toggle('modal')"
                                >
                                    {{ __('tallstackui.share') }}
                                </x-ts-button>

                                <x-ts-button round
                                             color="green"
                                             icon="pencil"
                                             position="left"
                                             href="{{ route('items.edit-item', ['item' => $row]) }}"
                                             wire:navigate.hover
                                >
                                    {{ __('tallstackui.edit') }}
                                </x-ts-button>

                                <x-ts-button round
                                             color="red"
                                             icon="trash"
                                             position="left"
                                             wire:click="delete({{ $row->id }})"
                                             wire:confirm="{{ __('item.delete-confirm') }}"
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
