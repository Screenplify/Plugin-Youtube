
var cycleCount = 0;

$(document).ready(function(){

	startWeather();

});	

function startWeather(){
	if(navigator.onLine){
		getWeather();
		setInterval(getWeather, 3600000); //1 hr. = 3600000 ms
		console.log('Weather Widget started @ 3600000 ms intervals.');
	} else {
		console.log('Weather Plugin: navigator.offline, retrying.')
		setTimeout(function() {
			startWeather();
		}, 5000);
	}

	return false;
}

function getWeather(){

	$.simpleWeather({
		location: weatherData.location,
		woeid: weatherData.woeid,
		unit: weatherData.unit,
		success: function(weather) {
			//console.log(weather);

			$('#weather .location').html(weather.city);
			$('#weather .w-icon').html(getIconElement(weather.code));
			$('#weather .temperature').html(weather.temp+'&deg;'+weather.units.temp);
			$('#weather .currently').html(weather.currently);
			$('#weather #title').html(weather.updated);
			$('#weather .temp-high').html('<i data-icon="[" class="icon"></i> '+weather.high+'&deg;'+weather.units.temp);
			$('#weather .temp-low').html('<i data-icon="?" class="icon"></i> '+weather.low+'&deg;'+weather.units.temp);


			if(weather.forecast.length){
				$('#weather #forecast').html('');

				for (var i = 1; i < 7; i++) { 
					var item = weather.forecast[i];
					var html = '<div class="col-xs-2 text-center"><div class="day-icon">';
						html += getIconElement(item.code);
						html += '</div><div class="day-title">'+item.day+'</div></div>';

					$(html).appendTo('#weather #forecast');
				}
			} else {
				$('#weather #forecast').html('NO forecast data.')
			}

			if(!cycleCount) console.log('Start @:', moment().format('DD-MM-YYYY hh:mm'));
			console.log('Success #:', cycleCount)	
			cycleCount ++;
			//html += weather.code;
			//$("#weather").html(html);
		},
		error: function(error) {
			console.log('Error @:', moment().format('DD-MM-YYYY hh:mm'));
			console.log('Error #:', cycleCount);
			console.log('Error $:', error);
			console.log('Error ####################################');
			//$("#weather").html('<p>'+error+'</p>');
		}
	});

}

//https://developer.yahoo.com/weather/documentation.html
function getIconElement(code){ 
	code = parseInt(code); 
	var result = '';

	switch(code) {
		
		case 3: //severe thunderstorms
			result = '<i data-icon="c" class="icon"></i>';
			break;

		case 4: //thunderstorms
			result = '<i data-icon="w" class="icon"></i>';
			break;

		case 11: //showers
			result = '<i data-icon="b" class="icon"></i>';
			break;
			
		case 12: //showers
			result = '<i data-icon="b" class="icon"></i>';
			break;		

		case 26: //cloudy
		case 44: //partly  cloudy
			result = '<i data-icon="1" class="icon"></i>';
			break;

		case 27: //mostly cloudy (night)
			result = '<i data-icon="2" class="icon"></i>';
			break;	

		case 28: //mostly cloudy (day)
			result = '<i data-icon="3" class="icon"></i>';
			break;

		case 29: //partly  cloudy (night)
			result = '<i data-icon="l" class="icon"></i>';
			break;	

		case 30: //partly  cloudy (day)
			result = '<i data-icon="m" class="icon"></i>';
			break;

		case 31: //clear (night)
		case 33: //fair  (night)
			result = '<i data-icon="+" class="icon"></i>';
			break;

		case 32: //clear (night)
		case 34: //fair  (night)
			result = '<i data-icon="/" class="icon"></i>';
			break;

		case 36: // hot
			result = '<i data-icon="]" class="icon"></i>';
			break;

		case 37: //isolated thunderstorms
		case 38: //scattered thunderstorms
		case 39: //scattered thunderstorms
			result = '<i data-icon="G" class="icon"></i>';
			break;

		case 45: //thundershowers
		case 47: //isolated thundershowers
			result = '<i data-icon="c" class="icon"></i>';
			break;

		default:
			console.log('return default for:', code)
			result = '<i data-icon="a" class="icon"></i>';

	}

	return result;
}