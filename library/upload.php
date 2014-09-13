<?php

/**
 * An class to help with uploading files
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 * @last-modified 31-08-2014
 *
 * @todo add more upload functions for different types of files
 * @todo fix image detection
 */
class Upload {

    function __construct() {
    }

    /**
     * Uploads a array
     *
     * @param        $files - Array with $_FILE[]'s
     * @param string $destination - Must be a absolute path
     */
    public function uploadArray($files, $destination) {
        foreach($files as $file) {
            $this->upload($file, $destination);
        }
    }

    /**
     * Function that uploads images, checks with the image type
     *
     * @param        $file - The $_FILE[] parameter
     * @param string $destination - Must be a absolute path
     *
     * @return bool|string
     */
    public function uploadImage($file, $destination) {
        //@todo fix image detection
        /*if ($file['type'] != 'image/gif' || $file['type'] != 'image/jpeg' || $file['type'] != 'image/jpg' || $file['type'] != 'image/png') {
            return 'File not an image, got: ' . $file['type'];
        }*/
        return $this->upload($file, $destination);
    }

    private function upload($file, $destination) {

        if ($file != null) {
            if ($file['error'] != UPLOAD_ERR_OK) {
                return 'An error occured: ' . $file['error'];
            } else {
                if ($file['size'] > $this->getMaxUploadSize()) {
                    return 'An error occured: File size is too large!';
                }
                if (!is_writable($destination)) {
                    return 'An error occured: Directory is not writable!';
                }

                return move_uploaded_file($file['tmp_name'], $destination . $file['name']) ? : 'An error occured';
            }
        } else {
            return 'Geupload bestand bestaat niet!';
        }
    }

    private function getMaxUploadSize() {
        $val = trim(ini_get('upload_max_filesize'));
        $last = strtolower($val[strlen($val) - 1]);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
}
