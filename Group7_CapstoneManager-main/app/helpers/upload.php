<?php

function uploadFile($file, $folder = 'general'){

    if(!isset($file)){
        return false;
    }

    $allowed = ['pdf','doc','docx'];
    $allowedMimes = [
        'application/pdf', 
        'application/msword', 
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    $filename = $file['name'];
    $tmp = $file['tmp_name'];
    $size = $file['size'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);

    if(!in_array($ext, $allowed) || !in_array($mime, $allowedMimes)){

        return [
            'status' => false,
            'message' => 'Invalid file type. Only PDF, DOC, DOCX are allowed.'
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