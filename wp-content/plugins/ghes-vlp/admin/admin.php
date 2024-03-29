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
    register_setting('vlp-pages-admin-group', 'vlp-lessons');
    register_setting('vlp-pages-admin-group', 'vlp-select-child');
    register_setting('vlp-pages-admin-group', 'vlp-purchase');
    register_setting('vlp-pages-admin-group', 'vlp-manage');
    register_setting('vlp-pages-admin-group', 'vlp-past-payments');
    register_setting('vlp-pages-admin-group', 'vlp-payment-confirmation');
    register_setting('vlp-pages-admin-group', 'vlp-cancel-confirmation');
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
                <tr valign="middle">
                    <th class="table-header" scope="row">
                        Select VLP Browse Lessons Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-lessons" value="<?php echo esc_attr(get_option('vlp-lessons')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-lessons')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-lessons'))) ?></option>
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
                        Select VLP Select Child Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-select-child" value="<?php echo esc_attr(get_option('vlp-select-child')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-select-child')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-select-child'))) ?></option>
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
                        Select VLP Purchase Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-purchase" value="<?php echo esc_attr(get_option('vlp-purchase')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-purchase')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-purchase'))) ?></option>
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
                        Select VLP Subscription Management Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-manage" value="<?php echo esc_attr(get_option('vlp-manage')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-manage')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-manage'))) ?></option>
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
                        Select VLP Subscription Past Payments:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-past-payments" value="<?php echo esc_attr(get_option('vlp-past-payments')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-past-payments')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-past-payments'))) ?></option>
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
                        Select VLP Subscription Payment Confirmation Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-payment-confirmation" value="<?php echo esc_attr(get_option('vlp-payment-confirmation')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-payment-confirmation')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-payment-confirmation'))) ?></option>
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
                        Select VLP Subscription Cancellation Confirmation Page:
                    </th>
                    <td class="page-select">
                        <select required name="vlp-cancel-confirmation" value="<?php echo esc_attr(get_option('vlp-cancel-confirmation')); ?>">
                            <option value="<?php echo esc_attr(get_option('vlp-cancel-confirmation')); ?>">
                                <?php echo get_the_title(esc_attr(get_option('vlp-cancel-confirmation'))) ?></option>
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