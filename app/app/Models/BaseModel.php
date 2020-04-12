<?php

namespace App\Models;

use Image;
use App\TCC;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function uploadImage(object $file, string $path, array $resize = array(300, 200))
    {
        try
        {
            $originalFilename = $file->getClientOriginalName();
            
            $originalFilenameArr = explode('.', $originalFilename);
            
            $fileExt = end($originalFilenameArr);
            
            $image = 'A-' . time() . '.' . $fileExt;

            $filePath = $path . $image;

            Image::make($file)
                    ->resize($resize[0], $resize[1])
                    ->save($filePath);

            return $filePath;
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            throw new \Exception('failed to save image');
        }
    }
}