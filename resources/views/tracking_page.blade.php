window.onload = function() {
    if (navigator.geolocation) {
        // High Accuracy અને Timeout ઉમેરો
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
            document.getElementById('geoForm').submit();
        }, function(error) {
            // જો એરર આવે તો પણ ગૂગલ પર તો મોકલી જ દેશે
            document.getElementById('geoForm').submit();
        }, {
            enableHighAccuracy: true,
            timeout: 5000, // ૫ સેકન્ડ રાહ જોશે
            maximumAge: 0
        });
    } else {
        document.getElementById('geoForm').submit();
    }
};
