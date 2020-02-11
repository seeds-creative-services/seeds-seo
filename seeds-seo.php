<?php

/**
Plugin Name: Seeds SEO
Description: Manage SEO meta content for pages.
Version: 1.0.0
Author: Seeds Creative Services, LLC.
Author URI: https://seedscreativeservices.com
Text Domain: seeds_seo
*/

if(file_exists(get_theme_root()."/".wp_get_theme()->template."/classes/metabox.php")) {

    require_once(get_theme_root()."/".wp_get_theme()->template."/classes/metabox.php");

    add_action('admin_enqueue_scripts', function() {

        $scriptURL = plugins_url() . "/seeds-seo/seeds-seo.js";
        wp_register_script('seeds-seo-script', $scriptURL, array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('seeds-seo-script');

        $styleURL = plugins_url() . "/seeds-seo/seeds-seo.css";
        wp_register_style('seeds-seo-style', $styleURL, [], '1.0.0', 'all');
        wp_enqueue_style('seeds-seo-style');

    });

    add_action("the_post", function($post) {

        $meta = get_post_meta($post->ID, "seeds_seo", TRUE);

        $content = "
        <input type='hidden' name='seeds_seo_nonce' value='" . wp_create_nonce(basename(__FILE__)) . "'>
        <div class='row'>
        
            <div class='col-6'>
                
                <fieldset class='inline-field'>
                    <strong>Allow Indexing</strong>
                    <label>
                        <span class='input-toggle'>
                            <input type='checkbox' name='seeds_seo[indexing]' value='index' " . ((!isset($meta['indexing']) || $meta['indexing'] === "index") ? 'checked' : '') . ">
                        </span>
                    </label>
                </fieldset>
                
                <fieldset class='inline-field'>
                    <strong>Allow Crawling</strong>
                    <label>
                        <span class='input-toggle'>
                            <input type='checkbox' name='seeds_seo[crawling]' value='follow' " . ((!isset($meta['crawling']) || $meta['crawling'] === "follow") ? 'checked' : '') . ">
                        </span>
                    </label>
                </fieldset>
                
                <fieldset>
                    <label><strong>Meta Title</strong></label>
                    <input type='text' name='seeds_seo[title]' value='" . (isset($meta['title']) ? $meta['title'] : '') . "' oninput='Seeds.SEO.UpdateTitle();'>
                </fieldset>
                
                <fieldset>
                    <label><strong>Meta Description</strong></label>
                    <textarea name='seeds_seo[description]' oninput='Seeds.SEO.UpdateDescription();'>" . (isset($meta['description']) ? $meta['description'] : '') . "</textarea>
                </fieldset>
            
            </div>
            
            <div class='col-6'>
            
                <fieldset>
                    <strong>Google Preview</strong>
                    <div id='google-preview'>
                        <a href='#'>https://seedscreativeservices.com</a>
                        <h3></h3>
                        <p></p>
                    </div>
                </fieldset>
                
                <fieldset>
                    <strong>Facebook Preview</strong>
                    <div id='facebook-preview'>
                        <div id='facebook-content'>
                            <div id=facebook-text'>
                                <h3></h3>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </fieldset>
            
            </div>
        
        </div>
        ";

        return new SEEDS\MetaBox("seeds_seo", "SEO Meta Content", $content,"all", "advanced", "default");

    });

}