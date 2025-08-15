<?php
// メールアドレスの設定
$adminEmail = "se-misora_sales@smart-energy.jp"; // 管理者のメールアドレス
$fromName = "低圧太陽光 お問い合わせフォーム";
$fromNameEncoded = mb_encode_mimeheader($fromName, "UTF-8");

// フォームの値を取得
$name = $_POST["name"] ?? "";
$company = $_POST["company"] ?? "";
$address = $_POST["address"] ?? "";
$tel = $_POST["tel"] ?? "";
$email = $_POST["email"] ?? "";
$count = $_POST["count"] ?? "";
$prefecture = $_POST["prefecture"] ?? "";

// 文字化け対策
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// 管理者宛メール内容
$subjectToAdmin = "低圧太陽光LPからのお問い合わせ";
$bodyToAdmin = <<<EOT
以下の内容でお問い合わせがありました。

■名前：
{$name}

■会社名：
{$company}

■住所：
{$address}

■電話番号：
{$tel}

■メールアドレス：
{$email}

■発電所の所有件数：
{$count}

■発電所がある都道府県：
{$prefecture}

----------
このメールはLPフォームから自動送信されたものです。
EOT;

// 管理者宛メールヘッダー（Fromは自分、Reply-Toにユーザー）
$headersToAdmin = "From: {$fromNameEncoded} <{$adminEmail}>\r\n";
$headersToAdmin .= "Reply-To: {$email}\r\n";

// メール送信（管理者宛）
mb_send_mail($adminEmail, $subjectToAdmin, $bodyToAdmin, $headersToAdmin);

// 自動返信メール内容
$subjectToUser = "【自動返信】お問い合わせありがとうございます";
$bodyToUser = <<<EOT
{$name} 様

お問い合わせありがとうございます。
以下の内容で受け付けいたしました。

=======================
■名前：{$name}
■会社名：{$company}
■住所：{$address}
■電話番号：{$tel}
■メールアドレス：{$email}
■発電所の所有件数：{$count}
■発電所がある都道府県：{$prefecture}
=======================

内容を確認の上、担当者よりご連絡差し上げます。

----------------------------------
SMART ENERGY サポート事務局
EOT;

// 自動返信メールのヘッダー（Fromは管理者メール）
$headersToUser = "From: {$fromNameEncoded} <{$adminEmail}>\r\n";
$headersToUser .= "Reply-To: {$adminEmail}\r\n";

// メール送信（ユーザー宛）
mb_send_mail($email, $subjectToUser, $bodyToUser, $headersToUser);

// 完了画面へリダイレクト
header("Location: thanks.html");
exit;
?>