<?php
global $table_prefix, $wpdb; // wordpress database

$table_name = $table_prefix . 'repopup';
$results = $wpdb->get_results("SELECT * FROM $table_name");
?>
<h1>RePopup</h1>
<div class="create--modal">
    <div class="form">
        <h2>Create Popup</h2>
        <form method="POST" id="create--popup">
            <input type="text" name="title">
            <input type="file" name="image">
            <textarea name="text"></textarea>
            <input type="submit" name="submit" value="Submit" />
        </form>
    </div>
</div>
<button class="create--popup">Create</button>
<table style="width:100%">
    <thead>
        <tr>
            <th></th>
            <th>id</th>
            <th>title</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($results as $result){
        echo "<tr>
            <td><input type=\"checkbox\"></td>
            <td>{$result->ID}</td>
            <td>{$result->title}</td>
            <td><button>Edit</button></td>
        </tr>";
    }?>
    </tbody>
</table>