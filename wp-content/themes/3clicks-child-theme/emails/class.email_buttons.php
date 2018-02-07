<?php

	class Three_Clicks_Child_Theme_Email_Buttons extends TribeiCal{
		
		public static function my_single_event_links($postId) {
			if(class_exists('TribeiCal'))
			{
			$output    = googleCalendarLink( $postId );
			// don't show on password protected posts
			if ( is_single() && post_password_required() ) {
				return;
			}
			
			$str = '';
	
			$str.= '<div class="tribe-events-cal-links">'."\r\n";
			$str.= '<a href="' . googleCalendarLink($postId) . '" title="' . __( 'Add to Google Calendar', 'tribe-events-calendar' ) . '" style="color: #fff; background-color: #29abe2; border-radius: 30px; display: inline-block; padding: 10px; font-size: 14px; font-weight: bold; text-decoration:none;font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;" mc:disable-tracking>+ ' . __( 'Google Calendar', 'tribe-events-calendar' ) . '</a>&nbsp;'."\r\n";
			$str.= '<a href="' . tribe_get_single_ical_link() . '" title="' . __( 'Download .ics file', 'tribe-events-calendar' ) . '" style="color: #fff; background-color: #29abe2; border-radius: 30px; display: inline-block; padding: 10px; font-size: 14px; font-weight: bold; text-decoration:none;font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;" >+ ' . __( 'iCal Export', 'tribe-events-calendar' ) . '</a>'."\r\n";
			$str.= '</div><!-- .tribe-events-cal-links -->'."\r\n";
			
			echo $str;
			}
		}

	}

function googleCalendarLink( $postId = null ) {
			global $post;
			$post = get_post( $postId );
			$my_post = get_post( $postId );
			
			//$event_details = get_the_content();
			$event_details = get_post_field('post_content', $postId, 'raw');

			//Truncate Event Description and add permalink if greater than 996 characters
			if ( strlen( $event_details ) > 996 ) {
				//Strip tags
				$event_details = strip_tags( $event_details );

				$event_url     = get_permalink();
				$event_details = substr( $event_details, 0, 996 );

				//Only add the permalink if it's shorter than 900 characters, so we don't exceed the browser's URL limits
				if ( strlen( $event_url ) < 900 ) {
					$event_details .= ' (View Full Event Description Here: ' . $event_url . ')';
				}
			}

            $details = apply_filters( 'the_content', $event_details );
            $details = str_replace('</p>', '</p> ', $details);
            $details = urlencode(strip_tags($details));
            
			
			$tribeEvents = TribeEvents::instance();

			if ( $postId === null || ! is_numeric( $postId ) ) {
				$postId = $my_post->ID;
			}
			// protecting for reccuring because the post object will have the start/end date available
			$start_date = isset( $post->EventStartDate )
				? strtotime( $post->EventStartDate )
				: strtotime( get_post_meta( $postId, '_EventStartDate', true ) );
			$end_date   = isset( $post->EventEndDate )
				? strtotime( $post->EventEndDate . ( get_post_meta( $postId, '_EventAllDay', true ) ? ' + 1 day' : '' ) )
				: strtotime( get_post_meta( $postId, '_EventEndDate', true ) . ( get_post_meta( $postId, '_EventAllDay', true ) ? ' + 1 day' : '' ) );

			$dates    = ( get_post_meta( $postId, '_EventAllDay', true ) ) ? date( 'Ymd', $start_date ) . '/' . date( 'Ymd', $end_date ) : date( 'Ymd', $start_date ) . 'T' . date( 'Hi00', $start_date ) . '/' . date( 'Ymd', $end_date ) . 'T' . date( 'Hi00', $end_date );
			$location = trim( $tribeEvents->fullAddressString( $postId ) );
			$base_url = 'http://www.google.com/calendar/event';

			
			$params = array(
				'action'   => 'TEMPLATE',
				'text'     => urlencode( strip_tags( $post->post_title ) ),
				'dates'    => $dates,
				'details'  => $details,
				//'details'  => urlencode( get_post_field('post_content', $postId, 'raw') ),
				//'details'  => 'lorem ipsum',
				'location' => urlencode( $location ),
				'sprop'    => get_option( 'blogname' ),
				'trp'      => 'false',
				'sprop'    => 'website:' . home_url(),
			);
			
			
			$url = "https://www.google.com/calendar/render
			?action=TEMPLATE
			&text=Borderline+Personality+Disorder
			&dates=20151006T190000/20151006T201500
			&details=Borderline+personality+disorder+%28BPD%29+is+a+serious+condition.+Symptoms+can+include+problems+regulating+emotions+and+thoughts,+impulsive+and/or+reckless+behaviour,+unstable+relationships,+and+sometimes+psychotic+episodes.+These+topics+will+be+discussed+as+well+as+defense+mechanisms+,+neurosis,+%E2%80%9Cblack+and+white%E2%80%9D+perceptions+and+self+harming+behaviour.+We+will+have+an+overview+of+treatment+methods.+The+impact+of+BPD+on+family+and+friends+will+also+be+addressed.+There+will+be+a+question+and+answer+period.+The+presentation+will+be+in+English+Speaker:+Sally+Butterworth,+psychologist+
			&location
			&sprop=website:http://thecaregivernetwork.ca
			&trp=false
			&pli=1
			&sf=true
			&output=xml#eventpage_6";
			
			
			/*
			https://www.google.com/calendar/render
			?action=TEMPLATE
			&text=Borderline+Personality+Disorder
			&dates=20151006T190000/20151006T201500
			&details
			&location
			&sprop=website:http://thecaregivernetwork.ca
			&trp=false
			&pli=1
			&sf=true
			&output=xml#eventpage_6
			*/
			//$params = apply_filters( 'tribe_google_calendar_parameters', $params );
			$url    = add_query_arg( $params, $base_url );
			
			return esc_url( $url );
		}
?>