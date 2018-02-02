<?php
 /*
 * datetime.php - Custom functions related to date and time formatting
 *
 * Note: Moved this outside functions.php to help alleviate development conflicts editing large functions.php file
 *
 */

$timezones = array(
	__('PST', 'tcn'),
	__('MST', 'tcn'),
	__('CST', 'tcn'),
	__('EDT', 'tcn'),
	__('AST', 'tcn'),
	__('NDT', 'tcn')
);
	
function get_timezones()
{
	global $timezones;
	return $timezones;
}


function get_event_date_iso($event) 
{
	$isoDate = get_post_meta($event->ID, '_EventStartDate', true);
	if(!empty($isoDate)) {
		// Use PHP timezones instead of translations, this could break if translation array index changes
		$zones = array(
			0 => 'America/Vancouver',   //__('PST', 'tcn'),
			1 => 'America/Regina',      //__('MST', 'tcn'),
			2 => 'America/Winnipeg',    //__('CST', 'tcn'),
			3 => 'America/Montreal',    //__('EST', 'tcn'),
			4 => 'America/Halifax',     //__('AST', 'tcn'),
			5 => 'America/St_Johns',    //__('NDT', 'tcn')
		);
		$tz = get_post_meta($event->ID, 'event_timezone', true);
		$timezone = !empty($tz) && isset($zones[$tz]) ? $zones[$tz] : $zones[0];
		$date = new DateTime($isoDate, new DateTimeZone($timezone));
		$isoDate = $date->format(DateTime::ISO8601);
	}
	
	return $isoDate;
}

function translate_months($date, $lang = 'en')
{
	if($lang == 'en')
	{
		return $date;
	}
	
	$months = array(
		"January" => "janvier",
		"February" => "février",
		"March" => "mars",
		"April" => "avril",
		"May" => "mai",
		"June" => "juin",
		"July" => "juillet",
		"August" => "août",
		"September" => "septembre",
		"October" => "octobre",
		"November" => "novembre",
		"December" => "décembre"
	);
	
	$timezones = array(
		'PST' => 'HNP',
		'MST' =>  'MST',
		'CST' =>  'CST',
		'EDT' =>  'HNE',
		'AST' =>  'AST',
		'NDT' =>  'NDT');

	foreach($months as $en => $fr)
	{
		$date = str_replace($en, $fr, $date);
	}
	
	foreach($timezones as $en => $fr)
	{
		$date = str_replace($en, $fr, $date);
	}
	
	return $date;
	
}

function get_event_date_fr($event)
{   
	if(is_event_over($event->ID))
	{
		$date = get_post_meta($event->ID, '_EventStartDate', true);
		return translate_months(date_i18n( "j F Y", strtotime($date)), 'fr');
	}
	else
	{
		$tz = get_post_meta($event->ID, 'event_timezone', true);
		if($tz == '')
			$tz = 0;
		// return tribe_events_event_schedule_details($event = $event, $before = '', $after = '', $lang='fr') . ' ('. get_timezones()[$tz] . ')';
		return translate_months(tribe_events_event_schedule_details($event = $event, $before = '', $after = '', $lang='fr') . ' ('. get_timezones()[$tz] . ')', 'fr');
	}    
}

function get_event_date($event)
{
	if(is_event_over($event->ID))
	{
		if(ICL_LANGUAGE_CODE == 'en')
		{
			$date = get_post_meta($event->ID, '_EventStartDate', true);
			return date("F j, Y", strtotime($date));
		}
		else
		{
			$date = get_post_meta($event->ID, '_EventStartDate', true);
			return translate_months(date_i18n( "j F Y", strtotime($date)),'fr');
		}
	}
	else
	{
		$tz = get_post_meta($event->ID, 'event_timezone', true);
		if($tz == '')
			$tz = 0;
		if(ICL_LANGUAGE_CODE == 'en')
			return tribe_events_event_schedule_details($event) . ' ('. get_timezones()[$tz] . ')';
		else
			return translate_months(tribe_events_event_schedule_details($event) . ' ('. get_timezones()[$tz] . ')', 'fr');
	}
}