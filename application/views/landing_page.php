<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <!-- Site Properties -->
  <title>Barriér</title>
  <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo.png'); ?>"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.js"></script>
  <!-- CSS file location -->
  <link href="<?php echo base_url('assets/css/header_footer.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/landing_page.css'); ?>" rel="stylesheet" type="text/css">
</head>

<body>
  <!-- header -->
  <?php include('header.php'); ?>
  <div class="main container">
    <div id="click_barrier_modal" class="ui small modal">
      <div id="temp_barrier_id"></div>
      <div class="header">
        <p id="click_barrier_id_title"></p>
      </div>
      <div class="content">
        <p id="click_barrier_id"></p>
        <div class="ui two column stackable grid container">
          <div class="column">
            <div id="click_barrier_status"></div>
          </div>
          <div class="column">
            <h5 class="ui header"><i class="orange marker icon"></i>Specific Coordinates</h3>
            <p id="click_barrier_coord"></p>
          </div>
        </div>
      </div>
      <div class="actions">
        <div class="ui two column grid container">
          <div class="column">
            <div class="ui fluid orange button" id="update_barrier_button">
              <i class="spinner icon"></i>
              Update Barrier
              <div id="update_barrier_modal" class="ui small modal">
                <div class="header">
                  <p id="update_barrier_id"></p>
                </div>
                <div class="content">
                  <p>Are you sure you want to update this barrier?</p>
                </div>
                <div class="actions">
                  <div class="ui negative button">
                    Cancel
                  </div>
                  <div id="update_barrier" type="submit" class="ui positive button">
                    Update
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column">
            <div class="ui fluid negative button" id="delete_barrier_button">
              <i class="remove icon"></i>
              Delete Barrier
              <div id="delete_barrier_modal" class="ui small modal">
                <div class="header">
                  <p id="delete_barrier_id"></p>
                </div>
                <div class="content">
                  <p>Are you sure you want to delete this barrier?</p>
                </div>
                <div class="actions">
                  <div class="ui negative button">
                    Cancel
                  </div>
                  <div id="delete_barrier" type="submit" class="ui positive button">
                    Delete
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="map"></div>
  </div>

  <!-- footer -->
  <div class="ui fluid orange center aligned inverted vertical fixed bottom sticky footer segment" id="footer">
  CS 145 Team 1 <i class="copyright icon"></i>2017<br>
  </div>
</body>



<script>
  //=============================================================================
  //=================================map.js======================================
  //=============================================================================
  var map, currLocation, mark, currMarker, gmarkers=[], flag=0;
  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
     center: {lat: 14.5995, lng: 120.9842},
     zoom: 12,
     minZoom: 6,
     styles: [{
        "featureType": "landscape.natural",
        "elementType": "geometry.fill",
        "stylers": [
            {"visibility": "on"},
            {"color": "#e0efef"}
        ]},
    {
        "featureType": "poi",
        "elementType": "geometry.fill",
        "stylers": [
            {"visibility": "on"},
            {"hue": "#1900ff"},
            {"color": "#c0e8e8"}
        ]},
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
            {"lightness": 100},
            {"visibility": "simplified"}
        ]},
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {"visibility": "simplified"}
        ]},
    {
        "featureType": "transit.line",
        "elementType": "geometry",
        "stylers": [
            {"visibility": "on"},
            {"lightness": 700}
        ]},
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {"color": "#7dcdcd"}
        ]}
    ]

    });

    // Multiple Markers
    var markers = [
      <?php foreach ($markers as $marker):?>
        <?php echo "['".$marker['barrier_id']."', ".$marker['barrier_latitude'].", ".$marker['barrier_longitude'].", ".$marker['barrier_status']."], \n";
        ?>
      <?php endforeach; ?>
    ];
    // Loop through our array of markers & place each one on the map
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        marker = new google.maps.Marker({
          position: position,
          map: map,
          animation: google.maps.Animation.DROP,
          title: markers[i][0],
          barrier_latitude: markers[i][1],
          barrier_longitude: markers[i][2]
        });
        marker.addListener('click', function() {
          document.getElementById("temp_barrier_id").value = this.title;
          document.getElementById("update_barrier_id").innerHTML = "<i class=\"spinner icon\"></i>Update " + this.title;
          document.getElementById("click_barrier_id_title").innerHTML = "<img class=\"small_logo\" src=\"http://svgshare.com/i/1Y6.svg\">&nbsp;&nbsp;&nbsp;Barrier " + this.title;
          // document.getElementById("click_barrier_id").innerHTML = "What do you want to do with " + this.title + "?";
          document.getElementById("click_barrier_coord").innerHTML = "<b>Latitude: </b>" + this.barrier_latitude + "<br><b>Longitude: </b>" + this.barrier_longitude;
          document.getElementById("delete_barrier_id").innerHTML = "<i class=\"remove icon\"></i>Delete " + this.title + "?";
          $('#click_barrier_modal').modal('show');
        });
        if (markers[i][3] == 1) {
          marker.setIcon('<?php echo base_url('assets/images/orange.svg'); ?>'); // orange
          marker.addListener('click', function() {
            document.getElementById("click_barrier_status").innerHTML = "<div class=\"ui positive message\"><div class=\"header\">The barrier is in its proper place!</div><p>No further action has to be done.</p></div>";
          });
        }
        else {
          marker.setIcon('<?php echo base_url('assets/images/red.svg'); ?>'); // red
          marker.setAnimation(google.maps.Animation.BOUNCE);
          marker.addListener('click', function() {
            document.getElementById("click_barrier_status").innerHTML = "<div class=\"ui negative message\"><div class=\"header\">The barrier has been moved!</div><p>When returned to its proper place, please update the barrier.</p></div>";
          });
          flag = 1;
        }
      gmarkers.push(marker);
    }

    var current = new google.maps.Marker({
      map: map,
      icon: '<?php echo base_url('assets/images/current.jpg'); ?>'
    });
    addYourLocationButton(map, current); // for clicking current location
    google.maps.event.addListener(map, 'click', function(event) {
      placeMarker(event.latLng);
    }); // for adding a marker

    //var markerCluster = new MarkerClusterer(map, gmarkers,
    //  {imagePath: '<?php echo base_url('assets/images/m'); ?>'});

    if (flag == 0) {
      $('#warning').hide();
    }
    else {
      var code = "";
      <?php foreach ($markers as $marker):?>
        <?php if ($marker['barrier_status'] == 0):?>    
          code = "<tr><td><?php echo $marker['barrier_id'] ?></td><td><?php echo $marker['barrier_latitude'] ?></td><td><?php echo $marker['barrier_longitude'] ?></td></tr>"
          $('#moved_markers').append(code);
        <?php endif?>
      <?php endforeach; ?>
    }

  }

  function addYourLocationButton(map, marker) {
    var controlDiv = document.createElement('div');

    var firstChild = document.createElement('button');
    firstChild.style.backgroundColor = '#fff';
    firstChild.style.border = 'none';
    firstChild.style.outline = 'none';
    firstChild.style.width = '28px';
    firstChild.style.height = '28px';
    firstChild.style.borderRadius = '2px';
    firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
    firstChild.style.cursor = 'pointer';
    firstChild.style.marginRight = '10px';
    firstChild.style.padding = '0px';
    firstChild.title = 'Your Location';
    controlDiv.appendChild(firstChild);

    var secondChild = document.createElement('div');
    secondChild.style.margin = '5px';
    secondChild.style.width = '18px';
    secondChild.style.height = '18px';
    secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
    secondChild.style.backgroundSize = '180px 18px';
    secondChild.style.backgroundPosition = '0px 0px';
    secondChild.style.backgroundRepeat = 'no-repeat';
    secondChild.id = 'you_location_img';
    firstChild.appendChild(secondChild);

    // you_location_img is the icon above street view don't remove any of that code, note to self
    google.maps.event.addListener(map, 'dragend', function() {
    	$('#you_location_img').css('background-position', '0px 0px');
    });

    firstChild.addEventListener('click', function() {
    	var imgX = '0';
    	var animationInterval = setInterval(function(){
    		if(imgX == '-18') imgX = '0';
    		else imgX = '-18';
    		$('#you_location_img').css('background-position', imgX+'px 0px');
    	}, 500);
    	if(navigator.geolocation) {
    		navigator.geolocation.getCurrentPosition(function(position) {
    			var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
          currLocation = latlng; // update global variable
    			marker.setPosition(latlng);
    			map.setCenter(latlng);
          map.setZoom(17);
    			clearInterval(animationInterval);
    			$('#you_location_img').css('background-position', '-144px 0px');
          var circle = new google.maps.Circle({
            center: latlng,
            radius: position.coords.accuracy,
            map: map,
            fillColor: '#badcef',
            fillOpacity: 0.5,
            strokeColor: '#badcef',
            strokeOpacity: 0.5,
            radius: 300,
            clickable: false
          });
    		});
    	}
    	else {
    		clearInterval(animationInterval);
    		$('#you_location_img').css('background-position', '0px 0px');
    	}
    });
    controlDiv.index = 1;
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
  }

  // place a single marker and update current marker location
  function placeMarker(location) {
    if (mark) {
      mark.setPosition(location);
    }
    else {
      mark = new google.maps.Marker({
        position: location,
        map: map,
        icon: '<?php echo base_url('assets/images/blue.svg'); ?>'
      });
    }
    currMarker = location;
  }


  //=============================================================================
  //============================header.js========================================
  //=============================================================================

  var modal_is_open = 0;

  $('#add_menu').on('click',function(){
    $('#add_barrier_modal').modal('setting', { detachable:false }).modal('show').addClass('scrolling active');
    $('body').addClass('scrolling');
     modal_is_open = 1;
  });

  $('#misplaced_barriers_menu').on('click',function(){
    $('#misplaced_barriers_modal').modal('show');
     modal_is_open = 1;
  });

  $('#update_barrier_button').on('click',function(){
    $('#update_barrier_modal').modal('show');
     modal_is_open = 1;
  });

  $('#delete_barrier_button').on('click',function(){
    $('#delete_barrier_modal').modal('show');
    modal_is_open = 1;
  });

  // get current location or marker and set those values in add form
  var lat = document.getElementById("latitude");
  var lng = document.getElementById("longitude");
  $('#curr').on('click', function() {
    lat.value = currLocation.lat();
    lng.value = currLocation.lng();
  });
  $('#added.button').on('click', function() {
    lat.value = currMarker.lat();
    lng.value = currMarker.lng();
  });

  // cancel button for edit details
  $('.cancel.button').on('click', function() {
    $('.ui.form')[0].reset(); //edit details form
    $('.ui.form .ui.dropdown').dropdown('restore defaults');
  });

  // reset button for the forms
  $('.reset.button').on('click', function() {
    $('.ui.form')[0].reset(); //edit details form
  });

  // function that reloads the markers to reflect the current data in the database
  function asyncUpdateMarkers() {
    $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>/Barrier/async_update_marker",
      data:{ data: 'data'},
      dataType: 'json',
      success:function(data){
        var flag = 0;
        for (var i = 0; i < gmarkers.length; i++) {
          gmarkers[i].setMap(null);
        }
        gmarkers = [];
        for( i = 0; i < data.length; i++ ) {
            var position = new google.maps.LatLng(data[i].barrier_latitude, data[i].barrier_longitude);
            marker = new google.maps.Marker({
              position: position,
              map: map,
              animation: google.maps.Animation.DROP,
              title: data[i].barrier_id,
              barrier_latitude: data[i].barrier_latitude,
              barrier_longitude: data[i].barrier_longitude
            });
            marker.addListener('click', function() {
              document.getElementById("temp_barrier_id").value = this.title;
              document.getElementById("update_barrier_id").innerHTML = "<i class=\"spinner icon\"></i>Update " + this.title;
              document.getElementById("click_barrier_id_title").innerHTML = "<img class=\"small_logo\" src=\"http://svgshare.com/i/1Y6.svg\">&nbsp;&nbsp;&nbsp;Barrier " + this.title;
              // document.getElementById("click_barrier_id").innerHTML = "What do you want to do with " + this.title + "?";
              document.getElementById("click_barrier_coord").innerHTML = "<b>Latitude: </b>" + this.barrier_latitude + "<br><b>Longitude: </b>" + this.barrier_longitude;
              document.getElementById("delete_barrier_id").innerHTML = "<i class=\"remove icon\"></i>Delete " + this.title + "?";
              $('#click_barrier_modal').modal('show');
            });
            if (data[i].barrier_status == 1) {
              marker.setIcon('<?php echo base_url('assets/images/orange.svg'); ?>'); // orange
              marker.addListener('click', function() {
                document.getElementById("click_barrier_status").innerHTML = "<div class=\"ui positive message\"><div class=\"header\">The barrier is in its proper place!</div><p>No further action has to be done.</p></div>";
              });
            }
            else {
              marker.setIcon('<?php echo base_url('assets/images/red.svg'); ?>'); // red
              marker.setAnimation(google.maps.Animation.BOUNCE);
              marker.addListener('click', function() {
                document.getElementById("click_barrier_status").innerHTML = "<div class=\"ui negative message\"><div class=\"header\">The barrier has been moved!</div><p>When returned to its proper place, please update the barrier.</p></div>";
              });
              flag = 1;
            }
          gmarkers.push(marker);
        }
        if (flag == 0) {
          $('#warning').hide();
        }
        else {
          $('#warning').show();
          var code = "";
          console.log("pwet");
          $('#moved_markers').empty();
          for (i = 0; i < gmarkers.length; i++) {
            if (data[i].barrier_status == 0) {     
              code = "<tr><td>" + data[i].barrier_id + "</td><td>" + data[i].barrier_latitude + "</td><td>" + data[i].barrier_longitude + "</td></tr>"
              $('#moved_markers').append(code);
            }
          }
        }
      },
    });
  }

  // ajax POST function to store marker to database
  $(function(){
    $("#save").click(function(event){
      event.preventDefault();//prevent auto submit data
      var ajax_barrier_id= $("#barrier_id").val();
      var ajax_barrier_key= $("#barrier_key").val();
      var ajax_latitude = $("#latitude").val();
      var ajax_longitude = $("#longitude").val();
      if(!isNaN(ajax_latitude) && !isNaN(ajax_longitude) && ajax_barrier_id && ajax_barrier_key && ajax_latitude && ajax_longitude){ //validate if input latitude and longitude are numbers
        $.ajax({
          type:"post",
          url: "<?php echo base_url(); ?>/Barrier/add_marker",
          data:{
            ajax_barrier_id:ajax_barrier_id,
            ajax_barrier_key:ajax_barrier_key,
            ajax_latitude:ajax_latitude,
            ajax_longitude:ajax_longitude,
          },
          success:function(data){
            $('#add_barrier_modal').modal("hide");
             modal_is_open = 0;
            asyncUpdateMarkers();
          },
        });
      }
    });
  });

  //update barrier
  $(function(){
    $("#update_barrier").click(function(event){
      event.preventDefault();//prevent auto submit data
      var ajax_barrier_id= $("#temp_barrier_id").val();
      $.ajax({
        type:"post",
        url: "<?php echo base_url(); ?>/Barrier/update_marker",
        data:{
          ajax_barrier_id:ajax_barrier_id
        },
        success:function(data){
          // $('#click_barrier_status').modal("hide");
          $('#update_barrier_modal').modal("hide");
           modal_is_open = 0;
          asyncUpdateMarkers();
        },
      });
    });
  });

  //delete barrier
  $(function(){
    $("#delete_barrier").click(function(event){
      event.preventDefault();//prevent auto submit data
      var ajax_barrier_id= $("#temp_barrier_id").val();
      $.ajax({
        type:"post",
        url: "<?php echo base_url(); ?>/Barrier/delete_marker",
        data:{
          ajax_barrier_id:ajax_barrier_id
        },
        success:function(data){
          // $('#click_barrier_status').modal("hide");
          $('#delete_barrier_modal').modal("hide");
           modal_is_open = 0;
          asyncUpdateMarkers();
        },
      });
    });
  });

  // form validation
  $('.ui.form').form({
    inline: true,
    on: "change",
    fields: {
      barrier_id: {
        rules: [
          {
            type   : 'empty'
          }
        ]},
      barrier_key: {
        rules: [
          {
            type   : 'empty'
          }
        ]},
      latitude: {
        rules: [
          {
            type   : 'empty'
          },
          {
            type   : 'number'
          }
        ]},
      longitude: {
        rules: [
          {
            type   : 'empty'
          },
          {
            type   : 'number'
          }
        ]}
    }
  });

  setInterval(function() {
    if(modal_is_open == 0) {
      asyncUpdateMarkers();
    }
  }, 10000);
</script>

<!-- <script src="<?php echo base_url('assets/js/marker_cluster.js'); ?>"></script> -->

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5VGrg2pYcLveD8MFh7oXZdIQBYLqr4-Y&callback=initMap">
</script>

</html>
