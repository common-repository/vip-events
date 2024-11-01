<?php
if (!class_exists('Alweventsfront')) {

    class Alweventsfront {
        public static $instance;
        public function __construct() {
            add_action('init', array($this, 'init_hooks'));
        }
        function init_hooks() {
            
            $this->events_register();
            $this->create_events_taxonomy();
            add_action('add_meta_boxes', array($this, 'add_custom_meta_box'));
            add_action('save_post', array($this, 'save_custom_meta'));            
            add_shortcode('alw_view_events', array($this, 'alw_view_events_func'));
            add_filter('single_template', array($this,'single_post_custom_template'));
        }
       
        
        function create_events_taxonomy() {
            register_taxonomy(
                    'events_category', 'alw-events', array(
                'label' => __('Event Categories'),
                'rewrite' => array('slug' => 'eventcat'),
                'hierarchical' => true,
                    )
            );
        }

        public function add_custom_meta_box() {
            $screens = array('alw-events');
            foreach ($screens as $screen) {
                add_meta_box(
                        'alw-events_sectionid', __('Events Details', 'event'), array($this, 'show_custom_meta_box'), // $callback
                        $screen
                );
            }
        }

        public function custom_meta_fields() {
            $categories = get_terms('events_category', 'orderby=name&hide_empty=0');
            $categories_arr = $categories;
            $prefix = 'alwevent_';
            $custom_meta_fields = array(
                 array(
                    'label' => 'Event Date',
                    'desc' => '',
                    'id' => $prefix . 'event_date',
                    'type' => 'text',
                    'class' => 'event_date_cls',
                     'attr'=>'required'
                ),
            );
            return $custom_meta_fields;
        }

        public function show_custom_meta_box() {
            wp_enqueue_script( 'jquery-ui-core' );
            
            global $post;
            $custom_meta_fields = $this->custom_meta_fields();
            // Use nonce for verification
            echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';
            // Begin the field table and loop
            echo '<table class="form-table">';
            foreach ($custom_meta_fields as $field) {
                $meta = get_post_meta($post->ID, $field['id'], true);
                // get value of this field if it exists for this post
                echo '<tr>
						<th><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
						<td>';
                switch ($field['type']) {
                    // text
                    case 'text':
                        echo '<input '.$field['attr'].' type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="30" />
									<br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                    case 'email':
                        echo '<input type="email" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="30" />
									<br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                    // textarea

                    case 'textarea':
                        echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" cols="60" rows="4">' . $meta . '</textarea>
									<br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>
									<label for="' . $field['id'] . '">' . $field['desc'] . '</label>';
                        break;
                    // select
                    case 'select':
                        echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
                        foreach ($field['options'] as $option) {
                            echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
                        }
                        echo '</select><br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                    // select
                    case 'select-multiple':
                        echo '<select multiple="multiple" name="' . $field['id'] . '[]" id="' . $field['id'] . '">';
                        foreach ($field['options'] as $option) {
                            echo '<option', in_array($option->term_id, $meta) ? ' selected="selected"' : '', ' value="' . $option->term_id . '">' . $option->name . '</option>';
                        }
                        echo '</select><br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                    case 'select-multiple-from-menus':
                        echo '<select multiple="multiple" name="' . $field['id'] . '[]" id="' . $field['id'] . '">';
                        foreach ($field['options'] as $option) {
                            echo '<option', in_array($option->ID, $meta) ? ' selected="selected"' : '', ' value="' . $option->term_id . '">' . $option->title . '</option>';
                        }
                        echo '</select><br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                } //end switch
                echo '</td></tr>';
            } // end foreach
            ?>
<?php
$value = get_post_meta($post->ID, 'alw_events_images', true);
$images = unserialize($value);
?>

<tr>
    <td colspan="2" class="upload_image_section">
        <div class="image-container">
            <h3>Upload Images</h3>
            <ul class="widget-images-list">
                <?php
                $i = 0;
                foreach ($images as $key1 => $image) {
                    ?>
                    <li class="editthisimage">
                        <span class="image_container">
                            <img src="<?php echo $image; ?>" />
                            <input type="hidden" value="<?php echo $image; ?>" name="alw_events_images[]">
                        </span>
                        <span class="action_container">
                            <a href="#remove" class="edit-image">Edit</a>
                            &nbsp;
                            <a href="#remove" class="remove-image">Delete</a>	
                        </span>
                    </li>

                    <?php
                    $i++;
                }
                ?>
                <li class="add-image-box">
                    <div class="add-thumb-project">

                    </div>
                    <div class="add-image-video">
                        <input type="hidden" name="imagess" id="unique_name" class="all-urls" value="" />
                        <div class="add-image-slide" title="Add image">
                            <input type="button" class="button button-primary button-large wp-media-buttons-icon add-image"  id="unique_name_button"  value="Add Images"  />
                            <br /><span class="description">Recommended size for upload image is 870x500</span>
                        </div>
                    </div>
                </li>
            </ul>
            <script>
                jQuery(document).ready(function ($) {
                    function secondimageslistlisize() {
                        var lisaze = jQuery('#images-list').width();
                        lisaze = lisaze * 0.06;
                        jQuery('#images-list .widget-images-list li').not('.add-image-box').not('.first').height(lisaze);
                    }
                    jQuery(window).resize(function () {
                        //secondimageslistlisize()										
                    });
                    jQuery(".wp-media-buttons-icon").click(function () {
                        jQuery(".attachment-filters").css("display", "none");
                    });
                    var _custom_media = true,
                            _orig_send_attachment = wp.media.editor.send.attachment;

                    /*#####ADD NEW PROJECT######*/
                    jQuery('.huge-it-newuploader .button').click(function (e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = jQuery(this);
                        var id = button.attr('id').replace('_button', '');
                        _custom_media = true;

                        jQuery("#" + id).val('');
                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                jQuery("#" + id).val(attachment.url + ';;;' + jQuery("#" + id).val());
                                jQuery("#save-buttom").click();
                            } else {
                                return _orig_send_attachment.apply(this, [props, attachment]);
                            }
                            ;
                        }
                        wp.media.editor.open(button);
                        return false;
                    });

                    jQuery('.widget-images-list').on('click', '.edit-image', function (e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var $src;
                        var button = jQuery(this);
                        var image_container = button.parent().siblings('.image_container');
                        var img = image_container.find('img');
                        var input = image_container.find('input');
                        //var images = image_container.find('img');
                        _custom_media = true;
//                                    jQuery(".media-menu .media-menu-item").css("display","none");
//                                    jQuery(".media-menu-item:first").css("display","block");
//                                    jQuery(".separator").next().css("display","none");
//                                    jQuery('.attachment-filters').val('image').trigger('change');
//                                    jQuery(".attachment-filters").css("display","none");
                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                img.attr('src', attachment.url);

                                input.attr('value', attachment.url);
                            } else {
                                return _orig_send_attachment.apply(this, [props, attachment]);
                            }
                            ;
                        }
                        wp.media.editor.open(button);
                        return false;
                    });

                    jQuery('.add_media').on('click', function () {
                        _custom_media = false;
                    });
                    /*#####ADD IMAGE######*/
                    jQuery('.add-image.button').click(function (e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;

                        var button = jQuery(this);
                        var id = button.attr('id').replace('_button', '');
                        _custom_media = true;

                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                jQuery("#" + id).parent().parent().before('<li class="editthisimage"><span class="image_container"><img src="' + attachment.url + '" alt="" /></span><span class="action_container"><a class="edit-image">Edit</a>&nbsp;<a href="#remove" class="remove-image">Delete</a></span><input type="hidden" name=alw_events_images[] value="' + attachment.url + '"></li>');
                                //alert(jQuery("#"+id).val());
                                jQuery("#" + id).val(jQuery("#" + id).val() + attachment.url + ';');

                                //secondimageslistlisize();

                            } else {
                                return _orig_send_attachment.apply(this, [props, attachment]);
                            }
                            ;
                        }

                        wp.media.editor.open(button);

                        return false;
                    });


                    /*#####REMOVE IMAGE######*/
                    jQuery("ul.widget-images-list").on('click', '.remove-image', function () {

                        if (confirm('Are you sure want to remove this image ? '))
                        {
                            jQuery(this).closest('.editthisimage').remove();
                            var allUrls = "";
                            var $src;

                            jQuery(this).parents('ul.widget-images-list').find('img').not('.plus').each(function () {
                                $src = jQuery(this).attr('src');
                                console.log($src);
                                allUrls = allUrls + $src + ';';
                                jQuery(this).parent().parent().parent().find('input.all-urls').val(allUrls);
                                //secondimageslistlisize();
                            });
                            jQuery(this).parent().remove();
                            return false;
                        }
                    });
                });
            </script>
        </div>
    </td>
</tr>
<?php
            echo '</table>'; // end table
        }

        // Save the Data
        public function save_custom_meta($post_id) {

            $custom_meta_fields = $this->custom_meta_fields();
            // verify nonce
            if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
                return $post_id;
            // check autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;
            // check permissions
            if ('page' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id))
                    return $post_id;
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
            foreach ($custom_meta_fields as $field) {
                $old = get_post_meta($post_id, $field['id'], true);
                $new = $_POST[$field['id']];

                if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
                } elseif ('' == $new && $old) {
                    delete_post_meta($post_id, $field['id'], $old);
                }
            }
            // end foreach
            $posted_images = $_POST['alw_events_images'];
            
            $alw_events_images = array();
            if (!empty($posted_images)) {
                foreach ($posted_images as $image_url) {
                    if (!empty($image_url))
                        $alw_events_images[] = esc_url_raw($image_url);
                }
            }
            // Update the alw_events_images meta field.
            update_post_meta($post_id, 'alw_events_images', serialize($alw_events_images));
        }

        public static function create_instance() {
            if (is_null(self::$instance))
                self::$instance = new Alweventsfront();
            return self::$instance;
        }

        function events_register() {
            $labels = array(
                'name' => _x('Alw Events', 'post type general name'),
                'singular_name' => _x('All Events', 'post type singular name'),
                'all_items' => _x('All Events', 'portfolio'),
                'add_new' => _x('Add New Event', 'events'),
                'add_new_item' => __('Add New Event'),
                'edit_item' => __('Edit Event'),
                'new_item' => __('New Event'),
                'view_item' => __('View Event'),
                'search_items' => __('Search Events'),
                'not_found' => __('Nothing found'),
                'not_found_in_trash' => __('Nothing found in Trash'),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'alw-events'),
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => '30',
                'supports' => array('title', 'editor', 'thumbnail'),
                'menu_icon' => 'dashicons-analytics',
            );
            register_post_type('alw-events', $args);
            //flush_rewrite_rules();
        }
  
        function get_post_counts($slug) {
            $args = array(
                'post_type' => 'alw-events',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'events_category',
                        'field' => 'slug',
                        'terms' => $slug,
                    ),
                ),
            );
            $query = new WP_Query($args);
            return $query->found_posts;
        }

        function alw_view_events_func($atts) {

            global $my_query ;
            wp_enqueue_style('style_custom_front_events', ALW_EVENTS_PLUGIN_URL . 'css/event-custom-front.css');
            wp_enqueue_style('flexslider_event_style', ALW_EVENTS_PLUGIN_URL . 'css/flexslider.css');
            wp_enqueue_style('lightbox_event_style', ALW_EVENTS_PLUGIN_URL . 'css/lightbox.min.css');
            
            wp_enqueue_script('alw-events-lightbox-js', ALW_EVENTS_PLUGIN_URL . 'js/lightbox-plus-jquery.min.js', array('jquery'));      
            
            wp_enqueue_script('alw-events-flexslider-js', ALW_EVENTS_PLUGIN_URL . 'js/jquery.flexslider-min.js', array('jquery'));            
            wp_enqueue_script('alw-events-custom-script-front-js', ALW_EVENTS_PLUGIN_URL . 'js/event-custom-front.js', array('jquery'));

            
                $a = shortcode_atts( array(
                    'category' => 'event',
                    'number_of_records' => 10,
                ), $atts );            
            $category = $a['category'];
            $exploded_cat = explode(',',$category);
            
            $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;


                $number_of_records = $a['number_of_records'];
                   $type = 'alw-events';
                      $args = array(
                      'post_type' => $type,
                      'post_status' => 'publish',
                     'posts_per_page'    => $number_of_records,
                      'paged' => $paged,
                      'tax_query' => array(
                         array(
                              'taxonomy' => 'events_category',
                              'field' => 'slug',
                              'terms' => $exploded_cat,
                          ),
                      ),
                    'meta_key'          => 'alwevent_event_date',
                     'orderby' => 'meta_value',
                     'order'             => 'ASC',
                  );
                  $my_query = new WP_Query($args);
                if ($my_query->have_posts()) {
                    echo '<div class="event-main">';
                    
                    while ($my_query->have_posts()) : $my_query->the_post();
                    ?>
                    <div class="event-row">
                        
                            <?php if (has_post_thumbnail()) { ?>
                                <a  class="img_thumb_area" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail(array(350,250)); ?></a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                        <a  class="img_thumb_area" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                            <img src="https://s3-ap-southeast-1.amazonaws.com/vsimg1/thumb/propimages/Jothi-Auditorium-thumb-placeholder.jpg" title="<?php the_title_attribute();?>">
                        </a>
                                    <?php
                                    
                                }
                                ?>
                        <div class="container_wrapper">                                    
                                         <?php
                                         $images = get_post_meta (get_the_ID(), 'alw_events_images', 'single');
                                         $images_arr = unserialize($images);
                                         if(count($images_arr) > 0)
                                         {
                                             ?>
                                                <div class="view_gallery_container">
                                                <a class="view-gallery-link" href="javascript:void(0)" title="View Gallery" onclick="init_gallery_popup('<?php echo get_the_ID()?>');" id="my_gallery_init<?php echo get_the_ID();?>">View Gallery</a>
                                            <?php
                                             $dynamic_id = 'hdn_img_container'.get_the_ID() ;
                                             echo "<div id=".$dynamic_id.">";
                                             foreach($images_arr as $key=>$image)
                                             {
                                                 $filename = basename($image);
                                                 ?>
                                                  <a href="<?php echo $image ; ?>" data-lightbox="image-set<?php echo get_the_ID() ;?>" data-title=""></a>

                                                 <?php
                                             }
                                             echo '</div></div>';
                                         }

                                         ?>

                                 
                            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                            <?php 
                            $content = get_the_content();
                            
                            if(strlen($content) > 500)
                            {
								?>
                                
                        <div class="event_date_section">
                            <?php
                               $post_id = get_the_ID();
                               $alwevent_event_date = get_post_meta($post_id, 'alwevent_event_date', 'single');
                               if(!empty($alwevent_event_date))
                               {
                                echo $formated_event_date = date("F Y", strtotime($alwevent_event_date));
                               }
                            ?>
                        </div>
                            <?php
                                echo substr($content, 0, 500).'...';
                                echo '&nbsp; <div class="more-btn"><a class="more-link" title="Read More" href='.get_the_permalink().'>Read More</a></div>';
                            }
                            else
                            {
                                
                            ?>
                                
                        <div class="event_date_section">
                            <?php
                               $post_id = get_the_ID();
                               $alwevent_event_date = get_post_meta($post_id, 'alwevent_event_date', 'single');
                               if(!empty($alwevent_event_date))
                               {
                                echo $formated_event_date = date("F Y", strtotime($alwevent_event_date));
                               }
                            ?>
                        </div>
                                <?php
                                echo $content ;
                                
                            }
                            
                            echo '</div></div>';
                    endwhile;
                    echo $this->bittersweet_pagination('custom');
             
                    echo '</div>';
                }
            
        }
        
        /* Filter the single_template with our custom function*/
	function single_post_custom_template( $single ) {
		global $wp_query, $post;
		/* Checks for single template by post type */
		if ( $post->post_type == 'alw-events' ) {
			if( file_exists( ALW_EVENTS_PLUGIN_URL_DIR . 'single-alw-events.php' ) )
				return ALW_EVENTS_PLUGIN_URL_DIR . 'single-alw-events.php';
		}
		
		return $single;
	}
        
        function bittersweet_pagination($custom = null) {
                global $my_query;
                $big = 999999999;
                $class = 'pagination';
                if ($custom == 'custom') {
                    $class = 'pagination-wrap';
                }
                $pages = paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $my_query->max_num_pages,
                    'type' => 'array',
                    'prev_text'          => __('←'),
                    'next_text'          => __('→'),
                ));

                if (is_array($pages)) {
                    $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
                    echo '<div class="' . $class . '"><ul class="pagination">';
                    foreach ($pages as $page) {
                        echo "<li>$page</li>";
                    }
                    echo '</ul></div>';
                }
    }

    }

    Alweventsfront::create_instance();
}

