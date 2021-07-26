<?php
/**
 * Created by PhpStorm.
 * User: jwoodcock
 * Date: 10/6/19
 * Time: 1:00 AM
 */

namespace WordPressHelper;


class WordPressHelper
{

    /**
     * Creates a custom taxonomy.
     *
     * @param $title
     * @param $args
     * @param string $text_domain
     */
    static public function createTaxonomy( $title, $args = [], $text_domain = 'text_domain' ) {
        $Title  = ucwords( $title );
        $Titles = ucwords( $title ) . 's';
        $titles = $title . 's';
        $labels = [
            'name'                       => _x( $Titles, 'Taxonomy General Name', $text_domain ),
            'singular_name'              => _x( $Title, 'Taxonomy Singular Name', $text_domain ),
            'menu_name'                  => __( $Titles, $text_domain ),
            'all_items'                  => __( 'All ' . $Titles, $text_domain ),
            'parent_item'                => __( 'Parent ' . $Title, $text_domain ),
            'parent_item_colon'          => __( 'Parent ' . $Title . ':', $text_domain ),
            'new_item_name'              => __( 'New ' . $Title . ' Name', $text_domain ),
            'add_new_item'               => __( 'Add New ' . $Title, $text_domain ),
            'edit_item'                  => __( 'Edit ' . $Title, $text_domain ),
            'update_item'                => __( 'Update ' . $Title, $text_domain ),
            'view_item'                  => __( 'View ' . $Title, $text_domain ),
            'separate_items_with_commas' => __( 'Separate ' . $titles . ' with commas', $text_domain ),
            'add_or_remove_items'        => __( 'Add or remove ' . $titles, $text_domain ),
            'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
            'popular_items'              => __( 'Popular ' . $Titles, $text_domain ),
            'search_items'               => __( 'Search ' . $Titles, $text_domain ),
            'not_found'                  => __( 'Not Found', $text_domain ),
            'no_terms'                   => __( 'No ' . $Titles, $text_domain ),
            'items_list'                 => __( $Titles . ' list', $text_domain ),
            'items_list_navigation'      => __( $Titles . ' list navigation', $text_domain ),
        ];
        $args   = array_merge( [
            'labels'            => $labels,
            'hierarchical'      => TRUE,
            'public'            => TRUE,
            'show_ui'           => TRUE,
            'show_admin_column' => TRUE,
            'show_in_nav_menus' => TRUE,
            'show_tagcloud'     => FALSE,
        ], $args );

        register_taxonomy( TextHelper::slugify( $title ), [ 'post', 'episode', 'advertisement' ], $args );
    }

    /**
     * Creates a custom post type.
     *
     * @param $title
     * @param $args
     * @param string $text_domain
     */
    static public function createPostType( $title, $args = [], $text_domain = 'text_domain' ) {
        $slug = self::slugify($title);
        $Title  = ucwords( $title );
        $Titles = ucwords( $title ) . 's';
        $titles = $title . 's';
        $labels = [
            'name'                       => _x( $Titles, 'Taxonomy General Name', $text_domain ),
            'singular_name'              => _x( $Title, 'Taxonomy Singular Name', $text_domain ),
            'menu_name'                  => __( $Titles, $text_domain ),
            'all_items'                  => __( 'All ' . $Titles, $text_domain ),
            'parent_item'                => __( 'Parent ' . $Title, $text_domain ),
            'parent_item_colon'          => __( 'Parent ' . $Title . ':', $text_domain ),
            'new_item_name'              => __( 'New ' . $Title . ' Name', $text_domain ),
            'add_new_item'               => __( 'Add New ' . $Title, $text_domain ),
            'edit_item'                  => __( 'Edit ' . $Title, $text_domain ),
            'update_item'                => __( 'Update ' . $Title, $text_domain ),
            'view_item'                  => __( 'View ' . $Title, $text_domain ),
            'separate_items_with_commas' => __( 'Separate ' . $titles . ' with commas', $text_domain ),
            'add_or_remove_items'        => __( 'Add or remove ' . $titles, $text_domain ),
            'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
            'popular_items'              => __( 'Popular ' . $Titles, $text_domain ),
            'search_items'               => __( 'Search ' . $Titles, $text_domain ),
            'not_found'                  => __( 'Not Found', $text_domain ),
            'no_terms'                   => __( 'No ' . $Titles, $text_domain ),
            'items_list'                 => __( $Titles . ' list', $text_domain ),
            'items_list_navigation'      => __( $Titles . ' list navigation', $text_domain ),
        ];
        $args   = array_merge( [
            'label'               => __( $Title, 'text_domain' ),
            'description'         => __( 'Custom post type', $text_domain ),
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'excerpt', 'author', 'thumbnail' ],
            'taxonomies'          => [ 'post_tag', 'category' ],
            'hierarchical'        => TRUE,
            'public'              => TRUE,
            'show_ui'             => TRUE,
            'show_in_menu'        => TRUE,
            'menu_position'       => 5,
            'show_in_admin_bar'   => TRUE,
            'show_in_nav_menus'   => TRUE,
            'can_export'          => TRUE,
            'has_archive'         => TRUE,
            'exclude_from_search' => FALSE,
            'publicly_queryable'  => TRUE,
            'capability_type'     => 'page',
        ], $args );


        register_post_type( $slug, $args );

        if ( in_array( 'category', $args['taxonomies'] ) ) {
            register_taxonomy_for_object_type( 'category', $slug );
        }

        if ( in_array( 'post_tag', $args['taxonomies'] ) ) {
            register_taxonomy_for_object_type( 'post_tag', $slug );
        }
    }

    /**
     * Returns HTML string using passed array and external HTML template.
     *
     * @param $template_name
     * @param null $new_vars
     *
     * @return string
     */
    static public function get_template_html( $template_name, $new_vars = [] ) {

        ob_start();
        $vars = array_merge( [
            'text_domain' => 'text_domain',
        ], $new_vars );
        require( get_stylesheet_directory() . "/templates/$template_name.php" );
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    static public function get_posts_by_term( $type, $tax = NULL, $term = 1, $max = - 1, $offset = 0 ) {
        $args = [
            'post_type'   => $type,
            'numberposts' => $max,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'offset'      => $offset,
        ];
        if ( $tax != NULL ) {
            $args['tax_query'] = [
                [
                    'taxonomy'         => $tax,
                    'field'            => 'slug',
                    'terms'            => $term,
                    'include_children' => FALSE,
                ],
            ];
        }

        return get_posts( $args );
    }

     /**
     * Get the user's roles
     * @since 1.0.0
     */
    static public function get_current_user_roles() {
        if( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $roles = ( array ) $user->roles;
            return $roles; // This returns an array
            // Use this to return a single value
            // return $roles[0];
        } else {
            return array();
        }
    }
    
    /**
    * Slugify a string.
    *
    * @param null $str
    *
    * @returns string
    */
    static public function slugify( $str, $separator = '-' ) {
        return strtolower(str_replace( " ", $separator, $str ));
    }

    /**
    * Shorten a string.
    *
    * @param null $str
    * @param number $max
    * @param string $suffix
    *
    * @returns string
    */
    static public function shorten( $str, $max = 200, $suffix = '...' ) {
        $str = preg_replace("/<img[^>]+\>/i", "", $str);
        $pos = strpos( $str, ' ', $max );
        return substr( $str, 0, $pos ) . $suffix;
    }

}
