<?php

/**
Plugin Name: Seeds SEO
Description: Manage SEO meta content for pages.
Version: 1.0.0
Author: Seeds Creative Services, LLC.
Author URI: https://seedscreativeservices.com
Text Domain: seeds_seo
*/

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

  <div>

    <input type="hidden" name="seeds_seo_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">

    <fieldset class="wporg_field">
      <strong>Search Engine Settings</strong>
      <div class="widefat">
        <label for="seeds_seo_index">
          <input type="radio" id="seeds_seo_index" name="seeds_seo[indexing]" value="index" <?php if((isset($meta['indexing']) && $meta['indexing'] === "index") or !isset($meta['indexing'])) echo "checked"; ?>>
          Allow Indexing
        </label>
      </div>
      <div class="widefat">
        <label for="seeds_seo_noindex">
          <input type="radio" id="seeds_seo_noindex" name="seeds_seo[indexing]" value="noindex" <?php if(isset($meta['indexing']) && $meta['indexing'] === "noindex") echo "checked"; ?>>
          Disallow Indexing
        </label>
      </div>
      <div class="widefat">
        <label for="seeds_seo_follow">
          <input type="radio" id="seeds_seo_follow" name="seeds_seo[crawling]" value="follow" <?php if((isset($meta['crawling']) && $meta['crawling'] === "follow") or !isset($meta['crawling'])) echo "checked"; ?>>
          Allow Crawling
        </label>
      </div>
      <div class="widefat">
        <label for="seeds_seo_nofollow">
          <input type="radio" id="seeds_seo_nofollow" name="seeds_seo[crawling]" value="nofollow" <?php if(isset($meta['crawling']) && $meta['crawling'] === "nofollow") echo "checked"; ?>>
          Disallow Cralwing
        </label>
      </div>
    </fieldset>

    <br>

    <fieldset class="wporg_field">
      <label for="seeds_seo_title"><strong>Meta Title</strong></label>
      <input type="text" class="widefat" id="seeds_seo_title" name="seeds_seo[title]" value="<?php echo isset($meta['title']) ? $meta['title'] : ""; ?>">
    </fieldset>

    <br>

    <fieldset class="wporg_field">
      <label for="seeds_seo_description"><strong>Meta Description</strong></label>
      <textarea type="text" class="widefat" id="seeds_seo_description" name="seeds_seo[description]"><?php echo isset($meta['description']) ? $meta['description'] : ""; ?></textarea>
    </fieldset>

    <br>

  </div>

<?php }


add_action("save_post", "save_seeds_seo");

function save_seeds_seo($post_id) {

  if(isset($_POST['seeds_seo_nonce'])) {


    if(!wp_verify_nonce($_POST['seeds_seo_nonce'], basename(__FILE__)))
      return $post_id;


    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
      return $post_id;


    if("page" === $POST['post_type'])
      if(!current_user_can("edit_page", $post_id))
        return $post_id;


    /* Delare the previous and current meta data values */
    $previous_meta = get_post_meta($post_id, "seeds_seo", TRUE);
    $current_meta  = $_POST['seeds_seo'];


    if($current_meta && $current_meta !== $previous_meta)
      update_post_meta($post_id, "seeds_seo", $current_meta);


    if("" === $current_meta && $previous_meta)
      delete_post_meta($post_id, "seeds_seo", $previous_meta);


  }

}
