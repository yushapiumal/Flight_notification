<?php
    session_start();
    require_once('config/config.inc.php');
    require_once('CMS/cms.class.php');
    $cms = new cms();


    function getRandomStringRand($length = 8)
    {
        $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringLength = strlen($stringSpace);
        $randomString = '';
        for ($i = 0; $i < $length; $i ++) {
            $randomString = $randomString . $stringSpace[rand(0, $stringLength - 1)];
        }
        return $randomString;
    }

    if(isset($_POST['doContentCreate'])){ 
        $cms->addContent($_POST, $_FILES);
        exit;
    }

    if(isset($_POST['deleteBtn'])) { $id = $_POST['id'];  $cms->deleteContentOrNewsById($id); }
    if(isset($_POST['activateBtn'])) { $id = $_POST['id'];  $cms->activateContentOrNewsById($id); }
    if(isset($_POST['tagType'])){ 
        $tag = $_POST['tagType']; 
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'totalCount' => $cms->getTag($tag, true),
            'found' => $cms->getTag($tag, $page)
        ]); 
        exit;
    }

    if(isset($_POST['loginBtn'])){
        $uname = $_POST['uname']; 
        $pass = $_POST['pass']; 
        $cms->loginUser($uname, $pass);
    }
    
?>