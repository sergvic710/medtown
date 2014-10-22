{* javascript template *}
{if $mapinstance == 1}
<script type="text/javascript" charset="utf-8" {$map_defertxt}>{literal}//<![CDATA[
if( typeof(jQuery) == 'undefined' ){
  alert('JQuery is required for this map to function');
}
{/literal}{if $map->sensor}{literal}
var my_location;
jQuery(document).ready(function(){
// a function to determine the current browser location.
// this function is asynchronous in nature.
function getGeoLocation()
{
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position){
      // Try W3C Geolocation (Preferred)
      var tmp = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
      my_location = tmp.toString();
    });
  } else if (google.gears) {
    // Try Google Gears Geolocation
    var geo = google.gears.factory.create('beta.geolocation');
    geo.getCurrentPosition(function(position) {
      var tmp = new google.maps.LatLng(position.latitude,position.longitude);
      my_location = tmp.toString();
    });
  }
}
getGeoLocation();
});{/literal}{/if}
//]]></script>{/if}

{jsmin}<script type="text/javascript" charset="utf-8" {$map_defertxt}>{literal}
jQuery(document).ready(function(){{/literal}
{if $generator->get_marker_count()}{if $map->info_window}{literal}
function handle_info_window(marker)
{
  // setup the text for the info window
  var tmp = jQuery('#cggm_infowindow_'+marker.map_instance);
  jQuery(tmp).empty();
  jQuery('.cggm_infowindow',marker.jq).clone().appendTo(tmp);
  tmp.find('form').append('<input type="hidden" name="map_instance" value="'+marker.map_instance+'"/><input type="hidden" name="marker_alias" value="'+marker.alias+'"/>');
  tmp.find('.cggm_infowindow').attr('id','cggm_infowindow_'+marker.map_instance);
  infoWindow.setContent(tmp.html());
  infoWindow.open(mapObj,marker);

  // handle clicking on 'to here'
  jQuery('div#cggm_infowindow_'+marker.map_instance+' a.cggm_dir_to').click(function() {
    var tmp = jQuery(this).closest('div.cggm_infowindow').parent();
    var map_instance = tmp.find(":input[name='map_instance']").val();
    var marker_alias = tmp.find(":input[name='marker_alias']").val();
    var mkr = jQuery('#map_'+map_instance+'_marker_'+marker_alias).data('marker');
    jQuery(this).parent().find('a').show();
    jQuery(this).hide();
    tmp.find("span").show();
    var addr = mkr.getPosition().toString();
    tmp.find(":input[name='daddr']").val(addr);
    tmp.find('span.cggm_dirto').hide();
    tmp.find(":input[name='saddr']").val(my_location);
    tmp.find('form').show();
  });

  // handle clicking on from here.
  jQuery('div#cggm_infowindow_'+marker.map_instance+' a.cggm_dir_from').click(function() {
    var tmp = jQuery(this).closest('div.cggm_infowindow').parent();
    var map_instance = tmp.find(":input[name='map_instance']").val();
    var marker_alias = tmp.find(":input[name='marker_alias']").val();
    var mkr = jQuery('#map_'+map_instance+'_marker_'+marker_alias).data('marker');
    jQuery(this).parent().find('a').show();
    jQuery(this).hide();
    tmp.find("span").show();
    var addr = mkr.getPosition().toString();
    tmp.find(":input[name='saddr']").val(addr);
    tmp.find("span.cggm_dirfrom").hide();
    tmp.find(":input[name='daddr']").val(my_location);
    tmp.find('form').show();
  });

  {/literal}
  {if $map->directions_dest == 'PANEL'}
  {literal}
  // handle the submit button in the directions form.
  jQuery('div#cggm_infowindow_'+marker.map_instance+' form.cggm_directions_form').submit(function() {
    var tmp = jQuery(this).closest('div.cggm_infowindow').parent();
    var src = jQuery(":input[name='saddr']",tmp).val();
    var dest = jQuery(":input[name='daddr']",tmp).val();
    var req = {
       origin: src,
       destination: dest,
       travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(req, function(response,status) {
      if( status == google.maps.DirectionsStatus.OK ) {
        directionsDisplay.setMap(mapObj);
        directionsDisplay.setPanel(document.getElementById('map_directions_'+mapInstance));
        directionsDisplay.setDirections(response);
        jQuery('#map_directions_'+mapInstance).show();
      }
    });
    return false;
  });
  {/literal}
  {/if}
  {literal}
}
{/literal}
{/if}
{/if}

// setup some variables and do initial testing.
var mapInstance = '{$mapinstance}';
var mapMainDiv = '#cggm_map_defn_'+mapInstance;
var mapElem = jQuery('#cggm_map_display_{$mapinstance}').get(0);
{literal}
if( mapElem == 'undefined' || mapElem == null ) {
  alert('{/literal}{$msg_mapelem_notfound}{literal}');
  return;
}{/literal}

{if $map->category_panel}
{if isset($categories)}
var category_info = new Array();
{foreach from=$map_categories item=cat}
category_info['{$cat.name}'] = '{$cat.icon}';
{/foreach}
{/if}
{literal}
// handle category form click
jQuery('#cggm_map_defn_'+mapInstance+' form.category_form :checkbox.category_checkbox').click(function(){
  // hide the infowindow
  infoWindow.close();
  // for each checkbox
  var cats = new Array();
  jQuery('#cggm_map_defn_'+mapInstance+' form.category_form :checkbox.category_checkbox:checked').each(function(){
    // add it to the list.
    cats.push(jQuery(this).attr('name'));
  });

  // for each marker
  jQuery('#cggm_map_defn_'+mapInstance+' div.cggm_map_markers > div.cggm_marker').each(function(){
    // get the categories
    var mname = jQuery(":input[name='name']",this).val();
    var tmp = jQuery(":input[name='categories']",this).val().split(',');
    var meta = jQuery(":input[name='meta']",this).val();
    // check for visible categories
    var display = false;
    var firstcat = '';
    for( i = 0; i < cats.length; i++ )
    {
       if( jQuery.inArray(cats[i],tmp) != -1)
       {
          display = true;
          firstcat = cats[i];
          break;
       }
    }
    // toggle the marker
    var mkr = jQuery(this).data('marker');
    mkr.setVisible(display);
    if( !meta )
    {
      var ticon = category_info[firstcat];
      mkr.setIcon(icons[ticon]);
    }

    // for each marker info window item in this marker
    jQuery('.cggm_infowindow_item',this).each(function(){
       var tmp = jQuery(":hidden[name='categories']",this).val();
       if( typeof tmp != 'undefined' ) {
         tmp = tmp.split(',');
         display = false;
         for( i = 0; i < cats.length; i++ )
         {
            if( jQuery.inArray(cats[i],tmp) != -1)
            {
              display = true;
              break;
            }
         }
         if( display )
         {
            jQuery(this).show();
         }
         else
         {
            jQuery(this).hide();
         }
       }
    });
  });

  // foreach sidebar item
  jQuery('#cggm_map_defn_'+mapInstance+' li.cggm_sidebar_item').each(function(){
    // toggle the sidebar item
    var tmp = jQuery(":hidden[name='categories']",this).val().split(',');
    var display = false;
    for( i = 0; i < cats.length; i++ )
    {
       if( jQuery.inArray(cats[i],tmp) != -1)
       {
          display = true;
          break;
       }
    }
    if( display )
    {
       jQuery(this).show();
    }
    else
    {
       jQuery(this).hide();
    }
  });
});
{/literal}
{/if}

// build the map itself.
var map_options = {ldelim}
mapTypeId: google.maps.MapTypeId.{$generator->get_google_maptype()},
{if $map->nav_controls}
navigationControl: true,
navigationControlOptions: {ldelim}style: google.maps.NavigationControlStyle.{$map->nav_control_option}{rdelim},
{else}
navigationControl: false,
{/if}
{if $map->scale_controls}
scaleControl: true,
{else}
scaleControl: false,
{/if}
{if $map->sv_controls}
streetViewControl: true,
{else}
streetViewControl: false,
{/if}
{if $map->type_controls}
mapTypeControl: true,
mapTypeControlOptions: {ldelim}style: google.maps.MapTypeControlStyle.{$map->type_control_option}{rdelim},
{else}
mapTypeControl: false,
{/if}
{if $map->center_lat != '' && $map->center_lon != ''}
// center latitude and longitude set for the map
center: new google.maps.LatLng({$map->center_lat},{$map->center_lon}),
{else}
// center latitude and longitude calculated by markers.
center: new google.maps.LatLng({$generator->get_center_lat()},{$generator->get_center_lon()}),
{/if}
zoom: {$map->zoom}
{rdelim}; // map_options

var mapObj = new google.maps.Map(mapElem,map_options);
var infoWindow = new google.maps.InfoWindow();
{if $map->zoom_encompass && $generator->get_marker_count() > 1}
  // set zoom encompass
  var minll = new google.maps.LatLng({$generator->get_min_lat()},{$generator->get_min_lon()});
  var maxll = new google.maps.LatLng({$generator->get_max_lat()},{$generator->get_max_lon()});
  var bds = new google.maps.LatLngBounds(minll,maxll);
  mapObj.fitBounds(bds);
{/if}
{if $map->directions && $generator->get_marker_count()}
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();
{/if}

{if $map->sv_controls}
{* build the streetview panorama object *}
panorama = new  google.maps.StreetViewPanorama(document.getElementById('cggm_sv_display_'+mapInstance));
mapObj.setStreetView(panorama);
{/if}
{if isset($icons)}
// build the icons.
var icons = new Array();
{foreach from=$icons item='icon'}
icons['{$icon.name}'] = new google.maps.MarkerImage('{$icon.url}');
{/foreach}{/if}
{if $generator->get_marker_count()}{literal}
// build the markers
jQuery('#cggm_map_defn_'+mapInstance+' div.cggm_map_markers > div.cggm_marker').each(function(){
  // create the marker object.
  var name = jQuery(":input[name='name']",this).val();
  var lat = jQuery(":input[name='latitude']",this).val();
  var lon = jQuery(":input[name='longitude']",this).val();
  var icon = jQuery(":input[name='icon']",this).val();
  var t_icon = null;
  if( icons != undefined && icon != undefined ) {
    t_icon = icons[icon];
  }
  var title = jQuery(":input[name='title']",this).val();
  var marker = new google.maps.Marker({
    map: mapObj,
    title: title,
    map_instance: mapInstance,
    alias: name,
    icon: t_icon,
    jq: this,
    position: new google.maps.LatLng(lat,lon)
  });
  // store the marker for later retrieval.
  jQuery(this).data('marker',marker);

  {/literal}
  {if $map->info_window && $map->info_trigger != 'none'}
  // add info window triggers.
  google.maps.event.addListener(marker, '{$map->info_trigger}', function() {ldelim}
    handle_info_window(marker);
    {if $map->sv_controls}
    var tmp =mapObj.getStreetView();
    var client = new google.maps.StreetViewService();
    client.getPanoramaByLocation(marker.getPosition(), {$map->sv_radius|default:1000}, function(result, status) {ldelim}
    if (status == google.maps.StreetViewStatus.OK) {ldelim}
      document.getElementById('cggm_sv_display_'+mapInstance).style.display='block';
      tmp.setPosition(result.location.latLng);
    {rdelim}
    {rdelim}); 
    {/if}
  {rdelim});
  {/if}
  {literal}
}); // each
{/literal}
{/if}

{if isset($kml_files) && count($kml_files) }
{* this kml_files variable is temporary *}
{foreach from=$kml_files item='one_file'}
var kml_layers = new Array();
var kml = new google.maps.KmlLayer('{$one_file}');
kml.setMap(mapObj);
kml_layers.push(kml);
{/foreach}
{/if}

{if $map->sidebar}
{literal}
// handle clicking on sidebar
jQuery('a.cggm_sidebar_title_link').click(function(){
  var tmp = jQuery(this).attr('rel').split('::');
  if( tmp.length == 2 )
  {
    var mkr = jQuery('#map_'+tmp[0]+'_marker_'+tmp[1]).data('marker');
    handle_info_window(mkr);
    {/literal}{if $map->sv_controls}
    mapObj.getStreetView().setPosition(mkr.getPosition());
    {/if}{literal}
  }
});
{/literal}
{/if}

{if $map->directions && $generator->get_marker_count()}
{literal}
// handle disabling any directions
jQuery(":button[name='hide_directions']").click(function(){
  directionsDisplay.setMap(null);
  jQuery(this).parent().hide();
});
{/literal}
{/if}

{literal}
}); // ready function.
//]]>{/literal}
</script>{/jsmin}