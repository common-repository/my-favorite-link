<?php

function sanitize(string $data)
{
    return strip_tags(
        stripslashes(
            sanitize_text_field(
                filter_input(INPUT_POST, $data)
            )
        )
    );
}

if (!empty($_POST)) {
    $error = false;
    $class = "success";
    $action = sanitize_key($_POST['action']);
    $message = "";

    switch ($action) {
        case 'new_link':
            if (
                !isset($_POST['mfl-wp-field-add-link'])
                || !wp_verify_nonce($_POST['mfl-wp-field-add-link'], 'mfl-wp-action-add-link')
            ) {
                $error = true;
            } else {
                $data->insert_link(
                    sanitize('name'),
                    esc_url_raw($_POST['site']),
                    sanitize('category')
                );
                $message = sprintf(__('Link %s added', 'my-favorite-link'), sanitize('name'));
            }
            break;

        case 'new_category':
            if (
                !isset($_POST['mfl-wp-field-add-cat'])
                || !wp_verify_nonce($_POST['mfl-wp-field-add-cat'], 'mfl-wp-action-add-cat')
            ) {
                $error = true;
            } else {
                $data->insert_cat(sanitize('name'));
                $message = sprintf(__('Category %s added', 'my-favorite-link'), sanitize('name'));
            }
            break;

        case 'del_link':
            if (
                !isset($_POST['mfl-wp-field-remove'])
                || !wp_verify_nonce($_POST['mfl-wp-field-remove'], 'mfl-wp-action-remove')
            ) {
                $error = true;
            } else {
                $data->remove_link(intval($_POST['id']));
                $message = sprintf(__('Link %s removed', 'my-favorite-link'), sanitize('name'));
            }
            break;

        case 'del_category':
            if (
                !isset($_POST['mfl-wp-field-remove'])
                || !wp_verify_nonce($_POST['mfl-wp-field-remove'], 'mfl-wp-action-remove')
            ) {
                $error = true;
            } else {
                $data->remove_cat(intval($_POST['id']));
                $message = sprintf(__('Category %s removed', 'my-favorite-link'), sanitize('name'));
            }
            break;
    }

    if ($error === true) {
        $class = "danger";
        $message = __('Error processing form, please try again', 'my-favorite-link');
    } ?>

    <div id="mfl-notification" class="<?php esc_html_e($class) ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <?php _e($message); ?>
    </div>

<?php } ?>

<div id="mfl-control">
    <div>
        <button id="favorite-form-link" class="mfl-button"><?php _e('Add Link', 'my-favorite-link') ?></button>
    </div>
    <div>
        <button id="favorite-form-cat" class="mfl-button"><?php _e('Add Category', 'my-favorite-link') ?></button>
    </div>
</div>

<div class="mfl-form" id="mfl-favorite-form-link">
    <?php $list = $data->categories();
    if (count($list) > 0) { ?>
        <form action="" method="POST">
            <?php wp_nonce_field('mfl-wp-action-add-link', 'mfl-wp-field-add-link'); ?>

            <label for="name"><?php _e('Link Name', 'my-favorite-link') ?></label>
            <input type="text" id="name" name="name" required />

            <label for="site"><?php _e('Link Url', 'my-favorite-link') ?></label>
            <input type="url" id="site" name="site" placeholder="https://" required />

            <label for="category"><?php _e('Category', 'my-favorite-link') ?></label>
            <select id="category" name="category">
                <?php $list = $data->categories();
                foreach ($list as $category) {
                    echo '<option value="' . $category->id . '">' . $category->name . '</option>';
                } ?>
            </select>

            <input type="hidden" name="action" value="new_link">
            <input type="submit" class="mfl-button" value="<?php _e('Save', 'my-favorite-link') ?>">
        </form>
    <?php } else { ?>
        <h4><?php _e('You must create some category before add a link', 'my-favorite-link'); ?></h4>
    <?php } ?>
</div>

<div class="mfl-form" id="mfl-favorite-form-cat">
    <form action="" method="POST">
        <?php wp_nonce_field('mfl-wp-action-add-cat', 'mfl-wp-field-add-cat'); ?>

        <label for="name"><?php _e('Category Name', 'my-favorite-link') ?></label>
        <input type="text" id="name" name="name" required />

        <input type="hidden" name="action" value="new_category">
        <input type="submit" class="mfl-button" value="<?php _e('Save', 'my-favorite-link') ?>">
    </form>
</div>

<script>
    document.getElementById("favorite-form-link").addEventListener('click', function() {
        slideUp(document.getElementById("mfl-favorite-form-cat"), 200);
        slideToggle(document.getElementById("mfl-favorite-form-link"), 200);
    });

    document.getElementById("favorite-form-cat").addEventListener('click', function() {
        slideUp(document.getElementById("mfl-favorite-form-link"), 200);
        slideToggle(document.getElementById("mfl-favorite-form-cat"), 200);
    });
</script>