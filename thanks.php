<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // ComposerでインストールしたPHPMailerを読み込む

// エラーレポート（開発時のみ有効に）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// フォームデータをPOSTから受け取る
$form_data = $_POST;

// 日本語ラベル定義
$purpose_options = [
    'feedback' => 'フィードバック',
    'inquiry' => '一般的なお問い合わせ',
    'support' => 'サポート依頼'
];

$topics_options = [
    'news' => 'ニュース',
    'updates' => 'アップデート',
    'offers' => '特別オファー'
];

$purpose_key = $form_data['purpose'] ?? '';
$purpose_jp = $purpose_options[$purpose_key] ?? '未選択';

$topics_jp = '選択なし';
if (!empty($form_data['topics']) && is_array($form_data['topics'])) {
    $selected_topics = array_map(function($topic) use ($topics_options) {
        return $topics_options[$topic] ?? '不明なトピック';
    }, $form_data['topics']);
    $topics_jp = implode(", ", $selected_topics);
}

// メール本文の作成
$message = <<<EOT
名前: {$form_data['name']}
メールアドレス: {$form_data['email']}
電話番号: {$form_data['phone']}
お問い合わせの目的: {$purpose_jp}
希望する連絡日: {$form_data['date']}
ご希望の数量: {$form_data['quantity']}
お気に入りの色: {$form_data['color']}
ご満足度: {$form_data['range']}
連絡方法: {$form_data['contact_method']}
興味のあるトピック: {$topics_jp}
メッセージ:
{$form_data['message']}
EOT;

// メール送信処理（PHPMailer）
$mail_success = false;

$mail = new PHPMailer(true);

try {
    // SMTP設定（←適宜変更してください）
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // 例：smtp.gmail.com
    $mail->SMTPAuth = true;
    $mail->Username = 'your@example.com'; // 送信元アカウント
    $mail->Password = 'yourpassword';     // パスワード（アプリパスワード推奨）
    $mail->SMTPSecure = 'tls';            // ssl or tls
    $mail->Port = 587;

    // メールヘッダ
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->setFrom('your@example.com', 'お問い合わせフォーム');
    $mail->addAddress('test@sample.com', '受信者名'); // 送信先

    // 本文
    $mail->Subject = 'お問い合わせフォームからのメッセージ';
    $mail->Body = $message;

    // 添付ファイル（あれば）
    if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
        $mail->addAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
    }

    $mail->send();
    $mail_success = true;
} catch (Exception $e) {
    error_log('メール送信失敗: ' . $mail->ErrorInfo);
}

// セッションを完全にクリア（これを追加）
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7Z6M3CJEV3"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag("js", new Date());
    gtag("config", "G-7Z6M3CJEV3");
    <?php if ($mail_success): ?>
    gtag("event", "sample_contact_form");
    <?php endif; ?>
    </script>

    <!-- adsense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4871781946658288"
        crossorigin="anonymous"></script>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="contact.css">
    <title>【サンプル】送信完了</title>
</head>

<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>送信完了</h1>
            <?php if ($mail_success): ?>
            <p>お問い合わせいただきありがとうございます。</p>
            <p>担当者よりご連絡いたしますので、しばらくお待ちください。</p>
            <?php else: ?>
            <p>申し訳ありませんが、サンプルフォームの為送信に失敗しました。</p>
            <p>再度お試しいただくか、別の方法でご連絡ください。</p>
            <?php endif; ?>

            <form action="index.php" method="GET">
                <button type="submit">トップページへ戻る</button>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> CodeQuest.work</p>
    </footer>
</body>

</html>