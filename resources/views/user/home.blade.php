@extends('layouts.user')

@section('content')

<div class="container px-6 mx-auto grid">
   
        <br>
        <div class="container mx-auto">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="bg-gray-200 p-4 text-white bg-green-500">
                        <h3 class="text-lg font-semibold text-white">Absen Masuk</h3>
                        <p>Jam Masuk: {{ $jam_masuk }}</p>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-200 p-4 text-white bg-red-500">
                        <h3 class="text-lg font-semibold">Absen Pulang</h3>
                        <p>Jam Pulang: {{ $jam_keluar }}</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <form id="aduan_form" action="{{ route('user.submit.absen') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <button type="button" id="toggleMapBtn" class="btn text-white bg-blue-500 py-2 px-4 rounded mb-3">
                    Hide Map
                </button>
                <div class="container" style="width: 100%; height: 200px;" id="mapContainer">
                    <div id="map" style="width: 100%; height: 100%;"></div>
                </div>
                <input type="hidden" id="selected_location" name="lokasi_absen">
            </div>
            @if (!$absenMasuk || !$jam_keluar)
                <button type="submit" id="submitBtn" class="btn {{ $absenMasuk ? 'text-white bg-red-500' : 'text-white bg-green-500' }} py-2 px-4 rounded" disabled>
                    {{ $absenMasuk ? 'Absen Pulang' : 'Absen Masuk' }}
                </button>
            @endif
        </form>
        <br>
        <div>
        <a href="{{ route('user.printPDF', ['year' => $year, 'month' => $month]) }}" class="btn bg-blue-500 text-white py-2 px-4 rounded">Cetak Laporan Absensi</a>
        </div>
        <br>
        <h2 class="my-6 text-2md font-semibold text-gray-700 dark:text-gray-200 text-center">
            History Absensi
            </h2>
        <table class="table-auto divide-y divide-gray-200 w-auto mx-auto">
    <thead>
        <tr class="bg-gray-200 text-gray-600 uppercase text-xs"> <!-- Menggunakan text-xs untuk font kecil -->
            <th class="px-4 py-2 text-left">Tanggal Presensi</th> <!-- Mengurangi padding -->
            <th class="px-4 py-2 text-left">Lokasi Absen</th>
            <th class="px-4 py-2 text-left">Jam Masuk</th>
            <th class="px-4 py-2 text-left">Jam Keluar</th>
            <th class="px-4 py-2 text-left">Status</th>
        </tr>
    </thead>
    <tbody class="text-gray-900 dark:text-gray-300 text-xs font-light divide-y divide-gray-200"> <!-- Menggunakan text-xs untuk font kecil -->
        @foreach($absensis as $absen)
        <tr class="hover:bg-black-200">
            <td class="px-4 py-2 whitespace-nowrap">{{ $absen->tgl_presensi }}</td> <!-- Mengurangi padding -->
            <td class="px-4 py-2">{{ $absen->lokasi_absen }}</td>
            <td class="px-4 py-2">{{ $absen->jam_masuk }}</td>
            <td class="px-4 py-2">{{ $absen->jam_keluar }}</td>
            <td class="px-4 py-2">{{ $absen->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>
        {{ $absensis->links() }}
        <br>
    
</div>




  
       
   





<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mapContainer = document.getElementById('mapContainer');
        var toggleMapBtn = document.getElementById('toggleMapBtn');
        var submitBtn = document.getElementById('submitBtn');

        // Pre-split latitude and longitude values
        var officeLocations = [
            { lat: parseFloat("{{ explode(',', $office1->lokasi_kantor)[0] }}"), lng: parseFloat("{{ explode(',', $office1->lokasi_kantor)[1] }}"), radius: {{ $office1->radius }} },
            { lat: parseFloat("{{ explode(',', $office2->lokasi_kantor)[0] }}"), lng: parseFloat("{{ explode(',', $office2->lokasi_kantor)[1] }}"), radius: {{ $office2->radius }} },
            { lat: parseFloat("{{ explode(',', $office3->lokasi_kantor)[0] }}"), lng: parseFloat("{{ explode(',', $office3->lokasi_kantor)[1] }}"), radius: {{ $office3->radius }} }
        ];

      
        var map = L.map('map', {
            center: [officeLocations[0].lat, officeLocations[0].lng],
            zoom: 16,
            zoomControl: false,  // Disable zoom control buttons
            scrollWheelZoom: false,  // Disable scroll wheel zoom
            doubleClickZoom: false,  // Disable double click zoom
            dragging: false,  // Disable dragging
            touchZoom: false,  // Disable touch zoom
            boxZoom: false,  // Disable box zoom
            keyboard: false  // Disable keyboard navigation
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        officeLocations.forEach(function(office) {
            L.circle([office.lat, office.lng], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.2,
                radius: office.radius
            }).addTo(map);
        });

        var marker = L.marker([officeLocations[0].lat, officeLocations[0].lng], { draggable: false }).addTo(map);

        function updateMarker() {
            var markerLocation = marker.getLatLng();
            document.getElementById('selected_location').value = markerLocation.lat + ',' + markerLocation.lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${markerLocation.lat}&lon=${markerLocation.lng}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('lokasi_absen').value = data.display_name;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function isWithinAnyRadius(lat, lng) {
            return officeLocations.some(function(office) {
                return isWithinRadius(lat, lng, office.lat, office.lng, office.radius);
            });
        }

        function isWithinRadius(lat1, lng1, lat2, lng2, radius) {
            var R = 6371e3; // metres
            var φ1 = lat1 * Math.PI / 180; // φ, λ in radians
            var φ2 = lat2 * Math.PI / 180;
            var Δφ = (lat2 - lat1) * Math.PI / 180;
            var Δλ = (lng2 - lng1) * Math.PI / 180;

            var a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            var distance = R * c; // in metres
            return distance <= radius;
        }

        function onLocationFound(e) {
            var userLatLng = e.latlng;
            marker.setLatLng(userLatLng);
            updateMarker();
            map.setView(userLatLng, 16);

            var withinRadius = isWithinAnyRadius(userLatLng.lat, userLatLng.lng);
            submitBtn.disabled = !withinRadius;

            if (!withinRadius) {
                alert('You are not within the required radius to check in.');
            }
        }

        function onLocationError(e) {
            alert(e.message);
        }

        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);

        map.locate({ setView: true, maxZoom: 16 });

        toggleMapBtn.addEventListener('click', function() {
            if (mapContainer.style.display === 'none') {
                mapContainer.style.display = 'block';
                toggleMapBtn.textContent = 'Hide Map';
                map.invalidateSize();
            } else {
                mapContainer.style.display = 'none';
                toggleMapBtn.textContent = 'Show Map';
            }
        });

     
    document.getElementById('aduan_form').addEventListener('submit', function(event) {
        var markerLocation = marker.getLatLng();
        var withinRadius = isWithinAnyRadius(markerLocation.lat, markerLocation.lng);
        var currentTime = new Date();
        var startTime = new Date();
        var endTime = new Date();

        startTime.setHours(8, 45, 0); // Set start time to 08:45 AM
        endTime.setHours(24, 0, 0); // Set end time to 05:00 PM

        if (!withinRadius) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Outside Allowed Radius',
                text: 'Anda berada di luar radius yang diizinkan untuk melakukan absen.'
            });
        } else if (currentTime < startTime || currentTime > endTime) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Outside Working Hours',
                text: 'Absen diluar jam kerja tidak diizinkan.'
            });
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Anda berhasil melakukan absen.',
                showConfirmButton: false,
                timer: 3500
            });
        }
    });

    });
</script>


@endsection
