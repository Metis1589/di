<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 12/25/2014
 * Time: 4:21 PM
 */

namespace common\components;


use common\models\Media;
use common\models\MediaApp;
use common\models\ProductOption;
use yii\base\ErrorException;
use Yii;

class ImageHelper {

    static function getThumbFilename($sourceFilename, $prefix = '') {
        $path_parts = pathinfo($sourceFilename);
        return str_replace('.' . $path_parts['extension'], '_thumb' . $prefix . '.' . $path_parts['extension'], $sourceFilename);
    }

    static function createThumb($sourceFilename, $desired_width, $prefix = '') {

        $extension = strtolower(pathinfo($sourceFilename)['extension']);

        $destinationFilename = ImageHelper::getThumbFilename($sourceFilename, $prefix);

        /* read the source image */

        if ($extension == 'png') {
            $source_image = imagecreatefrompng($sourceFilename);
        }
        else {
            $source_image = imagecreatefromjpeg($sourceFilename);
        }

        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = floor($height * ($desired_width / $width));

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */

        if ($extension == 'png') {
            imagepng($virtual_image, $destinationFilename);;
        }
        else {
            imagejpeg($virtual_image, $destinationFilename);
        }

    }
} 