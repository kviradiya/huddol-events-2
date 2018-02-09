<?php
    $author = get_user_by('id', $event->post_author);
    $partner_url = get_user_meta($author->ID, 'tcn_partner_english_website', true); 

    if(substr($partner_url, 0, 7) == 'http://' || substr($partner_url, 0, 8) == 'https://')
    {
        // URL is okay, do nothing
    }
    else
    {
        $partner_url = 'http://' . $partner_url;
    }

    $imgURL = get_post_thumbnail($event->ID, 'full'); 
?>

<?php include('header.php'); ?>
			
			<h1 style="font-size:24px;">Enregistrement de l'évènement</h1>
			
			<a href="<?php echo get_permalink($event->ID); ?>" style="display: inline-block; margin:10px 0 35px; background-color:#3bc38f; padding:20px 30px; border-radius:30px; 
							   color: #fff; font-size:18px; font-weight: bold; border: 0px; outline: 0; text-decoration: none;">
				Regarder l'enregistrement
			</a>
			
			<img src="<?php echo $imgURL; ?>" style="width: 100%;">
			
			<div style="text-align: left;padding: 10px 40px;"> 
				<h2><?php echo $event->post_title ?></h2>
				
				<p>
					Présenté par: <?php echo $author->user_login?><br>
					Sujets: <?php echo tcn_capture_entry_categories($event); ?><br>
					Date: <?php echo get_event_date($event); ?>
				</p>

			</div>
				
			<a href="<?php echo get_permalink($event->ID); ?>" style="display: inline-block; margin:10px 0 35px; background-color:#3bc38f; padding:20px 30px; border-radius:30px; 
							   color: #fff; font-size:18px; font-weight: bold; border: 0px; outline: 0; text-decoration: none;">
				Regarder l'enregistrement
			</a>
		</div>

<?php include('footer.php'); ?>
