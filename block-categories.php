<?php

/**
 * Adds a custom block category to the list of block categories.
 *
 * @param array $categories The existing array of block categories.
 * @return array The modified array of block categories with the custom category added.
 */
add_filter( 'block_categories_all', [ $this, 'add_block_category' ], 10, 2 );
function add_block_category( $categories ) {
    $custom_categories = [
        [
            'slug' => 'custom-blocks',
            'title' => __( 'Custom Blocks', 'your-text-domain' ),
            'icon'    => null,
        ],
    ];

    return array_merge( $categories, $custom_categories );
}

/**
 * Adds a custom block category to the list of block categories in a specific position.
 *
 * This function hooks into the 'block_categories_all' filter to add a custom block category
 * at a specified position in the list of block categories.
 *
 * @param array $categories The existing array of block categories.
 * @return array The modified array of block categories with the custom category added.
 */
add_filter( 'block_categories_all', [ $this, 'add_order_block_category' ], 10, 2 );
function add_order_block_category( $categories ) {
    $custom_category = [
        'slug' => 'custom-block',
        'title' => __( 'Custom Block', 'your-text-domain' ),
        'icon'  => null,
        'position' => 1,
    ];

    // Extract the position from the custom category array
    $position = $custom_category[ 'position' ];

    // Remove the position key from the custom category array
    unset( $custom_category[ 'position' ] );

    // Insert the custom category at the desired position
    array_splice( $categories, $position, 0, [ $custom_category ] );

    return $categories;
}

/**
 * Adds multiple custom block categories to the list of block categories in specific positions.
 *
 * This function hooks into the 'block_categories_all' filter to add custom block categories
 * at specified positions within the existing categories array.
 *
 * @param array $categories The existing array of block categories.
 * @return array The modified array of block categories with custom categories added.
 */
add_filter( 'block_categories_all', [ $this, 'add_order_multiple_block_categories' ], 10, 2 );
function add_order_multiple_block_categories( $categories ) {
    $custom_categories = [
        [
            'slug' => 'custom-category-1',
            'title' => __( 'Custom Category 1', 'your-text-domain' ),
            'icon'    => null,
            'position' => 1,
        ],
        [
            'slug' => 'custom-category-2',
            'title' => __( 'Custom Category 2', 'your-text-domain' ),
            'icon'    => null,
            'position' => 3,
        ],
    ];

    $added_categories = [];

    // Prepare an associative array with positions as keys
    foreach ( $custom_categories as $custom_category ) {
        $position = $custom_category[ 'position' ];
        unset( $custom_category[ 'position' ] );
        $added_categories[ $position ] = $custom_category;
    }

    // Sort the categories to insert by their positions/key
    ksort( $added_categories );

    // Insert the sorted categories into the existing categories array
    foreach ( $added_categories as $position => $custom_category ) {
        array_splice( $categories, $position, 0, [ $custom_category ] );
    }

    return $categories;
}

/**
 * Reorders the 'design' category in the block categories list.
 *
 * This function hooks into the 'block_categories_all' filter to find the 'design' category
 * and move it to a new position within the categories array.
 *
 * @param array $categories The list of block categories.
 * @return array The modified list of block categories with the 'design' category reordered.
 */
add_filter( 'block_categories_all', [ $this, 'reorder_single_category' ], 10, 2 );
function reorder_single_category( $categories ) {
    $design_category = null;
    $new_position = 1;

    // Find and remove the design category from the existing categories
    foreach ( $categories as $key => $category ) {
        if ( $category[ 'slug' ] === 'design' ) {
            $design_category = $category;
            unset( $categories[ $key ] );
            break;
        }
    }

    // If the design category was found above insert it at the new position
    if ( $design_category ) {
        array_splice( $categories, $new_position, 0, [ $design_category ] );
    }

    // Reindex the array
    return array_values( $categories );
}

/**
 * Reorders multiple block categories in the WordPress editor.
 *
 * This function hooks into the 'block_categories_all' filter to reorder
 * specific block categories based on predefined positions.
 *
 * @param array $categories The existing block categories.
 * @return array The reordered block categories.
 */
add_filter( 'block_categories_all', [ $this, 'reorder_multiple_categories' ], 10, 2 );
function reorder_multiple_categories( $categories ) {
    $reorder_categories = [
        'design' => 0,
        'text' => 3,
    ];

    $moved_categories = [];

    // Iterate through the existing categories and add/remove the ones to be reordered
    foreach ( $categories as $key => $category ) {
        if ( array_key_exists( $category[ 'slug' ], $reorder_categories ) ) {
            $moved_categories[ $reorder_categories[ $category[ 'slug' ] ] ] = $category;
            unset( $categories[ $key ] );
        }
    }

    // Sort the moved categories by their new positions
    ksort( $moved_categories );

    // Insert the moved categories at their new positions
    foreach ( $moved_categories as $position => $category ) {
        array_splice( $categories, $position, 0, [ $category ] );
    }

    // Reindex the array
    $categories = array_values( $categories );

    return $categories;
}

/**
 * Filters the block categories and renames the 'text' category to 'Text Elements'.
 *
 * @param array $categories Array of block categories.
 * @return array Modified array of block categories with renamed 'text' category.
 */
add_filter( 'block_categories_all', [ $this, 'rename_single_category' ], 10, 2 );
function rename_single_category( $categories ) {
    foreach ( $categories as $category ) {
        if ( $category[ 'slug' ] === 'text' ) {
            $category[ 'title' ] = __( 'Text Elements', 'your-text-domain' );
        }
    }

    return $categories;
}

/**
 * Filters the block categories to rename multiple categories.
 *
 * @param array $categories Array of block categories.
 * @return array Modified array of block categories with renamed titles.
 */
add_filter( 'block_categories_all', [ $this, 'rename_multiple_categories' ], 10, 2 );
function rename_multiple_categories( $categories ) {
    foreach ( $categories as $category ) {
        if ( $category[ 'slug' ] === 'text' ) {
            $category[ 'title' ] = __( 'Text Elements', 'your-text-domain' );
        }
        if ( $category[ 'slug' ] === 'design' ) {
            $category[ 'title' ] = __( 'Design Elements', 'your-text-domain' );
        }
    }

    return $categories;
}

/**
 * Modify the order and titles of block categories.
 *
 * This function hooks into the 'block_categories_all' filter to modify the order and titles of block categories.
 * It allows for custom ordering and renaming of block categories.
 *
 * @param array $categories The existing block categories.
 * @return array The modified block categories.
 */
add_filter( 'block_categories_all', [ $this, 'modify_block_categories' ], 10, 2 );
function modify_block_categories( $categories ) {
    $new_category_order = [
        [
            'slug' => 'media',
            'position' => 0,
        ],
        [
            'slug' => 'custom-category',
            'title' => __( 'Custom Category', 'your-text-domain' ),
            'position' => 1,
        ],
        [
            'slug' => 'text',
            'title' => __( 'Text Elements', 'your-text-domain' ),
            'position' => 2,
        ],
        [
            'slug' => 'embed',
            'position' => 3,
        ],
        [
            'slug' => 'design',
            'position' => 4,
        ],
    ];

    // Create an associative array of block categories with the slug as the key.
    $current_block_categories = array_column( $categories, 'title', 'slug' );

    // Check if the new category order has a title set, otherwise use the default title.
    foreach ( $new_category_order as &$new_category ) {
        $new_category[ 'title' ] = $new_category[ 'title' ] ?? $current_block_categories[ $new_category[ 'slug' ] ] ?? __( 'Untitled', 'your-text-domain' );
    }

    // Prepare an associative array with positions as keys
    $moved_categories = [];
    foreach ( $new_category_order as $new_category ) {
        if ( isset( $new_category[ 'position' ] ) ) {
            $position = ( int ) $new_category[ 'position' ];
            unset( $new_category[ 'position' ] );
            $moved_categories[ $position ] = $new_category;
        }
    }

    // Sort the categories to insert by their positions
    ksort( $moved_categories );

    // Insert the sorted categories into the original categories array
    foreach ( $moved_categories as $position => $new_category ) {
        array_splice( $categories, $position, 0, [ $new_category ] );
    }

    // Filter out the remaining block categories that are not in the new order.
    $new_category_slugs = array_column( $new_category_order, 'slug' );
    $remaining_categories = array_filter( $categories, function ( $category ) use ( $new_category_slugs ) {
        return ! in_array( $category[ 'slug' ], $new_category_slugs, true );
    });

    // Merge the new category order with the remaining categories.
    return array_merge( $categories, $remaining_categories );
}