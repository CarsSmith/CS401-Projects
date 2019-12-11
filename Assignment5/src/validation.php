<?php namespace validation;

function user_update_errors($user) {
    $logger = \logging\Logger::getInstance();
    $logger->debug('validating user object');
    $errors = array();
    if (strlen($user->name) > 256) {
        $logger->warn('user name is too long');
        $errors['name'] = 'Too Long (at most 256 chars)';
    } else if (empty($user->name)) {
        $logger->warn('user name is missing');
        $errors['name'] = 'A name is required';
    }
    //TODO: Add checks for other user update sanitization/validation.
    return $errors;
}

function validate_post_create($values) {
    $uploadOk = 1;
    $logger = \logging\Logger::getInstance();
    $logger->debug("validating new post");
    $logger->debug(print_r($values, true));
    $errors = array();
    
    //Validating the title...
    if (strlen($values[0]) > 500) {
        $logger->warn('title is too long');
        $errors['title'] = 'Title is Too Long! (at most 500 chars)';
        $uploadOk = 0;
    } else if ($values[0] == "") {
        $logger->warn('title is missing');
        $errors['title'] = 'A title is required';
        $uploadOk = 0;
    } else if (empty($values[0])) {
        $logger->warn('title is missing');
        $errors['title'] = 'A title is required';
        $uploadOk = 0;
    }
    
    //Validating the image_url...
    if($_FILES["image_url"]["name"]) {
        $target_dir = "uploads/";
        $img_name = $_FILES['image_url']['tmp_name'];
        $target_file_loc = $target_dir . basename($_FILES["image_url"]["name"]);
        $uploadOk = 1;
        $check = getimagesize($_FILES["image_url"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $logger->warn('File is not an image.');
            $errors['image'] = 'File is not an image.';
            $uploadOk = 0;
        }
        
        //Validating file type to JPEG, JPG, PNG, or GIF - Working.
        if(exif_imagetype($img_name) != IMAGETYPE_GIF && exif_imagetype($img_name) != IMAGETYPE_PNG && exif_imagetype($img_name) != IMAGETYPE_JPEG) {
            $logger->warn('File is not a valid file type.');
            $logger->warn($target_file);
            $errors['image'] = 'Only jpeg, jpg, png, and gif files are allowed.';
            $uploadOk = 0;
        }
        
        //Validating the size of the image - It's working properly.
        $size = getimagesize($img_name);
        if($size[0] > 1000 || $size[1] > 1000) {
            $logger->warn('File is too wide or tall.');
            $logger->warn("SIZE HERE IDIOT: " . $size[0] . " AND " . $size[1]);
            $errors['image'] = 'Image is too big. 1000px by 1000px max.';
            $uploadOk = 0;
        }
        
        //
        if ($uploadOk == 1) {
            if (move_uploaded_file($img_name, $target_file_loc)) {
                $logger->debug("The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
            } else {
                $logger->warn('error uploading file to ' . $target_file_loc);
                $errors['image'] = 'There was some issue with uploading the image.';
            }
        }
    }
    return $errors;
}

function validate_comment_create($values) {
    $logger = \logging\Logger::getInstance();
    $logger->debug("Validating Comment Information");
    $logger->debug(print_r($values, true));
    $errors = array();
    
    if (strlen($values[0]) > 500) {
        $logger->warn('The text input is too long. (500 chars max).');
        $errors['text'] = 'The comment is too long! 500 characters max.';
    } else if(strlen($values[0]) == 0) {
        $logger->warn('There is no text.');
        $errors['text'] = 'There needs to be some text in the comment.';
    }
    
    return $errors;
}

function validate_user_signup_info($values) {
    $logger = \logging\Logger::getInstance();
    $logger->debug("Validating Post Information");
    $logger->debug(print_r($values, true));
    $errors = array();
    
    //Validating Email - values[0]
    if (strlen($values[0]) > 256) {
        $logger->warn('The email input is too long. (256 characters max.)');
        $errors['email'] = 'Email is too long! (256 characters max.)';
    } else if(strlen($values[0]) == 0) {
        $logger->warn('The email input is required.');
        $errors['email'] = 'The email input field is required.';
    } else if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $values[0])) {
        $logger->warn('The email input is not a valid email.');
        $errors['email'] = 'The email input is not a valid email.';
    }
    
    //Validating Password - values[2] and values[3]
    if ($values[2] != $values[3]) {
        $logger->warn('The two password inputs are not the same.');
        $errors['password'] = 'The two password inputs are not the same!';
    } else if(strlen($values[2]) < 8) {
        $logger->warn('The password is not long enough. (Min 8 Characters)');
        $errors['password'] = 'The password is not long enough. (Min 8 Characters)';
    } else if(!preg_match("#[a-zA-Z]+#", $values[2] )) {
        $logger->warn('The password must contain at least one letter!');
        $errors['password'] = 'The password must contain at least one letter!';
    } else if(!preg_match("#[0-9]+#", $values[2] )) {
        $logger->warn('The password must contain at least one number!');
        $errors['password'] = 'The password must contain at least one number!';
    }
    
    //Validating Name - values[1]
    if (strlen($values[1]) > 256) {
        $logger->warn('name input is too long! (256 characters max)');
        $errors['fname'] = 'The Username is too long. (256 characters max)';
    }
    
    //Validating Checkboxes just in case - values[4], values[5], values[6]
    if ($values[4] != 0 and $values[4] != 1) {
        $logger->warn("Something weird happened with the checkboxes1 regarding {$values[4]}");
        $errors['is_admin'] = 'Something Went Wrong.';
    } else if ($values[5] != 0 and $values[5] != 1) {
        $logger->warn('Something weird happened with the checkboxes2');
        $errors['is_moderator'] = 'Something Went Wrong.';
    } else if ($values[6] != 0 and $values[6] != 1) {
        $logger->warn('Something weird happened with the checkboxes3');
        $errors['is_banned'] = 'Something Went Wrong.';
    }
    $logger->warn("{$errors}");
    return $errors;
}