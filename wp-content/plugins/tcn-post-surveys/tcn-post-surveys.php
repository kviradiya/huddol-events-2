<?php
/**
 * Plugin Name: TCN: Post Surveys
 * Author: Jon Hill
 * Description: Attach surveys to posts.
 * Version: Only and forever
 */
 
 class PostSurveysMain {
	function __construct() 
	{
		add_action( 'add_meta_boxes', array( $this, 'adding_custom_meta_boxes') );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
	}
    
    function save_meta_box_data()
    {
        global $post;
        
        if ( ! isset( $_POST['post_surveys_meta_box_nonce'] ) ) 
	    {
    		return;
    	}

    	if ( ! wp_verify_nonce( $_POST['post_surveys_meta_box_nonce'], 'post_surveys_meta_box' ) ) 
    	{
    		return;
    	}

    	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	{
    		return;
    	}

    	if ( ! current_user_can( 'edit_post', $post->ID ) ) 
    	{
			return;
		}
    	
    	if ( ! isset( $_POST['post_surveys_survey'] ) ) 
    	{
    		return;
    	}        
    	
        update_post_meta($post->ID, 'post_surveys_survey', $_POST['post_surveys_survey']);
        update_post_meta($post->ID, 'post_surveys_interval', $_POST['post_surveys_interval']);
    }
    
    function render_meta_box()
    {
        global $wpdb;
        global $post;
        wp_nonce_field( 'post_surveys_meta_box', 'post_surveys_meta_box_nonce' );
        
        $survey_link = get_post_meta($post->ID, 'post_surveys_survey', true);
        $survey_interval = get_post_meta($post->ID, 'post_surveys_interval', true);
        
        echo 'Survey Link (Use SurveyMonkey. Please contact TCN for link): ';
        echo '<input name="post_surveys_survey" value="' . $survey_link . '" /><br/>';
        
        echo 'Send Survey After (Enter A Number of Days. The default is 7 if you do not enter a value):<br/>';
        echo '<input name="post_surveys_interval" value="' . $survey_interval . '" />';
    }
            
    function adding_custom_meta_boxes( $post_type, $post=null ) {
        /*
        add_meta_box( 
            'post-surveys',
            __( 'Post Surveys' ),
             array( $this, 'render_meta_box'),
            'post',
            'normal',
            'default'
        );
        */
        
        add_meta_box( 
            'post-surveys',
            __( 'Post Surveys' ),
             array( $this, 'render_meta_box'),
            'tribe_events',
            'normal',
            'default'
        );
        
        /*
        add_meta_box( 
            'post-surveys',
            __( 'Post Surveys' ),
             array( $this, 'render_meta_box'),
            'page',
            'normal',
            'default'
        );
        */
    }
}

new PostSurveysMain;
