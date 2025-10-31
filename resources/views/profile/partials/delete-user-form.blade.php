<section class="space-y-6" x-data="{ showConfirmModal: false }">
    <header>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-black">
            Delete Account
        </h2>
        <p class="mt-2 text-base text-gray-600 dark:text-gray-900">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
            your account, please download any data or information that you wish to retain.
        </p>
    </header>

    {{-- This button now toggles the modal directly --}}
    <button @click="showConfirmModal = true"
        class="bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-red-500/30">
        Delete Account
    </button>

    {{-- Confirmation Modal --}}
    <div x-show="showConfirmModal" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

        <div @click.away="showConfirmModal = false"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <label for="password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input id="delete-password" name="password" {{-- Name must be 'password' for the controller --}} type="password"
                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900 dark:text-gray-200 mt-1"
                        placeholder="Password" required />
                    {{-- Displays validation errors specifically for the password field --}}
                    @if ($errors->userDeletion->has('password'))
                        <p class="text-sm text-red-500 mt-2">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="showConfirmModal = false"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
