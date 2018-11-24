var bpfwp_map=bpfwp_map||{};function bpInitializeMap(){'use strict';bpfwp_map.maps=[];bpfwp_map.info_windows=[];jQuery('.bp-map').each(function(){var id=jQuery(this).attr('id');var data=jQuery(this).data();data.addressURI=encodeURIComponent(data.address.replace(/(<([^>]+)>)/ig,', '));if('undefined'!==typeof data.lat){data.addressURI=encodeURIComponent(data.address.replace(/(<([^>]+)>)/ig,', '));bpfwp_map.map_options=bpfwp_map.map_options||{};bpfwp_map.map_options.center=new google.maps.LatLng(data.lat,data.lon);if(typeof bpfwp_map.map_options.zoom==='undefined'){bpfwp_map.map_options.zoom=bpfwp_map.map_options.zoom||15;}
bpfwp_map.maps[id]=new google.maps.Map(document.getElementById(id),bpfwp_map.map_options);var content='<div class="bp-map-info-window">'+'<p><strong>'+data.name+'</strong></p>'+'<p>'+data.address.replace(/(?:\r\n|\r|\n)/g,'<br>')+'</p>';if('undefined'!==typeof data.phone){content+='<p>'+data.phone+'</p>';}
content+='<p><a target="_blank" href="//maps.google.com/maps?saddr=current+location&daddr='+data.addressURI+'">'+bpfwp_map.strings.getDirections+'</a></p>'+'</div>';bpfwp_map.info_windows[id]=new google.maps.InfoWindow({position:bpfwp_map.map_options.center,content:content});bpfwp_map.info_windows[id].open(bpfwp_map.maps[id]);jQuery(this).trigger('bpfwp.map_initialized',[id,bpfwp_map.maps[id],bpfwp_map.info_windows[id]]);}else if(''!==data.address){var bpMapIframe=document.createElement('iframe');bpMapIframe.frameBorder=0;bpMapIframe.style.width='100%';bpMapIframe.style.height='100%';if(''!==data.name){data.address=data.name+','+data.address;}
bpMapIframe.src='//maps.google.com/maps?output=embed&q='+encodeURIComponent(data.address);bpMapIframe.src='//maps.google.com/maps?output=embed&q='+data.addressURI;jQuery(this).html(bpMapIframe);jQuery(this).trigger('bpfwp.map_initialized_in_iframe',[jQuery(this)]);}});}
function bp_initialize_map(){bpInitializeMap();}
jQuery(document).ready(function(){'use strict';if(!bpfwp_map.autoload_google_maps){return;}
if('undefined'===typeof google||'undefined'===typeof google.maps){var bpMapScript=document.createElement('script');bpMapScript.type='text/javascript';bpMapScript.src='//maps.googleapis.com/maps/api/js?v=3.exp&callback=bp_initialize_map';if('undefined'!==typeof bpfwp_map.google_maps_api_key){bpMapScript.src+='&key='+bpfwp_map.google_maps_api_key;}
document.body.appendChild(bpMapScript);}else{bp_initialize_map();}});