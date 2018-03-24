<?php
namespace src\mm;

class SessionStatus {

    public function __construct() {
        if(!isset($_SESSION)) {
            session_start();
        }
    }
    
    public function getSubjectSeq() {
        if (session_is_registered("SUBJECT_SEQ")) {
            return $_SESSION['SUBJECT_SEQ'];
        } else {
            header("Location: /account/Registration.htm");
            return 0;
        }
    }
    
    public function getManagerUserId() {
        if (session_is_registered("USER_ID")) {
            return $_SESSION['USER_ID'];
        } else {
            header("Location: /mgr/");
            return 0;
        }
    }
    
    
}

