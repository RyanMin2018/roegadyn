<?php

include_once($_SERVER["DOCUMENT_ROOT"].'/src/GlobalEnv.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');

$lo  = new src\mm\SessionStatus();
$who = $lo->getSubjectSeq();
if ($who>0) {
    // 
} else {
    header("Location: /account/Registration.htm");
    exit();
}

?>