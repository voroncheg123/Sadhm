(function() {

	var jQuery;

	if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.4.2') {
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type", "text/javascript");
    script_tag.setAttribute("src", "http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js");
    if (script_tag.readyState) {
      script_tag.onreadystatechange = function () { // For old versions of IE
      	if (this.readyState == 'complete' || this.readyState == 'loaded') {
      		scriptLoadHandler();
      	}
      };
    } else { // Other browsers
      script_tag.onload = scriptLoadHandler;
    }
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
  } else {
  	jQuery = window.jQuery;
  	main();
  }
	
	function scriptLoadHandler() {
		jQuery = window.jQuery.noConflict(true);
		main();
	}
	
	function main() {
		jQuery(document).ready(function($) {
			
			var city = 'Тюмень';
			if (!!$('#moonwidget-info').attr('data-city')) city = $('#moonwidget-info').attr('data-city');
			if (city != '') city = '&mfc=' + city;
        
      $.getJSON('http://satyoga.ru/wp-admin/admin-ajax.php?action=jsmoonwidget_getinfo' + city + '&callback=?', function(data) {
      	var html = '';
      	html = '<div class="moonwidget-info-datetime"><span class="moonwidget-info-date">' + data.date + '</span> <span class="moonwidget-info-time">' + data.time + '</span></div>' + 
      		'<div class="moonwidget-info-moonday"><span class="moonwidget-info-moonday-label">' + data.moonday + '</span>-й лунный день <span class="moonwidget-info-moondayfrom">' + data.moondayfrom + '</span></div>' +
      		'<div class="moonwidget-info-moonimage"><img src="' + data.moonimagesrc + '" width="' + data.moonimagewidth + '" height="' + data.moonimageheight + '"></div>' +
      		'<div class="moonwidget-info-moonphase"><span class="moonwidget-info-label">Фаза луны:</span> ' + data.moonphase + 
      		'<div class="moonwidget-info-moonsign"><span class="moonwidget-info-label">Луна в знаке:</span> ' + data.moonsign  + '</span></div>';
      		;
        $('#moonwidget-info').html(html);
      });
		});
	}

})();
