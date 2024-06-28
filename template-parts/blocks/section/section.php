<?php

/**
 * Section Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

$block_class_name = '';
if ( ! empty( $block['className'] ) ) {
    $block_class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $block_align .= ' align' . $block['align'];
}
if ( ! empty( $block['backgroundColor'] ) ) {
    $block_background_color .= ' bg-' . $block['backgroundColor'];
}

// get number of columns and start processing if exist
$col_count = null;
if ( have_rows('columns') ) {

    $data_id = get_field('data_id');

    /* define initial classes and styles start */
    // container and row classes
    $wrapper_classes = [];
    $container_classes = [];
    $row_classes = [];
    $row_inner_classes = [];

    // container and row styles
    $wrapper_styles = [];
    $container_styles = [];
    $row_styles = [];
    $row_inner_styles = [];

    // initial container and row classes
    $wrapper_classes[] = 'container-wrapper';
    $section_width = get_field('section_width');
    if ( $section_width == 'full' ) {
        $container_classes[] = 'container-fluid';
    } else {
        $container_classes[] = 'container';
    }
    $container_classes[] = 'element-container';

    $row_classes[] = 'row';
    $row_classes[] = 'element-row';
    $row_inner_classes[] = 'row-inner';

    // column classes
    $col_classes = [];
    $col_inner_classes = [];
    $col_inner_content_classes = [];

    // column styles
    $col_styles = [];
    $col_inner_styles = [];
    $col_inner_content_styles = [];

    // initial column styles
    $col_classes[] = 'col-element';
    $col_inner_classes[] = 'col-inner';
    $col_inner_content_classes[] = 'col-inner-content';

    // overlay classes
    $container_overlay = '';
    $row_overlay = '';
    $row_mobile_overlay = '';
    $container_overlay = '';
    $container_video = '';
    $container_mobile_image_overlay = '';
    /* define initial classes and styles end */

    // get column count
    $col_count = count(get_field('columns'));

    /* get column fields */
    $col_width_pass_inside = '';
    $col_width = get_field('columns_per_row'); // determine width inside loop
    $column_gap = get_field('column_gap');
    $mobile_breakpoint = get_field('mobile_breakpoint');
    $reverse_columns = get_field('reverse_columns');
    $element_assignment = get_field('element_assignment');

    // breakpoint
    if ( !$mobile_breakpoint ) {
        $mobile_breakpoint = 'lg';
    }

    // pass width inside loop
    if ( ( $col_width === 'auto' ) || ( $col_width === 'column-element' ) ) {
        $col_width = 12 / $col_count;
        $col_width_pass_inside = 'auto';
    }

    // column gap
    if ( $column_gap ) {
        $row_classes[] = 'gap-' . $column_gap;
        if ( $column_gap === 'custom' ) {
            $custom_gap = get_field('custom_gap');
            if ( $custom_gap ) { ?>
                <style>
                    [data-id="<?=$data_id?>"] .row  > * { 
                        padding-right: calc(<?=$custom_gap?>rem * .5) !important;
                        padding-left: calc(<?=$custom_gap?>rem * .5) !important;
                    }
                </style>
            <?php }
        }
    }

    // add default vertical margin gap on mobile
    $col_bottom_spacing = '';
    if ( ( $col_count === 1 ) || ( $column_gap === 'none' ) ) {
        $col_bottom_spacing = 'mb-0';
    } else {
        $top_bottom_padding_column_mobile = get_field('top_bottom_padding_column_mobile', 'style');
        if ( $top_bottom_padding_column_mobile ) {
            $col_bottom_spacing = 'mb-' . $top_bottom_padding_column_mobile;
        } else {
            $col_bottom_spacing = 'mb-1';
        }
    }
    $col_classes[] = 'mb-' . $mobile_breakpoint . '-0 ' . $col_bottom_spacing;

    // container classes and styling
    $min_height_100vh_minus_menu_height = get_field('min_height_100vh_minus_menu_height');
    $min_height = get_field('min_height');
    if ( $min_height && ( $min_height_100vh_minus_menu_height !== 'enabled' ) ) {
        $value = $min_height['value'];
        if ( $value ) {
            $container_classes[] = 'has-min-height';
            $unit = $min_height['unit'];
            $min_height = 'min-height: ' . $value . $unit;
            $container_styles[] = $min_height . ';';
        }
    } else {
        $header_height = get_field('header_height', 'header');
        $container_classes[] = 'has-min-height';
        $min_height = 'min-height: calc( 100vh - ' . $header_height . 'px)';
        $container_styles[] = $min_height . ';';
    }

    // container background
    $container_background = get_background_bbc('section_background', $container_classes, $container_styles);

    if ( $container_background ) {
        if ( $container_background['classes'] ) {
            $wrapper_classes[] = $container_background['classes'];
        }
        if ( $container_background['styles'] ) {
            $wrapper_styles[] = $container_background['styles'];
        }
        if ( $container_background['overlay'] ) {
            $container_overlay = $container_background['overlay'];
        }
        if ( $container_background['video'] ) {
            $container_video = $container_background['video'];
            $container_video_script = $container_background['video_script'];
        }
        if ( $container_background['mobile_image_overlay'] ) {
            $container_mobile_image_overlay = $container_background['mobile_image_overlay'];
        }
    }

    // row classes and styling
    $max_width = get_field('max_width');

    if ( $max_width ) {
        $value = $max_width['value'];
        if ( $value ) {

            $justify_container_row = get_field('justify_container_row');

            $unit = $max_width['unit'];
            $max_width = 'max-width: ' . $value . $unit;
            $row_styles[] = $max_width . ';';

            if ( $justify_container_row ) {
                if ( $justify_container_row === 'left' ) {
                    $row_classes[] = 'me-' . $mobile_breakpoint . '-auto ms-' . $mobile_breakpoint . '-0';
                } elseif ( $justify_container_row === 'center' ) {
                    $row_classes[] = 'me-' . $mobile_breakpoint . '-auto ms-' . $mobile_breakpoint . '-auto';
                } elseif ( $justify_container_row === 'right' ) {
                    $row_classes[] = 'ms-' . $mobile_breakpoint . '-auto me-' . $mobile_breakpoint . '-0';
                }
            } else {
                $row_classes[] = 'me-' . $mobile_breakpoint . '-auto ms-' . $mobile_breakpoint . '-auto';
            }

        }
    }

    // row background
    $row_background = get_background_bbc('row_background', $row_classes, $row_styles);
    if ( $row_background ) {
        if ( $row_background['classes'] ) {
            $row_classes[] = $row_background['classes'];
        }
        if ( $row_background['styles'] ) {
            $row_styles[] = $row_background['styles'];
        }
        if ( $row_background['overlay'] ) {
            $row_overlay = $row_background['overlay'];
        }
        if ( $row_background['video'] ) {
            $row_video = $row_background['video'];
        }
        if ( $row_background['mobile_image_overlay'] ) {
            $row_mobile_overlay = $row_background['mobile_image_overlay'];
        }
    }

    // container advanced
    $additional_classes = get_field('additional_classes');
    if ( $additional_classes ) {
        $container_classes[] = $additional_classes;
    }

    $custom_id = get_field('custom_id');
    if ( $custom_id ) {
        $custom_id = 'id="'. $custom_id .'"';
    }

    // reverse columns
    $reverse_columns = get_field('reverse_columns');
    if ( $reverse_columns && ( $reverse_columns === 'reverse' ) ) {
        $row_classes[] = 'flex-'. $mobile_breakpoint .'-row';
        $row_classes[] = 'flex-column-reverse';
    }

    // row advanced
    $row_additional_classes = get_field('row_additional_classes');
    if ( $row_additional_classes ) {
        $row_classes[] = $row_additional_classes;
    }

    // flex
    $flex_element = get_field('flex_element');
    if ( $flex_element != 'none' ) {
        $flex = get_flex_bbc(get_field('flex'));
        if ( $flex_element == 'row' ) {
            $row_classes[] = $flex;
        } elseif ( $flex_element == 'container' ) {
            $container_classes[] = $flex;
        }
    }

    // add number of columns to row
    $col_count = get_field('columns');
    if ( $col_count ) {
        $col_count = count($col_count); // get total number of columns
    } else {
        $col_count = 0;
    }
    $row_classes[] = 'columns-' . $col_count;

    // dividers
    $top_divider = get_field('top_divider');
    if ( $top_divider === 'enable' ) {
        $container_classes[] = 'container-divider-top';
        $top_divider_shape = get_field('top_divider_shape');
        if ( $top_divider_shape !== 'none' ) {
            $container_classes[] = $top_divider_shape;
        }

        $top_negative_margin = get_field('top_negative_margin');
        if ( $top_negative_margin === 'yes' ) {
            $container_classes[] = $top_divider_shape . '-container-negative-margin-top';
        }
    }
    $bottom_divider = get_field('bottom_divider');
    if ( $bottom_divider === 'enable' ) {
        $container_classes[] = 'container-divider-bottom';
        $bottom_divider_shape = get_field('bottom_divider_shape');
        if ( $bottom_divider_shape !== 'none' ) {
            $container_classes[] = $bottom_divider_shape;
        }

        $bottom_negative_margin = get_field('bottom_negative_margin');
        if ( $bottom_negative_margin === 'yes' ) {
            $container_classes[] = $bottom_divider_shape . '-container-negative-margin-bottom';
        }
    }

    // process global functions
    $container_classes[] = get_spacing_bbc(get_field('container_spacing'), 'container');
    $container_classes[] = get_borders_bbc(get_field('container_borders'));
    $row_classes[] = get_spacing_bbc(get_field('row_spacing'));

    /* compile classes and styles start */
    $wrapper_classes = trim(implode(' ', $wrapper_classes));
    $container_classes = trim(implode(' ', $container_classes));
    $row_classes = trim(implode(' ', $row_classes));
    $row_inner_classes = trim(implode(' ', $row_inner_classes));
    $wrapper_styles = trim(implode(' ', $wrapper_styles));
    $container_styles = trim(implode(' ', $container_styles));
    $row_styles = trim(implode(' ', $row_styles));
    $row_inner_styles = trim(implode(' ', $row_inner_styles));

    // push styles inside loop
    $col_classes_outside_loop = trim(implode(' ', $col_classes));
    $col_inner_classes_outside_loop = trim(implode(' ', $col_inner_classes));
    $col_inner_content_classes_outside_loop = trim(implode(' ', $col_inner_content_classes));
    $col_styles_outside_loop = trim(implode(' ', $col_styles));
    $col_inner_styles_outside_loop = trim(implode(' ', $col_inner_styles));
    $col_inner_content_styles_outside_loop = trim(implode(' ', $col_inner_content_styles));
    /* compile classes and styles end */

}

if ( get_field('columns') && ( $col_count > 0 ) ) { // if columns, add container start

    // reset column count
    $col_count = 1;

    echo '<section class="'. $wrapper_classes .'" style="'. $wrapper_styles .'">'; // container-wrapper start

        // top divider
        $top_divider = get_field('top_divider');
        if ( $top_divider === 'enable' ) {
            echo get_dividers_bbc('top');
        }

        echo '<div class="'. $container_classes .'" style="'. $container_styles .'" data-id="'. $data_id .'">'; // container start

            if ( $container_video ) {
                echo $container_video;
                echo $container_video_script;
            }

            if ( $container_mobile_image_overlay ) {
                echo $container_mobile_image_overlay;
            }

            if ( $container_overlay ) {
                echo $container_overlay;
            }

            ?>
            <div class="<?=esc_attr($row_classes)?>"<?php if ( get_field('masonry') === 'enabled' ) { ?> data-masonry='{"percentPosition": true }' <?php } ?> style="<?=esc_attr($row_styles)?>">
            <?php // row start

                if ( $row_mobile_overlay ) {
                    echo $row_mobile_overlay;
                }
                if ( $row_overlay ) {
                    echo $row_overlay;
                }

                if( have_rows('columns') ): // if columns start

                    while( have_rows('columns') ) : the_row(); // columns loop start

                        // initialize styles
                        // col
                        $col_classes = [];
                        $col_styles = [];
                        $col_classes[] = $col_classes_outside_loop;
                        $col_classes[] = 'column-' . $col_count;
                        $col_video = null;
                        $col_overlay = null;
                        $col_mobile_overlay = null;

                        // col inner
                        $col_inner_classes = [];
                        $col_inner_styles = [];
                        $col_inner_classes[] = $col_inner_classes_outside_loop;
                        $col_inner_styles[] = $col_inner_styles_outside_loop;
                        $col_inner_video = null;
                        $col_inner_overlay = null;
                        $col_inner_mobile_overlay = null;

                        // col inner content
                        $col_inner_content_classes = [];
                        $col_inner_content_styles = [];
                        $col_inner_content_classes[] = $col_inner_content_classes_outside_loop;
                        $col_inner_styles[] = $col_inner_content_styles_outside_loop;
                        $col_inner_content_video = null;
                        $col_inner_content_overlay = null;
                        $col_inner_content_mobile_overlay = null;

                        $col_element_width = get_sub_field('column_width');

                        if ( $col_width_pass_inside === 'auto' ) {
                            if ( $col_element_width && ( $col_element_width !== 'auto' ) ) {
                                $col_classes[] = 'col-' . $mobile_breakpoint . '-' . $col_element_width;
                            } else {
                                $col_classes[] = 'col-' . $mobile_breakpoint . '-' . $col_width;
                            }
                        }

                        // custom column max width
                        $column_max_width_enable = get_sub_field('column_max_width_enable');
                        if ( $column_max_width_enable && ( $column_max_width_enable === 'enable' ) ) {
                            $column_max_width_value = get_sub_field('column_max_width_value');
                            if ( $column_max_width_value ) {
                                
                                $column_max_width_unit = get_sub_field('column_max_width_unit');

                                // alignment
                                $column_max_width_alignment = get_sub_field('column_max_width_alignment');
                                if ( $column_max_width_alignment === 'left' ) {
                                    $column_max_width_alignment = 'ms-0 me-auto';
                                } elseif ( $column_max_width_alignment === 'center' ) {
                                    $column_max_width_alignment = 'ms-auto me-auto';
                                } elseif ( $column_max_width_alignment === 'right' ) {
                                    $column_max_width_alignment = 'ms-auto me-0';
                                }

                                // assignment
                                $column_max_width_assignment = get_sub_field('column_max_width_assignment');
                                if ( $column_max_width_assignment === 'col-inner' ) {
                                    $col_inner_classes[] = $column_max_width_alignment;
                                    $col_inner_styles[] = 'max-width: ' . $column_max_width_value . $column_max_width_unit;
                                } elseif ( $column_max_width_assignment === 'col-inner' ) {
                                    $col_inner_content_classes[] = $column_max_width_alignment;
                                    $col_inner_content_styles[] = 'max-width: ' . $column_max_width_value . $column_max_width_unit;
                                }

                            }
                        }

                        // column background
                        $column_background = get_background_bbc('column_background', $col_inner_classes, $col_inner_styles, true);
                        if ( $column_background ) {
                            if ( $element_assignment === 'outer') {
                                if ( $column_background['classes'] ) {
                                    $col_classes[] = $column_background['classes'];
                                }
                                if ( $column_background['styles'] ) {
                                    $col_styles[] = $column_background['styles'];
                                }
                                if ( $column_background['overlay'] ) {
                                    $col_overlay = $column_background['overlay'];
                                }
                                if ( $column_background['video'] ) {
                                    $col_video = $column_background['video'];
                                }
                                if ( $column_background['mobile_image_overlay'] ) {
                                    $col_mobile_overlay = $column_background['mobile_image_overlay'];
                                }
                            } elseif ( $element_assignment === 'inner') {
                                if ( $column_background['classes'] ) {
                                    $col_inner_classes[] = $column_background['classes'];
                                }
                                if ( $column_background['styles'] ) {
                                    $col_inner_styles[] = $column_background['styles'];
                                }
                                if ( $column_background['overlay'] ) {
                                    $col_inner_overlay= $column_background['overlay'];
                                }
                                if ( $column_background['video'] ) {
                                    $col_inner_video = $column_background['video'];
                                }
                                if ( $column_background['mobile_image_overlay'] ) {
                                    $col_inner_mobile_overlay = $column_background['mobile_image_overlay'];
                                }
                            }
                        }

                        // column flex
                        $column_flex = get_sub_field('flex_element');
                        if ( $column_flex != 'none' ) {
                            $flex = get_flex_bbc( get_sub_field('flex_column'), $mobile_breakpoint );
                            if ( $column_flex == 'element' ) {
                                $col_classes[] = $flex;
                            } elseif ( $column_flex == 'inner' ) {
                                $col_inner_classes[] = $flex;
                            } elseif ( $column_flex == 'content' ) {
                                $col_inner_content_classes[] = $flex;
                            }
                        }

                        // add default column spacing
                        $default_column_top_bottom_padding = get_sub_field('default_column_top_bottom_padding');
                        if ( $default_column_top_bottom_padding && ( $default_column_top_bottom_padding === 'add' ) ) {

                            // get fields from global styles
                            $top_bottom_padding_column = get_field('top_bottom_padding_column', 'style');
                            $top_bottom_padding_column_mobile = get_field('top_bottom_padding_column_mobile', 'style');

                            // assign values if global styles empty
                            if ( !$top_bottom_padding_column ) {
                                $top_bottom_padding_column = '2';
                            }
                            if ( !$top_bottom_padding_column_mobile ) {
                                $top_bottom_padding_column_mobile = '2';
                            }

                            // assign spacing to column assignment element
                            if ( $element_assignment === 'inner') {
                                $col_inner_classes[] = 'py-' . $mobile_breakpoint . '-' . $top_bottom_padding_column;
                                $col_inner_classes[] = 'py-' . $top_bottom_padding_column_mobile;
                            } elseif ( $element_assignment === 'outer') {
                                $col_classes[] = 'py-' . $mobile_breakpoint . '-' . $top_bottom_padding_column;
                                $col_classes[] = 'py-' . $top_bottom_padding_column_mobile;
                            }
                        }
                        $default_column_left_right_padding = get_sub_field('default_column_left_right_padding');
                        if ( $default_column_top_bottom_padding && ( $default_column_top_bottom_padding === 'add' ) ) {

                            // get fields from global styles
                            $left_right_padding_column = get_field('left_right_padding_column', 'style');
                            $left_right_padding_column_mobile = get_field('left_right_padding_column_mobile', 'style');

                            // assign values if global styles empty
                            if ( !$left_right_padding_column ) {
                                $left_right_padding_column = '2';
                            }
                            if ( !$left_right_padding_column_mobile ) {
                                $left_right_padding_column_mobile = '2';
                            }

                            // assign spacing to column assignment element
                            if ( $element_assignment === 'inner') {
                                $col_inner_classes[] = 'px-' . $mobile_breakpoint . '-' . $left_right_padding_column;
                                $col_inner_classes[] = 'px-' . $left_right_padding_column_mobile;
                            } elseif ( $element_assignment === 'outer') {
                                $col_classes[] = 'px-' . $mobile_breakpoint . '-' . $left_right_padding_column;
                                $col_classes[] = 'px-' . $left_right_padding_column_mobile;
                            }
                        }

                        // custom spacing
                        $col_spacing = null;
                        $col_spacing = get_spacing_bbc(get_sub_field('column_spacing'), 'column');
                        if ( $element_assignment == 'outer') {
                            $col_classes[] = $col_spacing;
                        } else {
                            $col_inner_classes[] = $col_spacing;
                        }

                        // borders
                        $column_border_element = get_sub_field('column_border_element');
                        $borders = get_borders_bbc(get_sub_field('column_borders'));

                        if ( $borders && ( $column_border_element !== 'default' ) ) {
                            if ( $column_border_element === 'col-element' ) {
                                $col_classes[] = $borders;
                            } elseif ( $column_border_element === 'col-inner' ) {
                                $col_inner_classes[] = $borders;
                            } elseif ( $column_border_element === 'col-inner-content' ) {
                                $col_inner_content_classes[] = $borders;
                            }
                        } elseif ( $borders ) {
                            if ( $element_assignment == 'outer') {
                                $col_classes[] = $borders;
                            } else {
                                $col_inner_classes[] = $borders;
                            }
                        }
                        
                        // element-specific classes
                        $col_classes[] = trim(get_sub_field('column_element_classes'));
                        $col_inner_classes[] = trim(get_sub_field('column_inner_classes'));
                        $col_inner_content_classes[] = trim(get_sub_field('col_inner_content_classes'));

                        // additional classes and id
                        $additional_classes = get_sub_field('additional_classes');
                        if ( $additional_classes ) {
                            $col_classes[] = $additional_classes;
                        }
                        
                        // compile column classes
                        $col_classes = trim(implode(' ', $col_classes));
                        $col_inner_classes = trim(implode(' ', $col_inner_classes));
                        $col_inner_content_classes = trim(implode(' ', $col_inner_content_classes));
                        $col_styles = trim(implode(' ', $col_styles));
                        $col_inner_styles = trim(implode(' ', $col_inner_styles));
                        $col_inner_content_styles = trim(implode(' ', $col_inner_content_styles));

                        // determine if column is link
                        $col_tag = '<div class="'. $col_classes .'" style="'. $col_styles .'">';

                        $col_link = get_sub_field('column_link');
                        if ( $col_link ) { 
                            $js = null;
                            $url = $col_link['url'];
                            $target = $col_link['target'];
                            if ( $target === '_blank' ) {
                                $target = ' onclick="window.open('. $url .')' . '"';
                            } else {
                                $target = ' onclick="window.location.href=' . $url. '"';
                            }

                            $col_tag = '<div class="' . esc_attr($col_classes) . ' column-link" style="' . esc_attr($col_styles) . '" ' . $target . '>';
                        }

                        echo $col_tag; // col-element start

                            if ( $col_mobile_overlay ) {
                                echo $col_mobile_overlay;
                            }
                            if ( $col_video ) {
                                echo $col_video;
                            }
                            if ( $col_overlay ) {
                                echo $col_overlay;
                            }

                            echo '<div class="'. $col_inner_classes .'" style="'. $col_inner_styles .'">'; // col-inner start

                                if ( $col_inner_mobile_overlay ) {
                                    echo $col_inner_mobile_overlay;
                                }
                                if ( $col_inner_video ) {
                                    echo $col_inner_video;
                                }
                                if ( $col_inner_overlay) {
                                    echo $col_inner_overlay;
                                }

                                echo '<div class="'. $col_inner_content_classes .'" style="'. $col_inner_content_styles .'">'; // col-inner-content start

                                    if( have_rows('elements') ): // if elements start

                                        while ( have_rows('elements') ) : the_row(); // elements loop start

                                            // add elements
                                            include( __DIR__ . '../../../elements/heading.php');
                                            include( __DIR__ . '../../../elements/paragraph.php');
                                            include( __DIR__ . '../../../elements/buttons.php');
                                            include( __DIR__ . '../../../elements/image.php');
                                            include( __DIR__ . '../../../elements/icon-list.php');
                                            include( __DIR__ . '../../../elements/tabs.php');
                                            include( __DIR__ . '../../../elements/accordion.php');
                                            include( __DIR__ . '../../../elements/divider.php');
                                            include( __DIR__ . '../../../elements/form.php');
                                            include( __DIR__ . '../../../elements/html.php');
                                            include( __DIR__ . '../../../elements/carousel.php');
                                            include( __DIR__ . '../../../elements/modal.php');
                                            include( __DIR__ . '../../../elements/gallery.php');

                                        endwhile; // elements loop end

                                    endif; // if elements end

                                echo '</div>'; // col-inner-content end

                            echo '</div>'; // col-inner end

                        echo '</div>'; // col-element end

                        $col_count++; // increase column count

                    endwhile; // columns loop end

                endif; // if columns end

            echo '</div>'; // row end

        echo '</div>'; // container end

        // bottom divider
        $bottom_divider = get_field('bottom_divider');
        if ( $bottom_divider === 'enable' ) {
            echo get_dividers_bbc('bottom');
        }

    echo '</section>'; // container-wrapper end

} else { // if columns, add container end
    echo 'Please add columns';
}

?>