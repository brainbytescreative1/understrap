<?php

if( get_row_layout() == 'paragraph' ):
                
    // content
    $text = get_sub_field('text');

    if ( $text ) {

        $classes = [];
        $classes[] = 'text-wrapper';
        $classes[] = 'element';

        // style
        $remove_margin = get_sub_field('remove_margin_from_last_paragraph');
        if( $remove_margin && in_array('Remove', $remove_margin) ) {
            $classes[] = 'no-margin-bottom';
        }

        $classes[] = get_text_styles_bbc(get_sub_field('text_styles'));

        $classes[] = get_spacing_bbc(get_sub_field('paragraph_spacing'));

        $additional_classes = get_sub_field('additional_classes');
        if ( $additional_classes ) {
            $classes[] = $additional_classes;
        }

        $classes = trim(implode(' ', $classes));

        ?>
        <div class="<?=$classes;?>">
            <?=$text;?>
        </div>
        <?php

    }

    echo "\r\n";

endif;