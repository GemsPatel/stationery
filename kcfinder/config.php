<?php

    $_CONFIG = array(

        'disabled' => false,
        'denyZipDownload' => false,
        'denyUpdateCheck' => false,
        'denyExtensionRename' => false,

        'theme' => "oxygen",

        'uploadURL' => "http://".$_SERVER['HTTP_HOST']."/kcfinder/",
        'uploadDir' => "",

        'dirPerms' => 0755,
        'filePerms' => 0644,

        'access' => array(

            'files' => array(
                'upload' => true,
                'delete' => true,
                'copy' => true,
                'move' => true,
                'rename' => true
            ),

            'dirs' => array(
                'create' => true,
                'delete' => false,
                'rename' => true
            )
        ),

        'deniedExts' => "exe com msi bat php phps phtml php3 php4 cgi pl",

    'types' => array(

        'assets' => array(
            'type' => "*img",
            'thumbWidth' => 500,
            'thumbHeight' => 500
        )

    ),
        'filenameChangeChars' => array(/*
            ' ' => "_",
            ':' => "."
        */),

        'dirnameChangeChars' => array(/*
            ' ' => "_",
            ':' => "."
        */),

        'mime_magic' => "",

        'maxImageWidth' => 0,
        'maxImageHeight' => 0,

        'thumbWidth' => 50,
        'thumbHeight' => 50,

        'thumbsDir' => "",

        'jpegQuality' => 100,

        'cookieDomain' => "",
        'cookiePath' => "",
        'cookiePrefix' => 'KCFINDER_',
);		
?>