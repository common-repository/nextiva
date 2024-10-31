<?php
/**
 * Plugin Name:       Nextiva
 * Plugin URI:        https://www.nextiva.com/
 * Description:       Add your Nextiva script easily on the site.
 * Version:           1.0.0
 * Author:            Nextiva
 * Author URI:        https://www.nextiva.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nextiva
 * Domain Path:       /languages
**/

if (! defined('ABSPATH')) {
    exit;
}

class Nextiva
{
    private $nextiva_options;

    public function __construct()
    {
        add_action('admin_menu', array( $this, 'nextiva_add_plugin_page' ));
        add_action('admin_init', array( $this, 'nextiva_page_init' ));
    }

    public function nextiva_add_plugin_page()
    {
        add_menu_page(
            __('Nextiva', 'nextiva'), // page_title
            __('Nextiva', 'nextiva'), // menu_title
            'manage_options', // capability
            'nextiva', // menu_slug
            array( $this, 'nextiva_create_admin_page' ), // function
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzYiIGhlaWdodD0iMzYiIHZpZXdCb3g9IjAgMCAzNiAzNiIgZmlsbD0iI2ZmZiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KCQkJPHBhdGggZD0iTTI3Ljg0NjMgMzMuMDA3MkMyNS45OTY0IDMzLjAwNzIgMjQuMjM4NCAzMy4wMDcyIDIyLjQ4MDMgMzIuOTk0OEMyMi4yOTQxIDMyLjk1OTUgMjIuMTI5MyAzMi44NTIzIDIyLjAyMTQgMzIuNjk2NUMyMC45NDY0IDMxLjI3MDMgMTkuODY5NyAyOS44Mzg4IDE4Ljc5NjUgMjguNDE3OUMxOC43MDQ3IDI4LjI5NjEgMTguNjA5NCAyOC4xNzYgMTguNDg1OCAyOC4wMTU0QzE4LjE0NjkgMjguNDYyIDE3LjgzOTggMjguODY0NCAxNy41MzYyIDI5LjI3MDRDMTYuNjY3OCAzMC40MjQ4IDE1LjgwNjQgMzEuNTg2MyAxNC45MjM4IDMyLjczMThDMTQuODY1OCAzMi44MDQ1IDE0Ljc5NCAzMi44NjUgMTQuNzEyNSAzMi45MDk4QzE0LjYzMSAzMi45NTQ2IDE0LjU0MTUgMzIuOTgyOSAxNC40NDkgMzIuOTkzMUMxMi43MDMzIDMzLjAwNzIgMTAuOTU5NCAzMy4wMDcyIDkuMTIwMTIgMzMuMDA3MkM5LjI0MzY4IDMyLjgzMDcgOS4zMjg0IDMyLjY5MyA5LjQyMzcyIDMyLjU2NzdDMTEuNDI0MiAyOS45MDM1IDEzLjQyNDcgMjcuMjQyMyAxNS40MjUxIDI0LjU4NEMxNS42MzM0IDI0LjMwODcgMTUuNjAxNiAyNC4xNDYzIDE1LjQwNzUgMjMuODk1NkMxMy40MjgyIDIxLjI3MDMgMTEuNDUzNiAxOC42NDAzIDkuNDgzNzMgMTYuMDA1NUM5LjM3NzgyIDE1Ljg2MjYgOS4yNzU0NSAxNS43MTYxIDkuMTMwNzEgMTUuNTE2NkM5LjMzNzIzIDE1LjUwNiA5LjQ3MTM4IDE1LjQ5NTQgOS42MDU1MyAxNS40OTM3QzExLjE1MzUgMTUuNDkzNyAxMi42OTk4IDE1LjUwNiAxNC4yNDYgMTUuNDgxM0MxNC40MDQ2IDE1LjQ3MDQgMTQuNTYzMSAxNS41MDMxIDE0LjcwNDQgMTUuNTc1OUMxNC44NDU2IDE1LjY0ODggMTQuOTY0MyAxNS43NTg5IDE1LjA0NzQgMTUuODk0M0MxNi4wODg4IDE3LjMwNjQgMTcuMTUzMiAxOC43MTg1IDE4LjIwODcgMjAuMTEzQzE4LjI4NDYgMjAuMjEzNiAxOC4zNjc2IDIwLjMxMDcgMTguNDg1OCAyMC40NjZDMTguNjA0MSAyMC4zMTc3IDE4LjcwODMgMjAuMTk0MiAxOC44MDUzIDIwLjA2NTNDMTkuODY0NCAxOC42NTMyIDIwLjkzNzYgMTcuMjQxMSAyMS45OTMxIDE1LjgxMzFDMjIuMDYwOCAxNS43MDg2IDIyLjE1NDkgMTUuNjIzOSAyMi4yNjU5IDE1LjU2NzVDMjIuMzc2OCAxNS41MTEgMjIuNTAwOCAxNS40ODUgMjIuNjI1MSAxNS40OTE5QzI0LjIxMzcgMTUuNTA3OCAyNS44MDIzIDE1LjQ5MTkgMjcuMzkwOSAxNS40OTE5QzI3LjUxMjcgMTUuNDkxOSAyNy42MzQ1IDE1LjUwNDIgMjcuODMyMiAxNS41MTMxQzI3LjcwNTEgMTUuNjg5NiAyNy42MTg2IDE1LjgzNjEgMjcuNTE5NyAxNS45NjY3QzI1LjU0MDQgMTguNjA4NSAyMy41NTU5IDIxLjI0NzQgMjEuNTY2IDIzLjg4MzNDMjEuMzU5NSAyNC4xNTY5IDIxLjM1MjQgMjQuMzIyOCAyMS41NjYgMjQuNjAzNEMyMy41OTQxIDI3LjI2NyAyNS41OTIyIDI5Ljk0NjUgMjcuNjAwOSAzMi42MjA2QzI3LjY3MzMgMzIuNzE3NyAyNy43MzMzIDMyLjgzMDcgMjcuODQ2MyAzMy4wMDcyWiIgZmlsbD0iI2ZmZiIvPgoJCQk8cGF0aCBkPSJNMTguNDc1NiAxNC4yMzg1QzE1LjM2NzIgMTQuMjM4NSAxMi44MjcyIDExLjY5MTUgMTIuODQ0OCA4LjYxNDg1QzEyLjg2MjUgNS41MzgyNCAxNS4zNzI1IDMgMTguNDAxNCAzQzIxLjU5MSAzIDI0LjEyMzkgNS40OTA1OSAyNC4xMjM5IDguNjIwMTVDMjQuMTIyNiA5LjM2MDEgMjMuOTc1MyAxMC4wOTI1IDIzLjY5MDYgMTAuNzc1NUMyMy40MDU5IDExLjQ1ODUgMjIuOTg5MyAxMi4wNzg2IDIyLjQ2NDcgMTIuNjAwNUMyMS45NCAxMy4xMjIzIDIxLjMxNzcgMTMuNTM1NiAyMC42MzMyIDEzLjgxNjZDMTkuOTQ4NyAxNC4wOTc3IDE5LjIxNTUgMTQuMjQxMSAxOC40NzU2IDE0LjIzODVWMTQuMjM4NVoiIGZpbGw9IiNmZmYiLz4KCQkJPC9zdmc+',
            75 // position
        );
    }

    public function nextiva_create_admin_page()
    {
        $this->nextiva_options = get_option('nextiva_option'); ?>

        <div class="wrap">
            <h2><?php esc_html_e('Nextiva', 'nextiva');?></h2>
            <div class="nextiva-img-cont" style="text-align: center; width: 100%; height: 440px; overflow: hidden;">
                <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/img/nextiva.png'); ?>" style="width: 100%; object-fit: cover; height: 100%;">
            </div>
            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                    settings_fields('nextiva_option_group');
                    do_settings_sections('nextiva-admin');
                    submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function nextiva_page_init()
    {
        register_setting(
            'nextiva_option_group', // option_group
            'nextiva_option', // option_name
            array( $this, 'nextiva_sanitize' ) // sanitize_callback
        );

        add_settings_section(
            'nextiva_setting_section', // id
            __('Settings', 'nextiva'), // title
            array( $this, 'nextiva_section_info' ), // callback
            'nextiva-admin' // page
        );

        add_settings_field(
            'nextiva_enable_nextiva', // id
            __('Enable', 'nextiva'), // title
            array( $this, 'nextiva_enable_nextiva_callback' ), // callback
            'nextiva-admin', // page
            'nextiva_setting_section' // section
        );

        add_settings_field(
            'nextiva_simplifychat_config_id', // id
            __('Config ID', 'nextiva'), // title
            array( $this, 'nextiva_simplifychat_config_id_callback' ), // callback
            'nextiva-admin', // page
            'nextiva_setting_section' // section
        );

        add_settings_field(
            'nextiva_simplifychat_url', // id
            __('URL', 'nextiva'), // title
            array( $this, 'nextiva_simplifychat_url_callback' ), // callback
            'nextiva-admin', // page
            'nextiva_setting_section' // section
        );
    }

    public function nextiva_sanitize($input)
    {
        $sanitary_values = array();

        // Validate Config ID
        if (isset($input['nextiva_simplifychat_config_id']) && !empty($input['nextiva_simplifychat_config_id'])) {
            $sanitary_values['nextiva_simplifychat_config_id'] = sanitize_text_field($input['nextiva_simplifychat_config_id']);
        } else {
            add_settings_error(
                'nextiva_simplifychat_config_id',
                'empty_config_id',
                __('Config ID is required.', 'nextiva'),
                'error'
            );
        }

        // Validate URL
        if (isset($input['nextiva_simplifychat_url']) && !empty($input['nextiva_simplifychat_url'])) {
            $sanitary_values['nextiva_simplifychat_url'] = esc_url_raw($input['nextiva_simplifychat_url']);
        } else {
            add_settings_error(
                'nextiva_simplifychat_url',
                'empty_url',
                __('URL is required.', 'nextiva'),
                'error'
            );
        }

        // Enable Checkbox
        $sanitary_values['nextiva_enable_nextiva'] = isset($input['nextiva_enable_nextiva']) ? true : false;

        return $sanitary_values;
    }

    public function nextiva_section_info()
    {
    }

    public function nextiva_enable_nextiva_callback()
    {
        ?>
        <input type="checkbox" name="nextiva_option[nextiva_enable_nextiva]" id="nextiva_enable_nextiva" <?php echo isset($this->nextiva_options['nextiva_enable_nextiva']) && $this->nextiva_options['nextiva_enable_nextiva'] ? 'checked="checked"' : ''; ?>>
        <?php
    }

    public function nextiva_simplifychat_config_id_callback()
    {
        ?>
        <input class="regular-text" type="text" name="nextiva_option[nextiva_simplifychat_config_id]" id="nextiva_simplifychat_config_id" value="<?php echo isset($this->nextiva_options['nextiva_simplifychat_config_id']) ? esc_attr($this->nextiva_options['nextiva_simplifychat_config_id']) : ''; ?>">
        <?php
    }

    public function nextiva_simplifychat_url_callback()
    {
        ?>
        <input class="regular-text" type="text" name="nextiva_option[nextiva_simplifychat_url]" id="nextiva_simplifychat_url" value="<?php echo isset($this->nextiva_options['nextiva_simplifychat_url']) ? esc_url($this->nextiva_options['nextiva_simplifychat_url']) : ''; ?>">
        <?php
    }
}

if (is_admin()) {
    $nextiva = new Nextiva();
}

add_action('wp_head', 'nextiva_script');
function nextiva_script()
{
    $nextiva_option = get_option('nextiva_option');

    if (isset($nextiva_option['nextiva_enable_nextiva']) && $nextiva_option['nextiva_enable_nextiva']) {
        if (isset($nextiva_option['nextiva_simplifychat_url']) && !empty($nextiva_option['nextiva_simplifychat_url']) && isset($nextiva_option['nextiva_simplifychat_config_id']) && !empty($nextiva_option['nextiva_simplifychat_config_id'])) {
            $url = esc_url($nextiva_option['nextiva_simplifychat_url'] . '?key=' . $nextiva_option['nextiva_simplifychat_config_id']); ?>
            <script src="<?php echo $url; ?>"></script>
        <?php }
    }
}
