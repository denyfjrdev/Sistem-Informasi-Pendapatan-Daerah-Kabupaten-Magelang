<?php

function generate_captcha()
{
    $word = substr(str_shuffle('1234567890'), 0, 5);

    // session()->set('captcha_word', $word);

    $image = imagecreatetruecolor(120, 40);

    $bg = imagecolorallocate($image, 255, 255, 255);
    $textcolor = imagecolorallocate($image, 0, 0, 0);

    imagefilledrectangle($image, 0, 0, 120, 40, $bg);

    #---font
    $font     = FCPATH . 'fonts/Verdana.ttf';
    // var_dump($font);exit();

    $fontSize = 16;
    imagettftext(
        $image,
        $fontSize,
        0,
        20,
        30,
        $textcolor,
        $font,
        $word
    );

    // imagestring($image, 5, 30, 10, $word, $textcolor);

    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();

    imagedestroy($image);

    // return 'data:image/png;base64,' . base64_encode($imageData);
    return (["word" => $word, "image" => 'data:image/png;base64,' . base64_encode($imageData)]);
}