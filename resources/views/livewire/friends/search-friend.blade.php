<?php

use App\Models\Furniture;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Multicaret\Acquaintances\Models\Friendship;
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
     * Display user's friends.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function friends(): LengthAwarePaginator
    {
        $closure = function ($query) {
            $query->when($this->search, function (Builder $query) {
                return $query->where(function (Builder $query) {
                    $query->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%");
                });
            });
        };

        return auth()->user()->getFriendsPaginator($closure, $this->quantity);
    }

    /**
     * Unfriend a friend.
     *
     * @param int $friendId
     *
     * @return void
     */
    public function unfriend(int $friendId): void
    {
        $friend = User::findOrFail($friendId);

        auth()->user()->unfriend($friend);

        $this->toast()
            ->success('Success', trans('friend.unfriend-success', ['name' => $friend->name]))
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
                    'index' => 'name',
                    'label' => trans('friend.name-table'),
                ],
                [
                    'index' => 'email',
                    'label' => trans('friend.email-table'),
                ],
                [
                    'index' => 'actions',
                    'label' => trans('tallstackui.actions'),
                    'sortable' => false
                ],
            ],

            'rows' => $this->friends,
        ];
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('friend.friends') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('friend.friend-information') }}
                            </h2>
                            <x-ts-button class="mt-5 mb-5"
                                         type="button"
                                         href="{{ route('friends.create-friend') }}"
                                         round
                                         wire:navigate.hover>
                                {{ __('friend.new-friend') }}
                            </x-ts-button>
                        </header>

                        <x-ts-table :$headers :$rows :$sort striped filter paginate id="friends">
                            @interact('column_actions', $row)
                            <div class="flex justify-between" wire:key="$row->id">
                                <x-ts-button round
                                             color="red"
                                             icon="trash"
                                             position="left"
                                             wire:click="unfriend({{ $row->id }})"
                                >
                                    {{ __('friend.unfriend') }}
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
