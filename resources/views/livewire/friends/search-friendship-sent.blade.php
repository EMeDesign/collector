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
    public function sentFriendships(): LengthAwarePaginator
    {
        return auth()->user()->getAllSentFriendships(perPage: $this->quantity, status: empty($this->status) ? null : $this->status);
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
                    'index' => 'recipient.name',
                    'label' => trans('friend.name-table'),
                ],
                [
                    'index' => 'recipient.email',
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
            ],

            'rows' => $this->sentFriendships,
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
                                {{ __('friend.sent-friend-information') }}
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
                        />
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
