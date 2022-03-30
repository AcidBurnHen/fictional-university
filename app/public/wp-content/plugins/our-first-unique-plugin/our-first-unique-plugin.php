<?php 

/* 
    Plugin Name: Our Test Plugin
    Description: A truly amazing plugin.
    Version: 1.0
    Author: Brad
    Author URI: https://www.nono.com
    Text Domain: wcpdomain
    Domain Path: /languages
*/

class WordCountAndTimePlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
        add_action('init', array($this, 'languages'));
    }

    function settings() { 
        
        register_setting('wordcountplugin', 'wcp_location', array( // registers a new setting
            'sanitize_callback' => array($this, 'sanitizeLocation'), // custom method for conditional logic and extra security, so the ID can only be 0 or 1
            'default' => '0' // the value used in locationHTML
        ));
        register_setting('wordcountplugin', 'wcp_headline', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Post Statistics'
        ));
        register_setting('wordcountplugin', 'wcp_wordcount', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1' // will make the checkbox checked
        ));
        register_setting('wordcountplugin', 'wcp_charactercount', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1' 
        ));
        register_setting('wordcountplugin', 'wcp_readtime', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1' 
        ));

        // adds a section to a new registered settings
        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page'); // 2nd arguments adds subtitle, and 3rd argument adds a bit of paragraph under the subtitle

        // ties HTML to the registered settings
        add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section'); 
        add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section'); 
        add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount')); 
        add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount')); 
        add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime')); 
    }

    function adminPage() {
        // add option page to wordpress->settings
        add_options_page('Word Count Settings', __('Word Count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHTML')); 
    }

    function sanitizeLocation($input) {
        if ($input != '0' AND $input != '1') {
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be beginning or end');
            return get_option('wcp_location');
        }
        return $input;
    }

    function locationHTML() { ?>
        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location'), 0); ?>>Begining of post</option> 
            <option value="1" <?php selected(get_option('wcp_location'), 1); ?>>End of post</option>
        </select>
     <?php }

    function headlineHTML() { ?>
        <input tpye="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">
    <?php }


    /*
    function wordcountHTML() { ?>
        <input type="checkbox" name="wcp_wordcount" value="1" <?php checked(get_option('wcp_wordcount', 1)) ?>>
    <?php }

    function charactercountHTML() { ?>
        <input type="checkbox" name="wcp_charactercount" value="1" <?php checked(get_option('wcp_charactercount', 1)) ?>>
    <?php }

    function readtimeHTML() { ?>
        <input type="checkbox" name="wcp_readtime" value="1" <?php checked(get_option('wcp_readtime', 1)) ?>>
    <?php }

    not good way to make this, since it is all the same code, it is better to build a re-usable function for all checkboxes
    */

    function checkboxHTML($args) { ?>
        <input type="checkbox" name="<?php echo $args['theName'] ?>" value="1" <?php checked(get_option($args['theName']), 1)?>>
    <?php }

    
    function ourHTML() { // html of the options page ?>
    
     <div class="wrap">
         <h1> Word Count Settings </h1>
         <form action="options.php" method="POST">
             <?php
                settings_fields('wordcountplugin'); // connects with the registered setting, also handles security and permissions necessary to change settings
                do_settings_sections('word-count-settings-page'); // calls the sections added to settings in lines above
                submit_button();  // used to save changes
            ?>
         </form> 
     </div>

    
    <?php }

    function ifWrap($content) {
        if ( is_main_query() AND is_single() AND (get_option('wcp_wordcount', 1) OR get_option('wcp_charactercount', '1') OR get_option('wcp_readtime', '1')) ) {
            return $this->createHTML($content);
        }
        return $content;
    }

    function createHTML($content) {
        $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

        if (get_option('wcp_wordcount', '1') OR get_option('wcp_readtime', '1')) {
            $wordCount = str_word_count(strip_tags($content));
        }

        if (get_option('wcp_wordcount', '1')) {
            $html .= esc_html__('This post has', 'wcpdomain') . ' ' . $wordCount . ' ' . __('words', 'wcpdomain') . '.<br>';
        }

        if (get_option('wcp_readtime', '1')) {
            $html .= 'This post will take about ' . round($wordCount/225) . ' minute(s) to read.<br>';
        }

        if (get_option('wcp_charactercount', '1')) {
            $html .= 'This post has ' . strlen(strip_tags($content)) . ' characters.<br>';
        }

        $html .= '</p>';

        if (get_option('wcp_location', '0') == '0') {
            return $html . $content;
        }

        return $content . $html;
    }
    function languages() { // makes the plugin translatable 
        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();



