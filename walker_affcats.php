
<?php

    class WalkerAffCats extends Walker
    {
       public $tree_type = 'category';

       public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

       public function start_lvl( &$output, $depth = 0, $args = array() ) {
          $output .= "<ul class='children'>\n";
       }

       public function end_lvl( &$output, $depth = 0, $args = array() ) {
          $output .= "</ul>\n";
       }

       public function start_el( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {

            if($args['has_children']){
                $output .= "<li><a class='aff_dropdown' href='" . home_url() . "/shop/?product_kind=" . $category->slug . "'>" . $category->name . "<span class='add_ddtri'></span></a>\n";
            }else{
                $output .= "<li><a href='" . home_url() . "/shop/?product_kind=" . $category->slug . "'>" . $category->name . "</a>\n";
            }
    
       }

       public function end_el( &$output, $category, $depth = 0, $args = array() ) {
          $output .= "</li>\n";
       }

       public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

            parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

       }

    }

?>