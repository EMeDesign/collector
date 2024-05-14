<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

new
#[Layout('layouts.app')]
class extends Component {
    use Interactions;

    /**
     * @var int|null
     */
    public ?int $recipient_id;

    /**
     * @return array
     */
    #[Computed()]
    public function userOptions(): array
    {
        return User::query()
            ->where('id', '!=', auth()->user()->id)
            ->get()
            ->map(function (User $user) {
                $user->description = $user->email;
                return $user;
            })
            ->toArray();
    }

    /**
     * Create new furniture.
     *
     * @return void
     */
    public function create(): void
    {
        $recipient = User::findOrFail($this->recipient_id);
        $sender = auth()->user();

        // check if self request
        if ($recipient->is($sender)) {
            $this->toast()
                ->error(trans('tallstackui.error'), trans('friend.cannot-send-self'))
                ->send();
            return;
        }

        // check if already friends
        if ($sender->isFriendWith($recipient)) {
            $this->toast()
                ->error(trans('tallstackui.error'), trans('friend.already-friend', ['name' => $recipient->name]))
                ->send();
            return;
        }

        // check if repeat
        if ($sender->hasSentFriendRequestTo($recipient)) {
            $this->toast()
                ->error(trans('tallstackui.error'),  trans('friend.already-send-request', ['name' => $recipient->name]))
                ->send();
            return;
        }

        // check if repeat (reverse)
        if ($recipient->hasSentFriendRequestTo($sender)) {
            $this->toast()
                ->error(trans('tallstackui.error'), trans('friend.already-send-request-rev', ['name' => $recipient->name]))
                ->send();
            return;
        }

        //check if already denied
        if ($sender->hasBennDeniedBy($recipient)) {
            $this->toast()
                ->error(trans('tallstackui.error'), trans('friend.already-denied', ['name' => $recipient->name]))
                ->send();
            return;
        }

        // Send request
        $sender->befriend($recipient);

        $this->toast()
            ->success(trans('tallstackui.success'), trans('friend.send-success', ['name' => $recipient->name]))
            ->flash()
            ->send();

        $this->redirect(route('friends.search-friend'));
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
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('friend.friend-request') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('friend.send-new-friend-request') }}
                            </p>
                        </header>

                        <x-ts-errors/>

                        <form class="mt-6 space-y-6" wire:submit="create()">
                            <x-ts-select.styled label="{{ __('friend.select-user-to-request') }}"
                                                hint="{{ __('friend.choose-only-one') }}"
                                                :options="$this->userOptions"
                                                select="label:name|value:id"
                                                wire:model.blur="recipient_id"
                                                searchable
                                                required
                            />

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ __('tallstackui.send') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
