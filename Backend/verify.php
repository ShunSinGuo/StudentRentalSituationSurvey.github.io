<?php
session_start();

// 生成隨機的驗證碼
$length = 4; // 驗證碼長度
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$captcha = '';
for ($i = 0; $i < $length; $i++) {
    $captcha .= $characters[rand(0, strlen($characters) - 1)];
}

// 將驗證碼存儲到 Session 中
$_SESSION['captcha'] = $captcha;

// 創建一個畫布並繪製驗證碼
$imageWidth = 90; // 圖片寬度
$imageHeight = 35; // 圖片高度
$image = imagecreatetruecolor($imageWidth, $imageHeight);
$backgroundColor = imagecolorallocate($image, 255, 255, 255); // 背景顏色
$textColor = imagecolorallocate($image, 0, 0, 0); // 文本顏色
imagefilledrectangle($image, 0, 0, $imageWidth - 1, $imageHeight - 1, $backgroundColor);
imagettftext($image, 20, 0, 10, 30, $textColor, './YuNaFont.ttf', $captcha); // 替換 'path/to/font.ttf' 為你的字型文件的實際路徑

// 設置 HTTP 頭部
header('Content-Type: image/png');

// 輸出圖片
imagepng($image);

// 釋放資源
imagedestroy($image);

// 將驗證碼數值以JSON格式返回
$response = array('captcha' => $captcha);
echo json_encode($response);
?>
