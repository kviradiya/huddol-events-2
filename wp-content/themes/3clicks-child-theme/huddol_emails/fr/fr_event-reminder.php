<?php
		$events_buttons = new Three_Clicks_Child_Theme_Email_Buttons;
		
		$event_meta_phone_number = get_post_meta($event->ID, 'event_meta_phone_number', true);
    $event_meta_conference_id = get_post_meta($event->ID, 'event_meta_conference_id', true);
    $event_meta_webinar_link = get_post_meta($event->ID, 'event_meta_webinar_link', true);

    $event_meta_phone_number = override_from_user($event->ID, 'event_meta_phone_number', $event_meta_phone_number);
    $event_meta_conference_id = override_from_user($event->ID, 'event_meta_conference_id', $event_meta_conference_id);
    $event_meta_webinar_link = override_from_user($event->ID, 'event_meta_webinar_link', $event_meta_webinar_link);
		
    $hosted_by_url = '';

    $imgURL = get_post_thumbnail($event->ID, 'full'); 

    $author = get_user_by("id", $event->post_author);

    $hosted_by_url = get_user_meta($author->ID, 'tcn_partner_english_website', true);

    if(substr($hosted_by_url, 0, 7) == 'http://' || substr($hosted_by_url, 0, 8) == 'https://')
    {
        // URL is okay, do nothing
    }
    else
    {
        $hosted_by_url = 'http://' . $hosted_by_url;
    }
    
?>

<?php include('header.php'); ?>
			
			<h1 style="font-size:24px;">Rappel d’évènement</h1>
			
			<p><?php echo $events_buttons->my_single_event_links($event->ID); ?></p>

			<img src="<?php echo $imgURL; ?>" style="width: 100%;">
			
			<div style="text-align: left;padding: 10px 40px;"> 
				<h2><?php echo $event->post_title ?></h2>
				
				<p>
					Présenté par: <a href="<?php echo $partner_url; ?>" style="color:#25aae1;"><?php echo $author->user_login?></a><br>
					Sujets: <?php echo tcn_capture_entry_categories($event); ?><br>
					Date: <?php echo get_event_date($event); ?>
				</p>
				
				<p style="font-weight: bold">
					Pour rejoindre l’évènement veuillez suivre les instructions suivantes: 
				</p>
				
				<p>
					1. Cliquez sur le bouton “Rejoindre présentation” en dessous<br>
					2. Connectez-vous à la salle de réunion en sélectionnant l’option “Entrer comme invité” <br>
					3. Entrez votre nom puis sélectionnez “Entrer dans la salle” <br>					
					<?php if( $event_meta_phone_number != '' && $event_meta_conference_id != ''): ?>
					4. Composez le numéro gratuit suivant: <?php echo $event_meta_phone_number ?>, code d’accès: <?php echo $event_meta_conference_id ?>
					<?php endif ?>
				</p>
			</div>
				
			<a href="<?php echo get_permalink($event->ID); ?>" style="display: inline-block; margin:10px 0 35px; background-color:#3bc38f; padding:20px 30px; border-radius:30px; 
							   color: #fff; font-size:18px; font-weight: bold; border: 0px; outline: 0; text-decoration: none;">
				Rejoindre la présentation
			</a>
			
			<div style="background-color:#e8eaef; width:100%; height:1px;"></div>
			
			<div style="text-align: left;padding: 20px 40px;"> 
				<p style="font-weight: bold">
					Informations Techniques
				</p>
				
				<p>
					- <a href="http://huddol.adobeconnect.com/common/help/en/support/meeting_test.htm" style="color:#25aae1;">Testez votre ordinateur</a><br>
					- <a href="http://www.adobe.com/products/acrobatconnectpro/systemreqs/" style="color:#25aae1;">Exigence du système Adobe Connect</a><br>
					- <a href="https://huddol.events.com/wp-content/uploads/2018/01/adobeconnect-troubleshooting-guide-fr.pdf" style="color:#25aae1;">Téléchargez notre guide de dépannage</a>
				</p>
			</div>
		</div>
		
<?php include('footer.php'); ?>
