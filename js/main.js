var map = L.map('map', {
	layers: layers,
}).setView([0, 0], 3);

var layers_cfg = { };
var layers = [];

[
"Misc",
"Police Stations",
"Hospitals",
"Banks",
"Stores",
"Repair Shops",
"Gas stations",
"Garages", 
].forEach(function(layer_name){
	layers.push(layers_cfg[layer_name] = L.layerGroup().addTo(map));
}, this);

L.control.layers(null, layers_cfg).addTo(map);

L.control.mousePosition().addTo(map);

// Reference the tiles
L.tileLayer('maps/gtav/{z}/{x}/{y}.png', {
	minZoom: 0, 
	maxZoom: 5, 
	continuousWorld: false,
	noWrap: true
	}).addTo(map);

var xhr = new XMLHttpRequest();
xhr.onload = function() {
	console.log(this.response);

	this.response.forEach(function(marker){
		var marker_cfg = { };
		
		if(marker["draggable"]){
			marker_cfg["draggable"] = true;
		}
		
		if(marker["icon"]){
			marker_cfg["icon"] = L.icon({
				iconUrl: marker["icon"],
				iconSize: [ 32, 32 ],
				//popupAnchor: [ -3, -76 ],
			});
		}	

		

		var map_marker = L.marker([marker["x_pos"], marker["y_pos"]], marker_cfg);//.addTo(map);
		

		if(marker["type"] == -1){
			map_marker.addTo(map);
		} else {
			layers[marker["type"]].addLayer(map_marker);//map_marker.addTo(layers[marker["type"]]);
		}

		if(marker["popup"]){
			map_marker.bindPopup(marker["popup"]);
		}
	}, this);
}
xhr.open("GET", "markers.php");
xhr.responseType = "json";
xhr.send();

var xhr = new XMLHttpRequest();
xhr.onload = function() {
	console.log(this.response);

	this.response.forEach(function(region){
		var polygon = L.polygon(region["points"], {color: 'red'}).addTo(map);

		//map.fitBounds(polygon.getBounds());
		polygon.bindPopup(region["name"]);
	}, this);
}
xhr.open("GET", "regions.php");
xhr.responseType = "json";
xhr.send();

// PANELER Å SKIT
