<?php

use GHES\Utils;

function register_vlp_admin_settings()
{
    register_setting('vlp-pages-admin-group', 'vlp-dbhost');
    register_setting('vlp-pages-admin-group', 'vlp-dbport');
    register_setting('vlp-pages-admin-group', 'vlp-dbuser');
    register_setting('vlp-pages-admin-group', 'vlp-dbpassword', 'encryptsetting');
    register_setting('vlp-pages-admin-group', 'vlp-dbname');
    register_setting('vlp-pages-admin-group', 'vlp-gameboard');
    register_setting('vlp-pages-admin-group', 'vlp-themes');
    register_setting('vlp-pages-admin-group', 'vlp-agetree');
}
add_action('admin_init', 'register_vlp_admin_settings');

function encryptvlpsetting($setting)
{
    return Utils::encrypt($setting);
}

function vlp_page_admin()
{
?>
    <div class="wrap">
        <h1>Virtual Learning Platform Admin Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('vlp-pages-admin-group'); ?>
            <?php do_settings_sections('vlp-pages-admin-group'); ?>
            <table>
                <th class="table-header table-section-header" scope="row">
                    Database Information:
                </th>
                </tr>
                <tr>
                    <th class="table-header" scope="row">
                        Database Name:
                    </th>
                    <td>
                        <input required name="vlp-dbname" value="<?php echo Utils::getunencryptedsetting('vlp-dbname'); ?>">
                    </td>
                </tr>
                <tr>
                    <th class="table-header" scope="row">
                        Database User:
                    </th>
                    <td>
                        <input required name="vlp-dbuser" value="<?php echo Utils::getunencryptedsetting('vlp-dbuser'); ?>">
                    </td>
                </tr>
                <tr>
                    <th class="table-header" scope="row">
                        Database Password:
                    </th>
                    <td>
                        <input required name="vlp-dbpassword" value="<?php echo Utils::getencryptedsetting('vlp-dbpassword'); ?>">
                    </td>
                </tr>
                <tr>
                    <th class="table-header" scope="row">
                        Database Host:
                    </th>
                    <td>
                        <input required name="vlp-dbhost" value="<?php echo Utils::getunencryptedsetting('vlp-dbhost'); ?>">
                    </td>
                </tr>
                <tr>
                    <th class="table-header" scope="row">
                        Database Port:
                    </th>
                    <td>
                        <input required name="vlp-dbport" value="<?php echo Utils::getunencryptedsetting('vlp-dbport'); ?>">
                    </td>
                </tr>
                <tr valign="middle">
                    <th class="table-header" scope="row">
                        Select VLP Gameboard Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-gameboard" value="<?php echo esc_attr(get_option('vlp-gameboard')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-gameboard')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-gameboard'))) ?></option>
                            <?php
                            $pages = get_pages();
                            foreach ($pages as $page) {
                                $option = '<option value="' . $page->ID . '">';
                                $option .= $page->post_title;
                                $option .= '</option>';
                                echo $option;
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="middle">
                    <th class="table-header" scope="row">
                        Select VLP Browse Themes Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-themes" value="<?php echo esc_attr(get_option('vlp-themes')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-themes')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-themes'))) ?></option>
                            <?php
                            $pages = get_pages();
                            foreach ($pages as $page) {
                                $option = '<option value="' . $page->ID . '">';
                                $option .= $page->post_title;
                                $option .= '</option>';
                                echo $option;
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="middle">
                    <th class="table-header" scope="row">
                        Select VLP Age Tree Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-agetree" value="<?php echo esc_attr(get_option('vlp-agetree')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-agetree')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-agetree'))) ?></option>
                            <?php
                            $pages = get_pages();
                            foreach ($pages as $page) {
                                $option = '<option value="' . $page->ID . '">';
                                $option .= $page->post_title;
                                $option .= '</option>';
                                echo $option;
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}
?>