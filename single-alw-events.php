<?php
/**
 * Template for displaying all single ARTICAL posts
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header();
            
            wp_enqueue_style('style_custom_front_events', ALW_EVENTS_PLUGIN_URL . 'css/event-custom-front.css');
            
            wp_enqueue_style('flexslider_event_style', ALW_EVENTS_PLUGIN_URL . 'css/flexslider.css');
            
            //wp_enqueue_style('lightbox_event_style', ALW_EVENTS_PLUGIN_URL . 'css/lightbox.min.css');
            //wp_enqueue_script('alw-events-lightbox-js', ALW_EVENTS_PLUGIN_URL . 'js/lightbox-plus-jquery.min.js', array('jquery'));      
            
            wp_enqueue_script('alw-events-flexslider-js', ALW_EVENTS_PLUGIN_URL . 'js/jquery.flexslider-min.js', array('jquery'));            
            wp_enqueue_script('alw-events-custom-script-front-js', ALW_EVENTS_PLUGIN_URL . 'js/event-custom-front.js', array('jquery'));
?>
<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
        <div class="bg-entryHeader">
                    <header class="entry-header">
                          
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <div class="breadcrumbs"> <?php if (function_exists('dp_breadcrumb')) {
        echo dp_breadcrumb();
    } 
    
    ?>
                        </div>
                    </header>
                </div><!-- .entry-header -->
    <div class="entry-content custom-portfolio">
         <div class="left-sidebar">
             <?php
             if (function_exists('widgets_on_template')) {
                widgets_on_template("Menu Sidebar"); 
             }
             ?>
          </div>
        
        <?php
        global $post ;
        /*
         * Run the loop to output the post.
         * If you want to overload this in a child theme then include a file
         * called loop-single.php and that will be used instead.
         */
        
        //echo '<pre>';print_r($post);echo '<pre>';
        ?>
            <!-- Place somewhere in the <body> of your page -->
            
            
            <div class="box-siteContent">
               
                    <?php
                        $images = get_post_meta (get_the_ID(), 'alw_events_images', 'single');
                        $images_arr = unserialize($images);
                       // echo '<pre>';print_r($images_arr);echo '<pre>';
                        if(count($images_arr) > 0)
                        {
                            echo ' <div class="flexslider">
                                    <ul class="slides">';
                            foreach($images_arr as $image)
                            {
                                ?>
                                    <li>
                                        <img src="<?php echo $image; ?>"/>
                                    </li>
                                <?php
                            }
                            echo '</ul></div>';
                        }

                    ?>
               
            <div>
            <h3><?php echo the_title(); ?></h3>
            
                <?php
                 echo $post->post_content ;
                ?>
                
            </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('.nextend-nav-2511').addClass('active opened');      
                    jQuery('.nextend-nav-893').addClass('active opened');
                });
            </script>
            <?php
            global $post ;
                
               //echo '<div style="display:none;">';
               
               $categories = get_the_terms( $post->ID, 'events_category' );
               //echo '<pre >';print_r($categories);echo '<pre>';
               if(count($categories) > 0)
               {
                   foreach($categories as $category)
                   {
                      $cat_slug = $category->slug ;
                       if($cat_slug == 'trade-show')
                       {?>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                jQuery('.nextend-nav-364').addClass('active opened');
                                
                                    
                                });
                            </script>
                       <?php
                       }
                       if($cat_slug == 'event')
                       {?>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                jQuery('.nextend-nav-1995').addClass('active opened');


                                });
                            </script>
                        <?php
                       }
                       
                       if($cat_slug == 'life-at-alw')
                       {?>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    jQuery('.nextend-nav-362').addClass('active opened');
                                
                                });
                            </script>
                        <?php
                       }
                       
                   }
               }
                
                //echo '</div>';
                //get_template_part( 'loop', 'single' );
            ?>
        </div>
    </div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
