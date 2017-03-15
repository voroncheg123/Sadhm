<?php
/**
 * Implemention of Custom Header 
 */

/**
 * Set up the WordPress core custom header settings.
 *
 */
function gardenia_custom_header_setup() {
    /**
     *     @param array $args {
     *     An array of custom-header support arguments.
     *
     *     @type bool   $header_text            Whether to display custom header text. Default false.
     *     @type int    $width                  Width in pixels of the custom header image. Default 1260.
     *     @type int    $height                 Height in pixels of the custom header image. Default 240.
     *     @type bool   $flex_height            Whether to allow flexible-height header images. Default true.
     *     @type string $admin_head_callback    Callback function used to style the image displayed in
     *                                          the Appearance > Header screen.
     *     @type string $admin_preview_callback Callback function used to create the custom header markup in
     *                                          the Appearance > Header screen.
     * }
     */
    add_theme_support('custom-header', apply_filters('gardenia_custom_header_args', array(
        'default-text-color' => '000',
        'width' => 1260,
        'height' => 240,
        'flex-height' => true,
        'wp-head-callback' => 'gardenia_header_style',
        'admin-head-callback' => 'gardenia_admin_header_style',
        'admin-preview-callback' => 'gardenia_admin_header_image',
    )));
}

add_action('after_setup_theme', 'gardenia_custom_header_setup');

if (!function_exists('gardenia_header_style')) :

    /**
     * Styles the header image and text displayed on the blog
     *
     * @see gardenia_custom_header_setup().
     *
     */
    function gardenia_header_style() {
        $gardenia_text_color = get_header_textcolor();

        // If no custom color for text is set, let's bail.
        if (display_header_text() && $gardenia_text_color === get_theme_support('custom-header', 'default-text-color'))
            return;

        // If we get this far, we have custom styles.
        ?>
        <style type="text/css" id="gardenia-header-css">
        <?php
        // Has the text been hidden?
        if (!display_header_text()) :
            ?>
                .home-link,
                .logo p {
                    clip: rect(1px 1px 1px 1px); /* IE7 */
                    clip: rect(1px, 1px, 1px, 1px);
                    position: absolute;
                }
            <?php
        // If the user has set a custom color for the text, use that.
        elseif ($gardenia_text_color != get_theme_support('custom-header', 'default-text-color')) :
            ?>
                .site-title a,
                .logo p {
                    color: #<?php echo esc_attr($gardenia_text_color); ?>;
                }
        <?php endif; ?>
        </style>
        <?php
    }

endif; // gardenia_header_style


if (!function_exists('gardenia_admin_header_style')) :

    /**
     * Style the header image displayed on the Appearance > Header screen.
     *
     * @see gardenia_custom_header_setup()
     */
    function gardenia_admin_header_style() {
        ?>
        <style type="text/css" id="gardenia-admin-header-css">
            .appearance_page_custom-header #headimg {
                background-color: #000;
                border: none;
                max-width: 1260px;
                min-height: 48px;
            }
            #headimg h1 {
                font-family: Lato, sans-serif;
                font-size: 18px;
                line-height: 48px;
                margin: 0 0 0 30px;
            }
            #headimg h1 a {
                color: #000;
                text-decoration: none;
            }
            #headimg img {
                vertical-align: middle;
            }
        </style>
        <?php
    }

endif; // gardenia_admin_header_style

if (!function_exists('gardenia_admin_header_image')) :

    /**
     * Create the custom header image markup displayed on the Appearance > Header screen.
     *
     * @see gardenia_custom_header_setup()
     */
    function gardenia_admin_header_image() {
        ?>
        <div id="headimg">
        <?php if (get_header_image()) : ?>
                <img src="<?php header_image(); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
        <?php endif; ?>
            <h1 class="displaying-header-text"><a id="name"<?php echo sprintf(' style="color:#%s;"', get_header_textcolor()); ?> onclick="return false;" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
        </div>
        <?php
    }


endif; // gardenia_admin_header_image
