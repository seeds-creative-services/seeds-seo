<?php

/**
Plugin Name: Seeds SEO
Description: Manage SEO meta content for pages.
Version: 1.0.0
Author: Seeds Creative Services, LLC.
Author URI: https://seedscreativeservices.com
Text Domain: seeds_seo
*/

class SeedsSEO {

    public function __construct() {

        /**
         * Exit if this file is accessed directly.
         */

        defined('ABSPATH') || exit;


        /**
         * Override default Gutenberg styles.
         * Load global block Javascript files.
         * @since 1.0.0
         */

        add_action('admin_enqueue_scripts', function() {

            $scriptURL = plugins_url() . "/seeds-seo/seeds-seo.js";
            wp_register_script('seeds-seo-script', $scriptURL, array('jquery'), '1.0.0', 'all');
            wp_enqueue_script('seeds-seo-script');

            $styleURL = plugins_url() . "/seeds-seo/seeds-seo.css";
            wp_register_style('seeds-seo-style', $styleURL, [], '1.0.0', 'all');
            wp_enqueue_style('seeds-seo-style');

        });

        $this->AddMetaBoxes();

    }

    public function AddMetaBoxes() {

        add_action("add_meta_boxes", function() {

            foreach(get_post_types('', 'objects') as $post_type) {

                // Only show the SEO block on public post types.
                if($post_type->public == 1 || $post_type->public == "1") {

                    add_meta_box(
                        "seeds_seo",
                        esc_html__("SEO Meta Content", "seedscs"),
                        "render_seeds_seo",
                        $post_type->name,
                        "advanced",
                        "default"
                    );

                }

            }

        });

        function render_seeds_seo() {

            global $post;

            $post_url = ($post->post_type === "post" || $post->post_type === "page") ? $post->post_name : $post->post_type."/".$post->post_name;
            $post_slug = get_site_url()."/".$post_url."/";

            $meta = get_post_meta($post->ID, "seeds_seo", TRUE); ?>

            <div class="row">

                <div class="col-6">

                    <input type="hidden" name="seeds_seo_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">

                    <fieldset class="inline-field">
                        <strong>Allow Indexing</strong>
                        <label for="seeds_seo_index">
                        <span class="input-toggle">
                            <input type="checkbox" id="seeds_seo_index" name="seeds_seo[indexing]" value="index" <?php if((!isset($meta['indexing']) || $meta['indexing'] === "index")) echo "checked"; ?>>
                        </span>
                        </label>
                    </fieldset>

                    <fieldset class="inline-field">
                        <strong>Allow Crawling</strong>
                        <label for="seeds_seo_follow">
                        <span class="input-toggle">
                            <input type="checkbox" id="seeds_seo_follow" name="seeds_seo[crawling]" value="follow" <?php if((!isset($meta['crawling']) || $meta['crawling'] === "follow")) echo "checked"; ?>>
                        </span>
                        </label>
                    </fieldset>

                    <fieldset>
                        <label for="seeds_seo_title"><strong>Meta Title</strong></label>
                        <input type="text" class="widefat" id="seeds_seo_title" name="seeds_seo[title]" value="<?php echo isset($meta['title']) ? $meta['title'] : ""; ?>" oninput="Seeds.SEO.UpdateTitle();">
                    </fieldset>

                    <fieldset>
                        <label for="seeds_seo_description"><strong>Meta Description</strong></label>
                        <textarea type="text" class="widefat" id="seeds_seo_description" name="seeds_seo[description]" oninput="Seeds.SEO.UpdateDescription();">
                            <?php echo isset($meta['description']) ? $meta['description'] : ""; ?>
                        </textarea>
                    </fieldset>

                </div>

                <div class="col-6">

                    <fieldset>
                        <strong>Google Preview</strong>
                        <div id="google-preview">
                            <a href="#">https://seedscreativeservices.com</a>
                            <h3></h3>
                            <p></p>
                        </div>
                    </fieldset>

                    <fieldset>
                        <strong>Facebook Preview</strong>
                        <div id="facebook-preview">
                            <div id="facebook-content">
                                <div id=facebook-text">
                                    <h3></h3>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>

            </div>

        <?php }


        add_action("save_post", "save_seeds_seo");

        function save_seeds_seo($post_id) {

            if(isset($_POST['seeds_seo_nonce'])) {


                if(!wp_verify_nonce($_POST['seeds_seo_nonce'], basename(__FILE__)))
                    return $post_id;


                if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                    return $post_id;


                if("page" === $_POST['post_type'])
                    if(!current_user_can("edit_page", $post_id))
                        return $post_id;


                /* Declare the previous and current meta data values */
                $previous_meta = get_post_meta($post_id, "seeds_seo", TRUE);
                $current_meta  = $_POST['seeds_seo'];


                if($current_meta && $current_meta !== $previous_meta)
                    update_post_meta($post_id, "seeds_seo", $current_meta);


                if("" === $current_meta && $previous_meta)
                    delete_post_meta($post_id, "seeds_seo", $previous_meta);


            }

        }

    }

}

global $SeedsSEO;
$SeedsSEO = new SeedsSEO;