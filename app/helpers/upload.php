<?php

function uploadFile($file, $folder = 'general'){

    if(!isset($file)){
        return false;
    }

    $allowed = ['pdf','doc','docx'];

    $filename = $file['name'];

    $tmp = $file['tmp_name'];

    $size = $file['size'];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){

        return [
            'status' => false,
            'message' => 'Invalid file type'
        ];
    }

    if($size > 10 * 1024 * 1024){

        return [
            'status' => false,
            'message' => 'File too large'
        ];
    }

    $newName = time() . "_" . uniqid() . "." . $ext;

    $uploadPath = "../public/assets/uploads/" . $folder . "/";

    if(!file_exists($uploadPath)){

        mkdir($uploadPath, 0777, true);
    }

    $destination = $uploadPath . $newName;

    if(move_uploaded_file($tmp, $destination)){

        return [
            'status' => true,
            'path' => "assets/uploads/" . $folder . "/" . $newName
        ];
    }

    return [
        'status' => false,
        'message' => 'Upload failed'
    ];
}