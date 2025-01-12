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

function addSpan($text)
{
    $step1 = str_replace("\$", "<p>", $text);
    $convertedText = str_replace("<p>", "</p>", $step1, $count);
    $convertedText = preg_replace("/\\$/", "<p>", $text, 1);
    $convertedText = preg_replace("/\\$/", "</p><br>", $convertedText, 1);

    return $convertedText;
}

function uploadImg($image)
{
    $extension = strtolower(Str::slug($image->getClientOriginalExtension()));
    if ($extension != 'png' && $extension != 'gif') {
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $image_name = time() . '_' . Str::slug($image->getClientOriginalName());
            $filename = pathinfo($image_name, PATHINFO_FILENAME);
            $path = public_path('postImage') . "/" . $filename . ".jpg";

            $imgRes = Image::make($image->getRealPath());
            $imgRes->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $imgRes->encode('jpg', 60)->save($path);
            return "postImage/" . $filename . ".jpg";
        }
    } else {
        if ($extension == 'png' || $extension == 'gif') {
            $name = Str::slug($image->getClientOriginalName());
            $explode = explode('.', $name);
            $name = $explode[0] . '_' . now()->format('d-m-Y_H-i-s') . '.' . $extension;
            $image->move(public_path('postImage/'), $name);
            return "postImage/" . $name;
        }
    }
}
?>
