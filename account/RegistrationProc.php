<?php
// include_once($_SERVER["DOCUMENT_ROOT"].'/src/GlobalEnv.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SubjectDAO.php');

//htmlspecialchars

$uid = 0;
if (isset($_POST["schoolcd"]) && isset($_POST["grade"]) && isset($_POST["gendercd"])) {
    $schoolcd       = substr($_POST["schoolcd"], 0, 1);
    $grade          = substr($_POST["grade"],    0, 1);
    $gendercd       = substr($_POST["gendercd"], 0, 1);
    $dao = new src\mm\SubjectDAO();
    $uid = $dao->regist($schoolcd, $grade, $gendercd);
}
echo $uid;
?>