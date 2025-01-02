<?php
$imagePath = __DIR__ . '';

if (!file_exists($imagePath)) {
    die("Image non trouvée !");
}
$image = imagecreatefromjpeg($imagePath);
$imageWidth = imagesx($image);
$imageHeight = imagesy($image);

$black = imagecolorallocate($image, 0, 0, 0);
$red = imagecolorallocate($image, 255, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$blue = imagecolorallocate($image, 0, 0, 255);

$fontPath = __DIR__ . '/OpenSans.ttf';

$allowedDomains = [
    'iooner.io'
];

$referer = $_SERVER['HTTP_REFERER'] ?? 'Aucun referer';
$isAllowed = false;

foreach ($allowedDomains as $domain) {
    if (strpos($referer, $domain) !== false) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed) {
    $mainText = "Utilisation non autorisée !";
    $subText = "L'utilisation de cette image est limitée. Demandez l'autorisation sur www.iooner.io/contact";

    $mainFontSize = 100;
    $subFontSize = 30;

    $mainTextBox = imagettfbbox($mainFontSize, 0, $fontPath, $mainText);
    $mainTextWidth = abs($mainTextBox[4] - $mainTextBox[0]);
    $mainTextHeight = abs($mainTextBox[5] - $mainTextBox[1]);
    $mainTextX = ($imageWidth - $mainTextWidth) / 2;
    $mainTextY = ($imageHeight - $mainTextHeight) / 2;

    for ($x = -2; $x <= 2; $x++) {
        for ($y = -2; $y <= 2; $y++) {
            if ($x != 0 || $y != 0) {
                imagettftext($image, $mainFontSize, 0, $mainTextX + $x, $mainTextY + $y, $black, $fontPath, $mainText);
            }
        }
    }

    imagettftext($image, $mainFontSize, 0, $mainTextX, $mainTextY, $red, $fontPath, $mainText);

    $subTextBox = imagettfbbox($subFontSize, 0, $fontPath, $subText);
    $subTextWidth = abs($subTextBox[4] - $subTextBox[0]);
    $subTextHeight = abs($subTextBox[5] - $subTextBox[1]);
    $subTextX = ($imageWidth - $subTextWidth) / 2;
    $subTextY = $mainTextY + $mainTextHeight + 10;

    for ($x = -1; $x <= 1; $x++) {
        for ($y = -1; $y <= 1; $y++) {
            if ($x != 0 || $y != 0) {
                imagettftext($image, $subFontSize, 0, $subTextX + $x, $subTextY + $y, $black, $fontPath, $subText);
            }
        }
    }

    imagettftext($image, $subFontSize, 0, $subTextX, $subTextY, $white, $fontPath, $subText);

    $debugText = "Referer: $referer";
    $debugStatus = $isAllowed ? "Statut: Autorisé" : "Statut: Non autorisé";

    $debugFontSize = 15;
    $debugX = 10;
    $debugY = 30;

    imagettftext($image, $debugFontSize, 0, $debugX, $debugY, $blue, $fontPath, $debugText);
    imagettftext($image, $debugFontSize, 0, $debugX, $debugY + 20, $blue, $fontPath, $debugStatus);
}

$footerText = "MétéoCam - iooner.io/meteo - meteo4600.be";
$footerFontSize = 25;

$footerTextBox = imagettfbbox($footerFontSize, 0, $fontPath, $footerText);
$footerTextHeight = abs($footerTextBox[5] - $footerTextBox[1]);
$footerTextX = 10;
$footerTextY = $imageHeight - 10;

for ($x = -1; $x <= 1; $x++) {
    for ($y = -1; $y <= 1; $y++) {
        if ($x != 0 || $y != 0) {
            imagettftext($image, $footerFontSize, 0, $footerTextX + $x, $footerTextY + $y, $black, $fontPath, $footerText);
        }
    }
}

imagettftext($image, $footerFontSize, 0, $footerTextX, $footerTextY, $white, $fontPath, $footerText);

$fileModificationTime = date("d/m/Y H:i:s", filemtime($imagePath));
$dateFontSize = 25;
$dateTextBox = imagettfbbox($dateFontSize, 0, $fontPath, $fileModificationTime);
$dateTextWidth = abs($dateTextBox[4] - $dateTextBox[0]);
$dateTextX = $imageWidth - $dateTextWidth - 10;
$dateTextY = $imageHeight - 10;

for ($x = -1; $x <= 1; $x++) {
    for ($y = -1; $y <= 1; $y++) {
        if ($x != 0 || $y != 0) {
            imagettftext($image, $dateFontSize, 0, $dateTextX + $x, $dateTextY + $y, $black, $fontPath, $fileModificationTime);
        }
    }
}

imagettftext($image, $dateFontSize, 0, $dateTextX, $dateTextY, $white, $fontPath, $fileModificationTime);

header("Content-Type: image/jpeg");

imagejpeg($image);

imagedestroy($image);
?>
