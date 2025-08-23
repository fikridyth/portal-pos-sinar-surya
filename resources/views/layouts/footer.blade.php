<footer class="text-center text-lg-start bg-body-tertiary mt-auto" style="color: black;">
    <div class="d-flex justify-content-between p-1">
        <div class="mx-3">POS #01 - LISTI</div>
        <div>NETWORK</div>
        <div>© 2024 Copyright: <a class="text-reset fw-bold" href="#">DigiBiz Indonesia</a></div>
        <div id="current-date"></div>
        <div id="current-time" class="mx-3"></div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const timeString = now.toLocaleTimeString('id-ID');
    
            document.getElementById('current-date').innerText = dateString;
            document.getElementById('current-time').innerText = timeString;
        }
    
        // Update the time every second
        setInterval(updateTime, 1000);
        // Initial call to display time immediately
        updateTime();
    </script>
</footer>