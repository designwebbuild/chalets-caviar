(function() {
    'use strict';

    /**
     * Google map class.
     *
     * @param selector
     * @param lon
     * @param lat
     * @param zoom
     * @returns {EsGoogleMap}
     */
    var EsGoogleMap = function(selector, lon, lat, zoom) {
        this.selector = selector;
        this.zoom = zoom || 16;
        this.lon = parseFloat(lon);
        this.lat = parseFloat(lat);
        this.markers = [];

        /**
         * Initialize google maps method.
         * @return object
         */
        this.init = function() {

            this.instance = new google.maps.Map(this.selector, {
                zoom: this.zoom,
                center: {lat: this.lat, lng: this.lon}
            });

            google.maps.event.trigger(this.selector, 'resize');

            return this;
        };

        /**
         *
         * Set marker to the map.
         *
         * @param position
         * @returns {EsGoogleMap}
         */
        this.setMarker = function(position) {
            position = {lat: this.lat, lng: this.lon} || position;

            this.markers.push(new google.maps.Marker({
                position: position,
                map: this.instance
            }));

            return this;
        };

        /**
         *
         * Get address info using coordinates.
         *
         * @param lat
         * @param lon
         * @param callback
         */
        this.getGeocoderInfo = function(lat, lon, callback) {
            var geocoder = new google.maps.Geocoder();
            var latLon = new google.maps.LatLng(lat, lon);

            geocoder.geocode({
                latLng: latLon
            }, callback);
        };

        return this;
    };

    window.EsGoogleMap = EsGoogleMap;
})();
