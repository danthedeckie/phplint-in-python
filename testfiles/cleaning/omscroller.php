<?php
/**
 * Plugin Name: OM Scroller
 * Description: A Scrolling Widget that shows the OM focus areas, and pray-give-go logos.
 * Version: 0.1
 * Author: Daniel Fairhead
 * Author URI: http://www.om.org/
 */

add_action( 'widgets_init', 'register_om_scroller' );

function register_om_scroller() {
	register_widget( 'OM_Scroller' );
}

class Collapsable {
    /* In a widget, if you want a section to be collapsable, use this.
        Collapsable::start('Links');
           echo 'stuff inside the widget...';
        Collapsable::end();
    */
    public function start($title, $id) {
        echo '<div class="widget ' . $id . '"><div class="widget-top" style="cursor:pointer"><div class="widget-title-action"><a class="widget-action hide-if-no-js" href="#"></a></div><div class="widget-title"><h5>', __($title, 'omscroller'), '</h5></div></div><div class="widget-inside">';
    }
    public function end() {
        echo '</div></div>';
    }

    public function hide_by($checkbox, $id) {
        // hide a section ($id) depending on value of $checkbox (a name given by "$this->get_field_name")

        ?><script>
            (function($){
                var s=$('input[name="<?php echo $checkbox;?>"]'),
                    x=function(){ s.parent().siblings('.<?php echo $id ?>').toggle(s.prop("checked"));};
                x();
                s.click(x);
            })(jQuery)</script><?php
    }
}


class OM_Scroller extends WP_Widget {

	function OM_Scroller() {
		$widget_ops = array( 'classname' => 'omscroller',
		                   'description' => __('A Scrolling Widget that shows the OM Focus Areas, and Pray Give Go logos.', 'omscroller'));
		$control_ops = array('height' => 300,
		                     'show_areas'=>1,
		                     'show_pgg'=>1,
                             'show_posts'=>0,
		                     'show_time'=>9000,
		                     'id_base' => 'omscroller',
                             'link_pray'=>0,
                             'link_give'=>0,
                             'link_go'=>0,
                             'link_church_planting'=>0,
                             'link_evangelism'=>0,
                             'link_justice'=>0,
                             'link_relief_and_development'=>0,
                             'link_discipleship'=>0,
		                     'categories'=>array());

		$this->WP_Widget( 'omscroller', __('OM Scroller', 'omscroller'), $widget_ops, $control_ops);

	}

    private static function omarea($name, $title, $desc, $url, $img_dir) {
    ?>
        <a class=" col span_1_of_5" href="<?php echo get_permalink($url); ?>">
            <div class="omarea <?php echo $name; ?>">
                <img src="<?php echo $img_dir . $name . '.png'; ?>" alt="<?php echo $title; ?>" />
                <div class="title"><?php echo $title; ?></div>
                <div class="desc"><h3><?php echo $title; ?></h3><p><?php echo $desc; ?></p></div>
            </div>
        </a>
    <?php
    }

    private static function pgg($name, $title, $desc, $url, $img_dir) {
    ?>
        <a class=" col span_1_of_3" href="<?php echo get_permalink($url); ?>">
            <div class="omarea <?php echo $name; ?>">
                <img src="<?php echo $img_dir . $name . '.png'; ?>" alt="<?php echo $title; ?>" />
                <!--<div class="title"><?php echo $title; ?></div>-->
                <div class="desc"><h3><?php echo $title; ?></h3><p><?php echo $desc; ?></p></div>
            </div>
        </a>
    <?php
    }



	function widget( $args, $instance ) {
		extract ($args);
		$show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;

		echo $before_widget;

		$img_dir = get_template_directory_uri() . '/img/';

		?>
			<div class="omscroller-box" style="overflow: hidden">
			<?php if($instance['show_areas']) { ?>
			<div class="omareas group">
                <?php 
                    OM_Scroller::omarea('church_planting', __('Church Planting', 'om-2014'),
                           __('The DNA of God\'s global church.', 'om-2014'),
                           $instance['link_church_planting'], $img_dir);

                    OM_Scroller::omarea('evangelism', __('Evangelism', 'om-2014'),
                           __('"Go and make disciples of all Nations" - Jesus', 'om-2014'),
                           $instance['link_evangelism'], $img_dir);

                    OM_Scroller::omarea('justice', __('Justice', 'om-2014'),
                           __('God is serious about Justice. So are we.', 'om-2014'),
                           $instance['link_justice'], $img_dir);

                    OM_Scroller::omarea('relief_and_development', __('Relief and Development', 'om-2014'),
                           __('Caring for the sick, comforting the broken-hearted', 'om-2014'),
                           $instance['link_relief_and_development'], $img_dir);

                    OM_Scroller::omarea('mentoring', __('Mentoring', 'om-2014'),
                           __('Empowering the next generation', 'om-2014'),
                           $instance['link_mentoring'], $img_dir);

                           ?>
			</div>
			<?php } // omareas

			if($instance['show_pgg']) { ?>
			<div id="pgg-wrapper" class="pgg-wrapper omareas group">
                <?php
                    OM_Scroller::pgg('pgg-pray', __('Pray', 'om-2014'),
                           __('OM is committed to prayer.  How else could be be here at all?', 'om-2014'),
                           $instance['link_pray'], $img_dir);
                    OM_Scroller::pgg('pgg-give', __('Give', 'om-2014'),
                           __('None of this would be possible without the generosity of the saints.', 'om-2014'),
                           $instance['link_give'], $img_dir);
                    OM_Scroller::pgg('pgg-go', __('Go', 'om-2014'),
                           __('Every 24 hours over 350000 people are born.  15000 die.  All of them need the Saviour.', 'om-2014'),
                           $instance['link_go'], $img_dir);

                ?>
			</div>
			<?php } // pgg
            if ($instance['show_posts']) { 

                $query = new WP_Query(array('category__in'=>$instance['categories']));
                while ($query->have_posts()) {
                $query->the_post();

                ?><div class="group omscroller_post">
                    <div class="col span_3_of_5">
                        <a href="<?php echo get_permalink(); ?>"><h3><?php the_title(); ?></h3><?php the_excerpt();?></a>
                    <a class="more-link" href="<?php echo get_permalink(); ?>"><?php _e("Read More") ?></a>
                    </div>
                    <div class="col span_2_of_5" style="text-align:center"><?php the_post_thumbnail(); ?></div>
                </div>
                <?php }

                 wp_reset_postdata();
			 } // show_posts
             ?>
			</div>
		<?php // scrollbox

		echo $after_widget;
		?><script>_defer(function($){$("#<?php echo $this->id; ?> > .omscroller-box").scrollbox(<?php echo $instance['show_time']; ?>);});</script><?php

	}

	function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['height'] = (int) $new_instance['height'];
		$instance['show_areas'] = $new_instance['show_areas']=='on';
		$instance['show_pgg'] = $new_instance['show_pgg']=='on';
		$instance['show_posts'] = $new_instance['show_posts']=='on';
		$instance['show_time'] = (int) $new_instance['show_time']?:$old_instance['show_time'];
		$instance['categories'] = (array) $new_instance['categories'];
        
        $om_things = array("pray","give","go","church_planting","evangelism","justice","relief_and_development","discipleship");

        foreach($om_things as $t) {
            $instance['link_' . $t] = (int) $new_instance['link_' . $t];
        }

		return $instance;
	}

	function form ( $instance ) {
		$defaults =  array('height' => 300,
		                     'show_areas'=>1,
		                     'show_pgg'=>1,
		                     'show_posts'=>1,
		                     'show_time'=>9000,
		                     'categories'=>array(),
                             'link_pray'=>0,
				);

		$instance = wp_parse_args( (array) $instance, $defaults);

        // General Options:

		$this->form_control('height', 'Initial Height', $instance, 'number');
		$this->form_control('show_areas', 'Show Areas?', $instance, 'checkbox');
		$this->form_control('show_pgg', 'Show Pray-Give-Go?', $instance, 'checkbox');
		$this->form_control('show_posts', 'Show Posts?', $instance, 'checkbox');

		$this->form_control('show_time', 'Time to show each page (ms)', $instance, 'number');

        // Page Links Options:

        $pages = array();

        foreach (get_pages() as $p) {
            $pages[$p->ID] = $p->post_title;
        }

        Collapsable::start('Pray/Give/Go Links', 'pgg_links');

            $this->form_select('link_pray', 'Pray:', $instance, $pages);
            $this->form_select('link_give', 'Give:', $instance, $pages);
            $this->form_select('link_go', 'Go:', $instance, $pages);

        Collapsable::end();

        Collapsable::hide_by($this->get_field_name('show_pgg'), 'pgg_links');

        Collapsable::start('Area Links', 'area_links');

            $this->form_select('link_church_planting', 'Church Planting:', $instance, $pages);
            $this->form_select('link_evangelism', 'Evangelism:', $instance, $pages);
            $this->form_select('link_justice', 'Justice:', $instance, $pages);
            $this->form_select('link_relief_and_development', 'Relief and Development', $instance, $pages);
            $this->form_select('link_discipleship', 'Discipleship', $instance, $pages);

        Collapsable::end();

        Collapsable::hide_by($this->get_field_name('show_areas'), 'area_links');

        // Tagged Posts options:

		Collapsable::start('Limit posts to categories...', 'post_categories');

            foreach (get_categories() as $category) {
                echo '<input type="checkbox" id="' . $this->get_field_id('categories') .'[]"
                      name="' . $this->get_field_name( 'categories' ) . '[]" ' .
                      checked(in_array($category->term_id,$instance['categories']), true, false) .
                      ' value="' . $category->term_id . '" /><label>'. $category->name .'</label><br/>'; 
            }
            
            if (!$categories) {
                echo "No categories are defined.";
            }

        Collapsable::end();

        Collapsable::hide_by($this->get_field_name('show_posts'), 'post_categories');

        echo '<hr/>';

	}

	function form_control($name, $label, $instance, $type) {
		$id = $this->get_field_id($name);
		$value = ($type=='checkbox')? 
		             checked(true, isset($instance[$name])?$instance[$name]:false, false):
		             'value="'. $instance[$name] .'"';
		printf('<p><label for="%s">%s </label> <input id="%s" name="%s" %s type="%s"/></p>',
		       $id, __($label,'omscroller'), $id,
		       $this->get_field_name($name), $value, $type);
	}

    function form_select($name, $label, $instance, $options) {
        echo '<label>' . __($label) . ' <select name="'. $this->get_field_name($name) . '">';
            foreach ($options as $id => $title) {
                echo '<option value="'. $id . '" '
                     . ( $instance[$name] == $id ? "selected" : "" )
                     .'>' . $title . '</option>';
            }
            echo '</select></label><br/>';

    }


}

?>
