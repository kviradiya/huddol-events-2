
<h2 class="wpallexport-wp-notices"></h2>

<div class="inner-content wpallexport-step-6 wpallexport-wrapper">
	
	<div class="wpallexport-header">
		<div class="wpallexport-logo"></div>
		<div class="wpallexport-title">
			<p><?php _e('WP All Export', 'wp_all_export_plugin'); ?></p>
			<h2><?php _e('Export to XML / CSV', 'wp_all_export_plugin'); ?></h2>					
		</div>
		<div class="wpallexport-links">
			<a href="http://www.wpallimport.com/support/" target="_blank"><?php _e('Support', 'wp_all_export_plugin'); ?></a> | <a href="http://www.wpallimport.com/documentation/" target="_blank"><?php _e('Documentation', 'wp_all_export_plugin'); ?></a>
		</div>

		<div class="clear"></div>	
		<div class="processing_step_1">

			<div class="clear"></div>

			<div class="step_description">
				<h2><?php _e('Export <span id="status">in Progress...</span>', 'wp_all_export_plugin') ?></h2>
				<h3 id="process_notice"><?php _e('Exporting may take some time. Please do not close your browser or refresh the page until the process is complete.', 'wp_all_export_plugin'); ?></h3>		
			</div>		
			<div id="processbar" class="rad14">
				<div class="rad14"></div>			
			</div>			
			<div id="export_progress">
				<span id="left_progress"><?php _e('Time Elapsed', 'wp_all_export_plugin');?> <span id="then">00:00:00</span></span>
				<span id="center_progress"><span id="percents_count">0</span>%</span>
				<span id="right_progress"><?php _e('Exported','wp_all_export_plugin');?> <span id="created_count"><?php echo $update_previous->exported; ?></span></span>				
			</div>			
		</div>

		<div id="export_finished">
			<!--h1><?php _e('Export Complete!', 'wp_all_export_plugin'); ?></h1-->
			<h3><?php _e('WP All Export successfully exported your data!','wp_all_export_plugin'); ?></h3>		
			<p><?php _e('Download Data', 'wp_all_export_plugin'); ?></p>
			<div class="input wp_all_export_download">
				<div class="input">
					<button class="button button-primary button-hero wpallexport-large-button download_data" rel="<?php echo add_query_arg(array('action' => 'download', 'id' => $update_previous->id, '_wpnonce' => wp_create_nonce( '_wpnonce-download_feed' )), $this->baseUrl); ?>"><?php echo strtoupper($update_previous->options['export_to']); ?></button>
				</div>
				<?php if ( empty($update_previous->options['cpt']) or ! in_array('shop_order', $update_previous->options['cpt'])): ?>
				<div class="input" style="margin-left: 10px;">
					<button class="button button-primary button-hero wpallexport-large-button download_data" rel="<?php echo add_query_arg(array('page' => 'pmxe-admin-manage', 'id' => $update_previous->id, 'action' => 'bundle', '_wpnonce' => wp_create_nonce( '_wpnonce-download_bundle' )), $this->baseUrl); ?>"><?php _e('Bundle', 'wp_all_export_plugin'); ?></button>
					<span><?php _e('Settings & Data for WP All Import', 'wp_all_export_plugin'); ?></span>
				</div>
				<?php endif; ?>
			</div>
			<hr>			
			<a href="<?php echo add_query_arg(array('page' => 'pmxe-admin-manage'), remove_query_arg(array('id','page'), $this->baseUrl)); ?>" id="manage_imports"><?php _e('Manage Exports', 'wp_all_export_plugin') ?></a>
		</div>

	</div>
	
	<a href="http://soflyy.com/" target="_blank" class="wpallexport-created-by"><?php _e('Created by', 'wp_all_export_plugin'); ?> <span></span></a>
	
</div>

<script type="text/javascript">
//<![CDATA[
(function($){$(function () {

	$('#status').each(function () {
		var $this = $(this);
		if ($this.html().match(/\.{3}$/)) {
			var dots = 0;
			var status = $this.html().replace(/\.{3}$/, '');
			var interval ;						
			interval = setInterval(function () {				
				if ($this.html().match(new RegExp(status + '\\.{1,3}$', ''))) {									
					$this.html(status + '...'.substr(0, dots++ % 3 + 1));
				} else {											
					$('#process_notice').hide();									
					clearInterval(interval);					
				}
			}, 1000);			
		}

		var then = $('#then');
		start_date = moment().sod();		
		update = function(){
			var duration = moment.duration({'seconds' : 1});
			start_date.add(duration); 
			
			if ($('#process_notice').is(':visible')) then.html(start_date.format('HH:mm:ss'));
		};
		update();
		setInterval(update, 1000);

		var $this = $(this);
												
		interval = setInterval(function () {																					

			var percents = $('#percents_count').html();
			$('#processbar div').css({'width': ((parseInt(percents) > 100 || percents == undefined) ? 100 : percents) + '%'});		
			

		}, 1000);
		
		$('#processbar').css({'visibility':'visible'});	

	});	

	var request = {
		action:'wpallexport',
		security: wp_all_export_security							
    };    

    function wp_all_export_process(){		
		$.ajax({
			type: 'POST',
			url: ajaxurl + ((typeof export_id != "undefined") ? '?id=' + export_id : ''),
			data: request,
			success: function(response) {	
				
				$('#created_count').html(response.exported);						
				$('#percents_count').html(response.percentage);
			    $('#processbar div').css({'width': response.percentage + '%'});

				if (response.done)
				{
					$('#status').html('Complete');	
					window.onbeforeunload = false;	

					setTimeout(function() {
													
						$('#export_finished').fadeIn();																			
						
					}, 1000);	

					//window.location.href = "<?php echo add_query_arg('action', 'download', $this->baseUrl); ?>";
				}
				else
				{					
					wp_all_export_process();
				}				
			},
			error:function(request, status, error){
				$('#status').html('Error');	
				window.onbeforeunload = false;	
				$('#process_notice').after(request.responseText);
			},
			dataType: "json"
		});
	};

	wp_all_export_process();

	window.onbeforeunload = function () {
		return 'WARNING:\nExport process in under way, leaving the page will interrupt\nthe operation and most likely to cause leftovers in posts.';
	};	

});})(jQuery);

//]]>
</script>