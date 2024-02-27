<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public string $tokenName = '';

    /**
     * Get current user's access tokens.
     */
    #[\Livewire\Attributes\Computed]
    public function accessTokens()
    {
        return Auth::user()->tokens;
    }

    /**
     * Create a new access token.
     */
    public function createNewAccessToken(): void
    {
        $validated = $this->validate([
            'tokenName' => ['required', 'string', 'max:255', 'unique:personal_access_tokens,name'],
        ]);

        $token = Auth::user()->createToken($validated['tokenName']);

        $this->tokenName = '';

        $this->dispatch('token-created', tokenContent: explode('|', $token->plainTextToken)[1]);
    }

    /** Delete the selected access token */
    public function delete(int $tokenId): void
    {
        $token = Auth::user()->tokens()->where('id', $tokenId)->delete();

        $this->toast()
            ->success('Success', 'Your Token Has Been Deleted!')
            ->send();
    }

    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'id', 'label' => '#'],
                ['index' => 'name', 'label' => 'Name'],
                ['index' => 'action', 'label' => 'Action'],
            ],

            'rows' => $this->accessTokens,
        ];
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Access Token') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Manage your access tokens.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6 max-w-fit min-w-96">
        <x-ts-table :$headers :$rows id="access-tokens">
            <!-- The $row represents the instance of \App\Model\User of each row -->
            @interact('column_action', $row)
            <x-ts-button.circle color="red"
                             icon="trash"
                             wire:click="delete('{{ $row->id }}')" />
            @endinteract
        </x-ts-table>
    </div>

    <form wire:submit="createNewAccessToken" class="mt-6 space-y-6 max-w-xl">
        <div>
            <x-input-label for="tokenName" :value="__('TokenName')"/>
            <x-text-input wire:model="tokenName" id="tokenName" name="tokenName" type="text" class="mt-1 block w-full" required
                          autofocus autocomplete="tokenName"/>
            <x-input-error class="mt-2" :messages="$errors->get('tokenName')"/>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>

@script
<script>
    Livewire.on('token-created', ({ tokenContent }) => {
        navigator.clipboard.writeText(tokenContent).then(
            function () {
                $interaction('toast')
                    .success('Success', 'Access Token Content Has Been Write Into Your Clipboard.')
                    .send();
            },
            function () {
                $interaction('toast')
                    .warning('Warning', 'Access Token Created Successfully, But Failed To Write Content Into Your Clipboard.')
                    .send();
            },
        );
    })
</script>
@endscript
