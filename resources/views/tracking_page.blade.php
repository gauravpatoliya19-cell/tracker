<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <p>Loading, please wait...</p>

    <form id="geoForm" action="{{ route('save.exact.location') }}" method="POST">
        @csrf
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">
    </form>

    <script>
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;
                    document.getElementById('geoForm').submit();
                }, function(error) {
                    // જો યુઝર પરમિશન ના આપે તો પણ રીડાયરેક્ટ કરી દો
                    document.getElementById('geoForm').submit();
                }, {enableHighAccuracy: true});
            } else {
                document.getElementById('geoForm').submit();
            }
        };
    </script>
</body>
</html>
