<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        class="btn btn-danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</button>

    {{-- This part requires Alpine.js for the modal functionality --}}
    <div
        x-show="show"
        x-on:open-modal.window="show = ($event.detail === 'confirm-user-deletion')"
        x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        style="display: none;"
    >
        <div @click.away="show = false" class="bg-white p-6 rounded-lg shadow-xl">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-6">
                    <label for="password" class="sr-only">{{ __('Password') }}</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control mt-1 block w-3/4"
                        placeholder="{{ __('Password') }}"
                    />
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" class="btn btn-secondary" @click="show = false">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="btn btn-danger ms-3">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
