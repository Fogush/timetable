/* Russian (UTF-8) initialisation for the jQuery UI date picker plugin. */
/* Written by Andrew Stromnov (stromnov@gmail.com). */
jQuery(function(jQuery){
	jQuery.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: '&#x3c;Пред',
		nextText: 'След&#x3e;',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
		'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
		'Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		weekHeader: 'Нед',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['ru']);
});

var isMobileDevice = undefined;

jQuery(document).ready( function(){
	
	if ( getURLParameter('force_ajax') == '1' ) {
		isMobileDevice = false;
	};
	
	//Запоминать выбранную группу
	jQuery('#group_id').change( function() {
		setCookie('group_id', jQuery(this).val());
	} );

	//Запоминать выбранную подгруппу
	jQuery('#subgroup').change( function() {
		setCookie('subgroup', jQuery(this).val());
	} );
	
	//Клик по (?) - вывод блока с хэлпом
	jQuery('#show-help').click( function(event) { 
		jQuery('#help-box').toggle(); 
		event.preventDefault(); 
	} );

	jQuery("#first-date")
		.datepicker({	//создать календарь (вернее, datepicker)
			showOtherMonths: true,
			selectOtherMonths: true
		})
		.keydown(function(key) {
			//Если нажимается Enter после ввода первой даты, то 
			//надо отправить форму так, будто нажали на "Указанную дату"
			// - нет смысла вводить первую дату и получать расписание на сегодня
			if ( key.keyCode == 13 ) {
				jQuery('#submit-reqdate').click();
			}
		} );
		
	jQuery("#second-date")
		.datepicker({
			showOtherMonths: true,
			selectOtherMonths: true
		})
		.keydown(function(key) {
			//Аналогично first-date, но уже "Интервал дат" 
			if ( key.keyCode == 13 ) {
				jQuery('#submit-interval').click();
			}
		} );
	
	
	if ( !isMobile() ) {
		
		jQuery('#group_id, #subgroup, #subject_id, #lesson_type_id, #second-date, #first-date').change( function() {
			loadTimetableAjax();
		} );
		
	}
	
	jQuery('#teachers-link').click(function(event) {
		jQuery('#teachers-block').toggleClass('hidden');
		event.preventDefault();
	});
	
} );

function setAction(action) {
	jQuery('#action').val(action);
}

function setCookie( name, value, expires, path, domain, secure ) {
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires ) {
		expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name+'='+escape( value ) +
		( ( expires ) ? ';expires=' + expires_date.toGMTString() : '' ) +
		( ( path ) ? ';path=' + path : '' ) +
		( ( domain ) ? ';domain=' + domain : '' ) +
		( ( secure ) ? ';secure' : '' );
}

function getCookie( name ) {
	var start = document.cookie.indexOf( name + '=' );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
		return null;
	}
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ';', len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
}

function deleteCookie( name, path, domain ) {
	if ( getCookie( name ) ) document.cookie = name + '=' +
			( ( path ) ? ';path=' + path : '') +
			( ( domain ) ? ';domain=' + domain : '' ) +
			';expires=Thu, 01-Jan-1970 00:00:01 GMT';
}

function isMobile() {
	if ( isMobileDevice != undefined ) {
		return isMobileDevice;
	}
	
	isMobileDevice = false;
	
	if ( screen.width < 500 ||
		 navigator.userAgent.match(/Android/i) ||
		 navigator.userAgent.match(/webOS/i) ||
		 navigator.userAgent.match(/iPhone/i) ||
		 navigator.userAgent.match(/iPad/i) ||
		 navigator.userAgent.match(/iPod/i) ||
		 navigator.userAgent.match(/symbian/i) ||
		 navigator.userAgent.match(/windows ce/i) ) {
		
		isMobileDevice = true;
	}

	return isMobileDevice;
}

function loadTimetableAjax() {
	
	jQuery('#ajax-loader').css('display', 'inline-block');
	jQuery('#errors').html('');
	var newUrl = window.location.pathname + '?' + jQuery('#timetable').serialize();
	
	jQuery('#timetable-data').load(newUrl + '&ajax=1', function() {
		jQuery('#ajax-loader').hide();
	});
	
	try {
		history.pushState({}, '', newUrl);
	} catch (e) {}
}

//Author: pauloppenheim from http://stackoverflow.com
function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}
