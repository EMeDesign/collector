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
use Multicaret\Acquaintances\Status;
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
     * Status input.
     *
     * @var string|null
     */
    public ?string $status = '';

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
     * Status options.
     *
     * @return array
     */
    #[Computed]
    public function statusOptions(): array
    {
        return [
            ['status' => Status::PENDING,  'label' => trans('friend.pending')],
            ['status' => Status::ACCEPTED, 'label' => trans('friend.accepted')],
            ['status' => Status::DENIED,   'label' => trans('friend.denied')],
            ['status' => Status::BLOCKED,  'label' => trans('friend.blocked')],
        ];
    }

    /**
     * Display user's friend requests.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function receivedFriendships(): LengthAwarePaginator
    {
        return auth()->user()->getAllReceivedFriendships(perPage: $this->quantity, status: empty($this->status) ? null : $this->status);
    }

    /**
     * Accept a friend request.
     *
     * @param int $senderId
     * @return void
     */
    public function accept(int $senderId): void
    {
        $sender = \App\Models\User::findOrFail($senderId);
        auth()->user()->acceptFriendRequest($sender);

        $this->toast()
            ->success(trans('tallstackui.success'), trans('friend.accept-success', ['name' => $sender->name]))
            ->send();
    }

    /**
     * Accept a friend request.
     *
     * @param int $senderId
     * @return void
     */
    public function deny(int $senderId): void
    {
        $sender = \App\Models\User::findOrFail($senderId);
        auth()->user()->denyFriendRequest($sender);

        $this->toast()
            ->success(trans('tallstackui.success'), trans('friend.deny-success', ['name' => $sender->name]))
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
                    'index' => 'sender.name',
                    'label' => trans('friend.name-table'),
                ],
                [
                    'index' => 'sender.email',
                    'label' => trans('friend.email-table'),
                ],
                [
                    'index' => 'status',
                    'label' => trans('friend.status-table'),
                ],
                [
                    'index' => 'created_at',
                    'label' => trans('friend.request-date'),
                ],
                [
                    'index' => 'actions',
                    'label' => trans('tallstackui.actions'),
                    'sortable' => false
                ],
            ],

            'rows' => $this->receivedFriendships,
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
                                {{ __('friend.received-friend-information') }}
                            </h2>
                            <x-ts-button class="mt-5 mb-5"
                                         type="button"
                                         href="{{ route('friends.create-friend') }}"
                                         round
                                         wire:navigate.hover>
                                {{ __('friend.new-friend') }}
                            </x-ts-button>
                        </header>

                        <div class="w-1/4 sm:w-1/5">
                            <x-ts-select.styled :options="$this->statusOptions"
                                                :label="'Status'"
                                                select="label:label|value:status"
                                                placeholder="{{ __('friend.filter-with-status') }}"
                                                wire:model.live="status"
                            />
                        </div>

                        <x-ts-table :$headers
                                    :$rows
                                    :$sort
                                    :filter="['quantity' => 'quantity']"
                                    striped
                                    paginate
                                    id="friendships"
                        >
                            @interact('column_actions', $row)
                            @if($row->status === Status::PENDING)
                                <div class="flex justify-between" wire:key="$row->id">
                                    <x-ts-button round
                                                 color="green"
                                                 icon="pencil"
                                                 position="left"
                                                 wire:click="accept({{ $row->sender_id }})"
                                    >
                                        {{ __('friend.accept') }}
                                    </x-ts-button>

                                    <x-ts-button round
                                                 color="red"
                                                 icon="trash"
                                                 position="left"
                                                 wire:click="deny({{ $row->sender_id }})"
                                    >
                                        {{ __('friend.deny') }}
                                    </x-ts-button>
                                </div>
                            @endif
                            @endinteract
                        </x-ts-table>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
