<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Count Time') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="sections-container">
                <!-- Template Bagian (Loket) -->
                <template id="section-template">
                    <div class="section bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                        <div class="section-header flex justify-between items-center">
                            <div>
                                <input type="text" class="text-2xl font-bold bg-transparent border-0 focus:outline-none"
                                    placeholder="Nama Bagian">
                                <input type="text" class="mt-2 bg-gray-100 dark:bg-gray-700 rounded-md p-2"
                                    placeholder="Nama Pengurus">
                            </div>
                            <button class="delete-section bg-red-500 text-white px-4 py-2 rounded-md">Hapus
                                Bagian</button>
                        </div>
                        <div class="services mt-4">
                            <!-- Template Pelayanan -->
                            <template id="service-template">
                                <div class="service bg-gray-100 dark:bg-gray-700 rounded-md p-4 mb-4">
                                    <div class="service-header flex justify-between items-center">
                                        <div>
                                            <input type="text"
                                                class="text-xl font-semibold bg-transparent border-0 focus:outline-none"
                                                placeholder="Nama Pelayanan">
                                            <textarea class="mt-2 w-full bg-transparent border-0 focus:outline-none"
                                                placeholder="Deskripsi Pelayanan"></textarea>
                                        </div>
                                        <button class="delete-service bg-red-500 text-white px-4 py-2 rounded-md">Hapus
                                            Pelayanan</button>
                                    </div>
                                    <div class="timer mt-4">
                                        <label class="block mb-2 text-sm font-medium">Atur Waktu Hitung Mundur</label>
                                        <div class="time-input-container flex items-center space-x-2">
                                            <div class="time-input-group">
                                                <span class="label block text-sm font-medium mb-1">Jam</span>
                                                <div class="time-input flex items-center">
                                                    <button class="decrement text-2xl font-semibold px-2">-</button>
                                                    <input type="text" value="00"
                                                        class="time-value text-2xl font-semibold text-center w-12 mx-1 border-0 bg-transparent focus:outline-none">
                                                    <button class="increment text-2xl font-semibold px-2">+</button>
                                                </div>
                                            </div>

                                            <div class="time-input-group">
                                                <span class="label block text-sm font-medium mb-1">Menit</span>
                                                <div class="time-input flex items-center">
                                                    <button class="decrement text-2xl font-semibold px-2">-</button>
                                                    <input type="text" value="00"
                                                        class="time-value text-2xl font-semibold text-center w-12 mx-1 border-0 bg-transparent focus:outline-none">
                                                    <button class="increment text-2xl font-semibold px-2">+</button>
                                                </div>
                                            </div>

                                            <div class="time-input-group">
                                                <span class="label block text-sm font-medium mb-1">Detik</span>
                                                <div class="time-input flex items-center">
                                                    <button class="decrement text-2xl font-semibold px-2">-</button>
                                                    <input type="text" value="00"
                                                        class="time-value text-2xl font-semibold text-center w-12 mx-1 border-0 bg-transparent focus:outline-none">
                                                    <button class="increment text-2xl font-semibold px-2">+</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button
                                            class="start-timer mt-2 bg-blue-500 text-white px-4 py-2 rounded-md">Mulai
                                            Timer</button>
                                        <div class="countdown-display mt-2 text-2xl font-bold text-green-500">00:00:00
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button class="add-service bg-green-500 text-white px-4 py-2 rounded-md mt-4">Tambahkan
                            Pelayanan</button>
                    </div>
                </template>
            </div>
            <button id="add-section" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-6">Tambahkan Bagian</button>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menambahkan bagian baru
    function addSection() {
        let container = document.getElementById('sections-container');
        let templateSection = document.getElementById('section-template').content.cloneNode(true);
        container.appendChild(templateSection);
        attachSectionListeners(container.lastElementChild);
    }

    // Fungsi untuk menambahkan pelayanan baru di dalam bagian
    function addService(button) {
        let servicesContainer = button.previousElementSibling;
        let templateService = document.getElementById('service-template').content.cloneNode(true);
        servicesContainer.appendChild(templateService);
        attachServiceListeners(servicesContainer.lastElementChild);
    }

    // Fungsi untuk menghapus bagian
    function deleteSection(button) {
        if (confirm('Apakah Anda yakin ingin menghapus bagian ini?')) {
            button.closest('.section').remove();
        }
    }

    // Fungsi untuk menghapus pelayanan
    function deleteService(button) {
        if (confirm('Apakah Anda yakin ingin menghapus pelayanan ini?')) {
            button.closest('.service').remove();
        }
    }

    // Fungsi untuk mengatur timer
    function startTimer(button) {
        let service = button.closest('.service');
        let countdownElement = button.previousElementSibling;
        let timeValues = Array.from(service.querySelectorAll('.time-value')).map(input => parseInt(input
            .value) || 0);
        let countdownTime = timeValues[0] * 3600 + timeValues[1] * 60 + timeValues[2];
        let displayElement = button.nextElementSibling;

        // Clear any existing timer intervals
        if (service.timerInterval) {
            clearInterval(service.timerInterval);
        }

        service.timerInterval = setInterval(() => {
            countdownTime--;

            let hours = Math.floor(countdownTime / 3600);
            let minutes = Math.floor((countdownTime % 3600) / 60);
            let seconds = countdownTime % 60;

            displayElement.textContent =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (countdownTime <= 0) {
                clearInterval(service.timerInterval);
                displayElement.textContent = '00:00:00';
            } else if (countdownTime <= (timeValues[0] * 3600 + timeValues[1] * 60 + timeValues[2]) /
                4) {
                displayElement.classList.remove('text-yellow-500', 'text-green-500');
                displayElement.classList.add('text-red-500');
            } else if (countdownTime <= (timeValues[0] * 3600 + timeValues[1] * 60 + timeValues[2]) /
                2) {
                displayElement.classList.remove('text-green-500');
                displayElement.classList.add('text-yellow-500');
            }
        }, 1000);
    }

    // Attach event listeners to new sections and services
    function attachSectionListeners(section) {
        section.querySelector('.delete-section').addEventListener('click', function() {
            deleteSection(this);
        });

        section.querySelector('.add-service').addEventListener('click', function() {
            addService(this);
        });
    }

    function attachServiceListeners(service) {
        service.querySelector('.delete-service').addEventListener('click', function() {
            deleteService(this);
        });

        service.querySelector('.start-timer').addEventListener('click', function() {
            startTimer(this);
        });
    }

    // Initial attachment for existing sections and services
    document.querySelectorAll('.section').forEach(section => attachSectionListeners(section));
    document.querySelectorAll('.service').forEach(service => attachServiceListeners(service));

    // Attach the addSection function to the button
    document.getElementById('add-section').addEventListener('click', addSection);
});
</script>

<style>
/* Custom styles for time input */
.time-input-group {
    display: flex;
    flex-direction: column;
}

.time-input {
    display: flex;
    align-items: center;
}

.time-value {
    width: 3.5rem;
    text-align: center;
}

button.decrement,
button.increment {
    background-color: transparent;
    border: none;
    cursor: pointer;
    outline: none;
    font-size: 1.5rem;
}
</style>