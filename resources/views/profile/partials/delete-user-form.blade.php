<section class="space-y-6" x-data="{ showConfirmModal: false }">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-black-100">
            Delete Account
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-black-400">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    {{-- This button now toggles the modal directly --}}
    <button @click="showConfirmModal = true" class="px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700">
        Delete Account
    </button>

    {{-- Confirmation Modal --}}
    <div x-show="showConfirmModal" x-cloak
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

        <div @click.away="showConfirmModal = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-md shadow-sm"
                        placeholder="Password"
                        required
                    />
                    {{-- Displays validation errors specifically for the password field --}}
                    @if($errors->userDeletion->has('password'))
                        <p class="text-sm text-red-500 mt-2">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="showConfirmModal = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-500">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
