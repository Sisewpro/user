<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Wealthness Spa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tombol Tambah Loket -->
            <form action="{{ route('timer-cards.store') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah Loket</button>
            </form>

            <div class="grid grid-cols-4 gap-4">
                @foreach($timerCards as $card)
                    <x-timer-card 
                        :cardName="$card->card_name" 
                        :userName="$card->user ? $card->user->name : 'Staff ' . $card->id"
                        :time="$card->time" 
                        :status="$card->status"
                        :id="$card->id"
                    />
                @endforeach 
            </div>
        </div>
    </div>

    <!-- Modal for Setting -->
    <div id="settingModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal Content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="settingForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Loket</h3>
                        <div class="mt-2">
                            <input type="text" name="card_name" placeholder="Nama Loket" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
                            <select name="user_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
                                <option value="">Pilih User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="time" placeholder="00:00:00" value="00:00:00" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm" onclick="toggleModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-8 w-1/3">
            <h2 class="text-xl font-semibold mb-4">Ubah Loket</h2>
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="card_id" id="editCardId">

                <div class="mb-4">
                    <label for="editCardName" class="block text-gray-700">Nama Loket</label>
                    <input type="text" id="editCardName" name="card_name" class="border-2 border-gray-300 rounded-lg p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="editTime" class="block text-gray-700">Set Timer</label>
                    <input type="text" id="editTime" name="time" class="border-2 border-gray-300 rounded-lg p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="editUserId" class="block text-gray-700">Pilih Staff</label>
                    <select id="editUserId" name="user_id" class="border-2 border-gray-300 rounded-lg p-2 w-full">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    function openEditModal(id, cardName, time, userId) {
        document.getElementById('editCardId').value = id;
        document.getElementById('editCardName').value = cardName;
        document.getElementById('editTime').value = time;
        document.getElementById('editUserId').value = userId; // Mengatur dropdown ke user yang benar

        document.getElementById('editForm').action = `/timer-cards/${id}`;

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
