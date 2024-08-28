<div id="card_{{ $id }}" class="bg-white shadow-md rounded-lg p-4 mt-4 max-w-sm mx-auto">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">{{ $cardName }}</h2>
        <div class="flex space-x-2">
            <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" onclick="openEditModal('{{ $id }}', '{{ $cardName }}', '{{ $time }}')">Ubah</button>
            <form action="{{ route('timer-cards.destroy', $id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Hapus</button>
            </form>
        </div>
    </div>

    <div class="mt-2 text-sm text-gray-600">
        <p>{{ $userName }}</p>
    </div>

    <div class="text-center">
        <label id="statusDisplay_{{ $id }}" for="timeInput_{{ $id }}" class="block text-lg font-bold text-green-500">{{ $status }}</label>
        <input id="timeInput_{{ $id }}" type="text" value="{{ $time }}" class="text-2xl font-bold text-gray-700 text-center border-none rounded-lg w-2/3 mx-auto">
    </div>

    <div class="mt-4 flex justify-center space-x-2">
        <button id="startTimer_{{ $id }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Mulai</button>
        <button id="pauseTimer_{{ $id }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Jeda</button>
        <button id="resetTimer_{{ $id }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Reset</button>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeTimerCard("{{ $id }}");
    });

    function initializeTimerCard(cardId) {
        let timerInterval;
        let totalSeconds = 0;

        function parseTimeInput(time) {
            const [hrs, mins, secs] = time.split(':').map(Number);
            return (hrs * 3600) + (mins * 60) + secs;
        }

        function updateTimerDisplay() {
            const hrs = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
            const mins = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            const secs = String(totalSeconds % 60).padStart(2, '0');
            document.getElementById('timeInput_' + cardId).value = `${hrs}:${mins}:${secs}`;
        }

        function updateStatus(status) {
            const statusDisplay = document.getElementById('statusDisplay_' + cardId);
            statusDisplay.textContent = status;
            statusDisplay.classList.toggle('text-green-500', status === "Ready");
            statusDisplay.classList.toggle('text-gray-500', status === "Running");
        }

        document.getElementById('startTimer_' + cardId).addEventListener('click', () => {
            if (!timerInterval) {
                totalSeconds = parseTimeInput(document.getElementById('timeInput_' + cardId).value);
                if (totalSeconds > 0) {
                    timerInterval = setInterval(() => {
                        totalSeconds--;
                        updateTimerDisplay();
                        updateStatus("Running");
                        if (totalSeconds <= 0) {
                            clearInterval(timerInterval);
                            timerInterval = null;
                            updateStatus("Ready");
                            alert("Waktu Habis!");
                        }
                    }, 1000);
                } else {
                    alert("Waktu tidak valid!");
                }
            }
        });

        document.getElementById('pauseTimer_' + cardId).addEventListener('click', () => {
            clearInterval(timerInterval);
            timerInterval = null;
            updateStatus("Ready");
        });

        document.getElementById('resetTimer_' + cardId).addEventListener('click', () => {
            clearInterval(timerInterval);
            timerInterval = null;
            totalSeconds = 0;
            document.getElementById('timeInput_' + cardId).value = "00:00:00";
            updateStatus("Ready");
        });
    }
</script>
