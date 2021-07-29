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

var gangs_xhr = new XMLHttpRequest();
gangs_xhr.onload = function(){

	console.log(this.response);

	var gangs_cfg = { };
	var gangs = [];

	this.response.forEach(function(gang_info){
			gangs.push(gangs_cfg[gang_info["name"]] = L.layerGroup().addTo(map));
			}, this);

	L.control.layers(null, gangs_cfg).addTo(map);

	var regions_xhr = new XMLHttpRequest();
	regions_xhr.onload = function() {
		console.log(this.response);

		this.response.forEach(function(region){	
			var polygon = L.polygon(region["points"], {color: 'red'}).addTo(map);
			gangs[region["owner"] - 1].addLayer(polygon);

			polygon.bindPopup(region["name"]);
				}, this);
	}
	regions_xhr.open("GET", "regions.php");
	regions_xhr.responseType = "json";
	regions_xhr.send();
};

gangs_xhr.open("GET", "gangs.php");
gangs_xhr.responseType = "json";
gangs_xhr.send();

// PANELER Å SKIT
