  
<?php
function search_directory($file_name, $search_for) {

    $search_for = strtolower($search_for);
    $results = [];
    $file_array = file($file_name);
    foreach($file_array as $line) {
        $exploded_line = explode(",", $line);
        $exploded_name = strtolower($exploded_line[1]);
        $exploded_email = strtolower($exploded_line[2]);
        if(strpos($exploded_name, $search_for) !== false) {
            $results[] = $exploded_email;
        } else if(strpos($exploded_email, $search_for) !== false) {
            $results[] = $exploded_email;
        }
    }
    return $results; // TODO search the directory as described in the writeup
}