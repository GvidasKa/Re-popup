<?php
global $table_prefix, $wpdb; // wordpress database

$table_name = $table_prefix . 'repopup';
$results = $wpdb->get_results("SELECT * FROM $table_name");
?>
<h1>RePopup</h1>
<div class="create--modal repop--modal">
    <div class="form">
        <h2>Create Popup</h2>
        <form method="POST" id="create--popup" class="row">
            <div class="popup--info">
                <label for="title">Title</label>
                <input type="text" name="title">
                <label for="text">Text</label>
                <textarea name="text"></textarea>
                <label for="status">Activate</label>
                <input type="checkbox" name="status">
                <input type="submit" name="submit" value="Submit" />
            </div>
            <div class="popup--image">
                <img src="/wp-content/plugins/re-popup/assets/images/remove--image.png" class="remove--image">
                <img src="/wp-content/plugins/re-popup/assets/images/upload-icon.png" class="upload--preview">
                <input type="file" accept="image/*"  name="image">
                <p>Drop files to upload</p>
            </div>
        </form>
    </div>
</div>
<div class="edit--modal repop--modal">
    <div class="form">
        <h2>Edit Popup</h2>
        <form method="POST" id="edit--popup" class="row">
            <div class="popup--info">
                <input type="hidden" value="" name="id">
                <label for="title">Title</label>
                <input type="text" name="title">
                <label for="text">Text</label>
                <textarea name="text"></textarea>
                <label for="status">Active</label>
                <input type="checkbox" name="status">
                <input type="submit" name="submit" value="Submit" />
            </div>
            <div class="popup--image">
                <img src="/wp-content/plugins/re-popup/assets/images/remove--image.png" class="remove--image">
                <img src="/wp-content/plugins/re-popup/assets/images/upload-icon.png" class="upload--preview">
                <input type="file" accept="image/*"  name="image">
                <p>Drop files to upload</p>
            </div>
        </form>
    </div>
</div>
<button class="create--popup">Create</button>
<button class="delete--popup" disabled>Delete</button>
<table style="width:100%" class="popups--table">
    <thead>
        <tr>
            <th></th>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($results as $result){
        echo "<tr>
            <td><input type=\"checkbox\"  tableid='{$result->ID}' class='delete'></td>
            <td>{$result->ID}</td>
            <td>{$result->title}</td>
            <td "; if($result->status == 1){echo 'class="status--active"';} else{echo 'class="status--disabled"';} echo ">"; if($result->status == 1){echo 'Active';} else {echo 'Disabled';} echo "</td>
            <td><button tableid='{$result->ID}'>Edit</button></td>
        </tr>";
    }?>
    </tbody>
</table>