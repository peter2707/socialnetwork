<?php

if(!empty($row['user_picture'])) {
    $image_url = $row['user_picture'];
    echo '<img src="' . $image_url . '" width="' . $width . '" height="' . $height .'">'; 
} else {
    $target = glob("data/images/profiles/" . $row['user_id'] . ".*");
    if($target) {
        echo '<img src="' . $target[0] . '" width="' . $width . '" height="' . $height .'">'; 
    } else {
        if($row['user_gender'] == 'M') {
            echo '<img src="data/images/profiles/M.jpg" width="' . $width . '" height="' . $height .'">';
        } else if ($row['user_gender'] == 'F') {
            echo '<img src="data/images/profiles/F.jpg" width="' . $width . '" height="' . $height .'">';
        }
    }
}

?>