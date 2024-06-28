<?php

if( get_row_layout() == 'image' ):
                                        
    $image = get_sub_field('image');
    
    if( $image ):

        $classes = [];
        $styles = [];

        $classes[] = 'img';
        $classes[] = 'element';

        $link_wrapper_tag = 'div';
        $image_link = get_sub_field('image_link');

        // size
        $size = get_sub_field('image_size');
        $size_max_width = '1024px';
        switch ($size) {
            case '2048x2048':
                $size_max_width = '1920px';
                break;
            case 'large':
                $size_max_width = '1024px';
                break;
            case 'medium_large':
                $size_max_width = '768px';
                break;
            case 'medium':
                $size_max_width = '300px';
                break;
            case 'thumbnail':
                $size_max_width = '150px';
                break;
        }

        // alt text and title
        $image_alt = get_post_meta($image, '_wp_attachment_image_alt', TRUE);
        $image_title = get_the_title($image);
        if ( get_sub_field('alt_text_override') ) {
            $image_alt = get_sub_field('alt_text_override');
        }

        if ( $image_link ) {

            $title = $image_link['title'];
            $url = $image_link['url'];
            $target = $image_link['target'];
            if ( $target ) {
                $target = ' target="' . $target . ' "';
            }

            $link_wrapper_tag = 'a '. $target .' href="'. $url .'"';

            $styles[] = 'max-width: ' . $size_max_width;

        }

        // image settings
        $image_alignment = get_sub_field('image_alignment');
        if ( $image_alignment ) {
            if ( $image_alignment == 'left' ) {
                $classes[] = 'align-left';
                $classes[] = 'me-auto';
            } elseif ( $image_alignment == 'center' ) {
                $classes[] = 'align-center';
                $classes[] = 'ms-auto';
                $classes[] = 'me-auto';
            } elseif ( $image_alignment == 'right' ) {
                $classes[] = 'align-right';
                $classes[] = 'ms-auto';
            }
        }

        $image_bottom_margin = get_sub_field('image_bottom_margin');
        if ( $image_bottom_margin && ( $image_bottom_margin === 'disabled' ) ) {
            $classes[] = 'mb-0';
        }

        $force_full_width = get_sub_field('force_full_width');
        if ( $force_full_width === 'yes' ) {
            $classes[] = 'force-full-width';
        }

        $force_full_width_tablet = get_sub_field('force_full_width_tablet');
        if ( $force_full_width_tablet === 'yes' ) {
            $classes[] = 'force-full-width-tablet';
        }

        $force_full_width_mobile = get_sub_field('force_full_width_mobile');
        if ( $force_full_width_mobile === 'yes' ) {
            $classes[] = 'force-full-width-mobile';
        }

        $max_width = get_sub_field('max_width');
        if ( $max_width && ( $max_width['value'] ) ) {
            $size_max_width = $max_width['value'] . $max_width['unit'];
        }

        $max_height = get_sub_field('max_height');
        if ( $max_height && ( $max_height['value'] ) ) {
            $styles[] = 'max-height: ' . $max_height['value'] . $max_height['unit'] . ';';
            $classes[] = 'image-max-height';
        }

        $classes[] = get_spacing_bbc(get_sub_field('image_spacing'));
        $classes[] = get_responsive_bbc('responsive');

        $additional_classes = get_sub_field('additional_classes');
        if ( $additional_classes ) {
            $classes[] = trim($additional_classes);
        }
        
        // add max width to wrapper
        $image_attributes = wp_get_attachment_image_src( $image, 'full' );
        $image_src_max_width = $image_attributes[1];
        if ( $image_src_max_width ) {
            $styles[] = 'max-width: ' . $image_src_max_width . 'px;';
        }

        $classes = trim(implode(' ', $classes));
        $styles = trim(implode(' ', $styles));

        echo '<'.$link_wrapper_tag.' class="'. $classes .'" style="'. $styles .'">';
            ?>
            <img fetchpriority="lazy" decoding="async" <?php get_responsive_image_bbc($image, $size, $size_max_width); ?>  alt="<?=$image_alt?>" />
            <?php
        echo '</'.$link_wrapper_tag.'>';
    
    endif;

endif;