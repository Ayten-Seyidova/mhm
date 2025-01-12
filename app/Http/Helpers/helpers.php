<?php

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

function cleaner($text, $character)
{
    $text = strip_tags($text);
    $text = html_entity_decode($text);
    $text = mb_substr($text, 0, $character);

    return $text;
}

function stringLen($text)
{
    $text = html_entity_decode($text);
    $text = strip_tags($text);
    $text = \Illuminate\Support\Str::length($text);

    return $text;
}

function addDots($text, $count)
{
    $dots = strlen($text) > $count ? '...' : '';

    $result = cleaner($text, $count) . $dots;

    return $result;
}

function uploadImg($image)
{
    $extension = Str::slug($image->getClientOriginalExtension());
        $image_name = time() . '_' . Str::slug($image->getClientOriginalName());
        $filename = pathinfo($image_name, PATHINFO_FILENAME);
        $path = public_path('storage/postImage') . "/" . $filename . ".jpg";

        $imgRes = Image::make($image->getRealPath());
        $imgRes->resize(1080, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imgRes->encode('jpg', 60)->save($path);
        return "postImage/" . $filename . ".jpg";
    
}
?>
