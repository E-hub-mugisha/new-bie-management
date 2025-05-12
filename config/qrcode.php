<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Format
    |--------------------------------------------------------------------------
    |
    | This option controls the default format of the QR code image.
    | It can be png, svg, or eps.
    |
    */

    'format' => 'png',

    /*
    |--------------------------------------------------------------------------
    | Error Correction Level
    |--------------------------------------------------------------------------
    |
    | This option determines the error correction level to use for the QR code.
    | It can be one of the following: 'L', 'M', 'Q', 'H'.
    |
    */

    'error_correction_level' => 3,

    /*
    |--------------------------------------------------------------------------
    | Size of QR Code
    |--------------------------------------------------------------------------
    |
    | This option determines the size of the QR code image.
    |
    */

    'size' => 200,

    /*
    |--------------------------------------------------------------------------
    | Margin
    |--------------------------------------------------------------------------
    |
    | This option determines the margin size of the QR code image.
    |
    */

    'margin' => 4,

    /*
    |--------------------------------------------------------------------------
    | Backend
    |--------------------------------------------------------------------------
    |
    | You can choose between the GD or Imagick backend for generating QR codes.
    | If you don't have Imagick installed, GD will be used by default.
    | Set it to 'GD' if you want to force the use of GD.
    |
    */

    'backend' => 'GD', // This forces the use of GD backend for QR code generation.

];
