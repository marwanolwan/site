<?php// filepath: C:/wamp64/www/site/views/locations.php?>
<?php require_once 'layouts/header.php'; ?>

<!-- 1. بانر صفحة المواقع -->
<div class="locations-page-banner" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['locations_banner_color1'] ?? '#1d4ed8'); ?>, <?php echo htmlspecialchars($settings['locations_banner_color2'] ?? '#3b82f6'); ?>);">
    <div class="container banner-grid">
        <div class="banner-text">
            <h1><?php echo htmlspecialchars($translations['locations_banner_title'] ?? 'Our Points of Sale'); ?></h1>
            <p><?php echo htmlspecialchars($translations['locations_banner_subtitle'] ?? ''); ?></p>
        </div>
        <div class="banner-image">
            <div class="banner-image-circle">
                <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['locations_banner_image'] ?? ''); ?>" alt="Map Icon">
            </div>
        </div>
    </div>
</div>

<style>
    .locations-page-banner {
        padding: 50px 0;
        clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        color: #fff;
        /* (الإصلاح) تقليل التداخل السلبي لإعطاء مسافة أكبر */
        margin-bottom: -60px; 
        position: relative;
        z-index: 5;
    }
    #map {
        height: 600px;
        width: 100%;
        border-radius: 12px;
        border: 1px solid #ddd;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .map-container {
        padding: 50px 0;
        position: relative;
        z-index: 4;
    }
    .gm-style-iw-d {
        font-family: 'Cairo', sans-serif !important;
    }
    .info-window-content {
        text-align: right;
        line-height: 1.8;
    }
    .info-window-content h4 {
        margin: 0 0 10px 0;
        font-size: 1.2rem;
    }
    .info-window-content p {
        margin: 0 0 15px 0;
    }
    .info-window-content a {
        display: inline-block;
        padding: 8px 15px;
        background-color: var(--primary-orange);
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }
</style>

<div class="container map-container">
    <div id="map"></div>
</div>

<!-- زر العودة للرئيسية -->
<div class="back-home-btn-container" style="padding-bottom: 50px;">
    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_back_to_home'] ?? 'Back to Homepage'); ?></a>
</div>

<script>
    function initMap() {
        const locations = <?php echo json_encode($salesPoints); ?>;
        const mapOptions = {
            center: { lat: 31.9522, lng: 35.2332 },
            zoom: 8,
        };
        if (locations.length > 0) {
            mapOptions.center = { lat: parseFloat(locations[0].latitude), lng: parseFloat(locations[0].longitude) };
            mapOptions.zoom = 9;
        }
        const map = new google.maps.Map(document.getElementById("map"), mapOptions);
        let currentInfoWindow = null;
        locations.forEach(location => {
            const marker = new google.maps.Marker({
                position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
                map: map,
                title: location.name,
            });
            const contentString = `<div class="info-window-content"><h4>${location.name}</h4><p><strong>العنوان:</strong> ${location.address}<br><strong>الهاتف:</strong> ${location.phone || 'غير متوفر'}</p><a href="#" onclick="getDirections(${location.latitude}, ${location.longitude}); return false;">احصل على الاتجاهات</a></div>`;
            const infowindow = new google.maps.InfoWindow({ content: contentString, ariaLabel: location.name });
            marker.addListener("click", () => {
                if (currentInfoWindow) { currentInfoWindow.close(); }
                infowindow.open({ anchor: marker, map });
                currentInfoWindow = infowindow;
            });
        });
    }
    function getDirections(destLat, destLng) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const startLat = position.coords.latitude;
                const startLng = position.coords.longitude;
                const url = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}`;
                window.open(url, '_blank');
            }, function() {
                const url = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}`;
                window.open(url, '_blank');
            });
        } else { alert("الموقع الجغرافي غير مدعوم من هذا المتصفح."); }
    }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo htmlspecialchars($apiKey); ?>&callback=initMap"></script>

<?php require_once 'layouts/footer.php'; ?>