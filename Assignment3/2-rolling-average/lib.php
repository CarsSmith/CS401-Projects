<?php
class User {
    function __construct($email) {
        $this->email = $email;
    }
}
function rolling_averages($users, $activity) {
    $rolling_avgs = [];
    foreach($activity as $name => $datalist) { //For each activity, takes the names and the data...
        foreach($users as $user) {
            if($user->email === $name) {
                $results = [];
                for($i = 0; $i < (count($datalist) - 1); $i++) {
                    $impressions = 0;
                    $clicks = 0;
                    $impressions += $datalist[$i][0];
                    $impressions += $datalist[$i+1][0];
                    $clicks += $datalist[$i][1];
                    $clicks += $datalist[$i+1][1];
                    $average = number_format(($clicks/$impressions), 2);
                    $results[] = $average;
                }
                $rolling_avgs += [$name=>$results];
            }
        }
    }
    return $rolling_avgs;
}