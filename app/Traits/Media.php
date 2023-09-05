<?php

namespace App\Traits;
trait Media
{
    public static function upload($image,string $dir) :string
    {
        $photoName = uniqid() . '.' . $image->extension();
        $image->move(public_path("images/$dir"),$photoName);
        return $photoName;
    }

    public static function delete(string $fullPublicPath) :bool
    {
        $oldPhotoPath = public_path("{$fullPublicPath}");
        if (file_exists($oldPhotoPath)) {
            unlink($oldPhotoPath);
            return true;
        }
        return false;
    }
}
