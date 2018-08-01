var last = '';
$(document).ready(function(){
  $(".col1 li a").on('click',function(){
    var ind = $(this).index('.col1 li a');
    $("#content-"+last).removeClass('activecontent');
    last= ind;
    $("#content-"+ind).addClass('activecontent');
    $(".activelink").stop().animate({
      top: ((ind*35)+20)+"px"
   },200);
    $('.col1 li a.active').removeClass('active');
    $(this).addClass('active');
    $(".col1").height($("#content-"+ind).height());
  });

  if ($("#contact_map").length){
    renderMap(globalAddress);
  }

  $(".col1 li a").first().click();

      $(".carousel").carouselMan({
        itemDom: '> li',
        itemsPerPage: 3,
        pageOffset: 120,
        speed: 700,
        easing: 'easeInOutBack'
      });
});

function renderMap(address){
    
    //geocode global address
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {

      if (status == google.maps.GeocoderStatus.OK) {
        latlng = results[0].geometry.location;
      }else{
        latlng = new google.maps.LatLng(53.547, -113.490);
      }
      var myOptions = {

      zoom: 11,
      center: latlng,
      disableDefaultUI: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP

    };

    map = new google.maps.Map(document.getElementById('contact_map'),myOptions);
    var userimg = new google.maps.MarkerImage('/dev/images/mappin.png',
          new google.maps.Size(81,55),
          new google.maps.Point(0,0),
         new google.maps.Point(40,80) 
    );
    user_marker = new google.maps.Marker({
        position: latlng,
        map: map,
        title:"ZAZZLE",
        icon: userimg,
       optimized: false
    });
  });
}


$(document).ready(function(){
  var itemWidth = 0;  //if 0, it will be calculated on the fly
  var numItems = 0;   
  
});