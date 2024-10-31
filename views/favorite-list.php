<div id="favorite-list">
    <?php $categories = $data->categories();
    foreach ($categories as $category) { ?>
        <div class="favorite-category">
            <h3><?php echo $category->name; ?><span onclick="remove('category', <?php echo "'$category->name', $category->id" ?>)" class="mfl-remove-link">x</span></h3>
            <ul>
                <?php $list = $data->links($category->id);
                foreach ($list as $link) {
                    echo "<li><a href='$link->site' target='_blank'>$link->name</a><span onclick='remove(\"link\", \"$link->name\", $link->id)' class='mfl-remove-link'>x</span></li>";
                } ?>
            </ul>
        </div>
    <?php } ?>
</div>

<form action="" method="POST" id="mfl-action-form">
    <input type="hidden" name="id">
    <input type="hidden" name="name">
    <input type="hidden" name="action">
    <?php wp_nonce_field('mfl-wp-action-remove', 'mfl-wp-field-remove'); ?>
</form>

<script>
    const actionForm = document.getElementById('mfl-action-form');

    function remove(action, name, id) {
        let msg = confirm(`You're about to remove the ${action} '${name}', are you agree?`);
        if (msg == true) {
            actionForm.elements["id"].value = id;
            actionForm.elements["name"].value = name;
            actionForm.elements["action"].value = "del_" + action;
            actionForm.submit();
        }
    }
</script>