<?php
session_start();

// セッションにフォームデータが存在しない場合はindex.phpにリダイレクト
if (!isset($_SESSION['form_data'])) {
    session_write_close();
    header('Location: index.php');
    exit();
}

// フォームデータを取得
$form_data = $_SESSION['form_data'] ?? [];
$file_data = $_SESSION['file_data'] ?? '未アップロード';

// **お問い合わせの目的オプション（日本語）**
$purpose_options = [
    'feedback' => 'フィードバック',
    'inquiry' => '一般的なお問い合わせ',
    'support' => 'サポート依頼'
];

// **興味のあるトピックオプション（日本語）**
$topics_options = [
    'news' => 'ニュース',
    'updates' => 'アップデート',
    'offers' => '特別オファー'
];

// **お問い合わせの目的を日本語に変換**
$purpose_jp = $purpose_options[$form_data['purpose']] ?? '未選択';

// **興味のあるトピックを日本語に変換**
$topics_jp = '選択なし';
if (!empty($form_data['topics']) && is_array($form_data['topics'])) {
    $selected_topics = array_map(function($topic) use ($topics_options) {
        return $topics_options[$topic] ?? '不明なトピック';
    }, $form_data['topics']);
    $topics_jp = implode(", ", $selected_topics);
}

// **メール送信の準備**
$to = "test@sample.com"; // **受信者のメールアドレス**
$subject = "お問い合わせフォームからのメッセージ";
$headers = "From: test@sample.com\r\n" . // **受信者のメールアドレス**
           "Reply-To: " . $form_data['email'] . "\r\n" .
           "Content-Type: text/plain; charset=UTF-8\r\n";

// **メール本文（日本語で送信）**
$message = "名前: {$form_data['name']}\n" .
           "メールアドレス: {$form_data['email']}\n" .
           "電話番号: {$form_data['phone']}\n" .
           "お問い合わせの目的: $purpose_jp\n" .
           "希望する連絡日: {$form_data['date']}\n" .
           "ご希望の数量: {$form_data['quantity']}\n" .
           "お気に入りの色: {$form_data['color']}\n" .
           "ご満足度: {$form_data['range']}\n" .
           "連絡方法: {$form_data['contact_method']}\n" .
           "興味のあるトピック: $topics_jp\n" .
           "メッセージ:\n{$form_data['message']}";

// **メール送信**
$mail_success = mail($to, $subject, $message, $headers);

// **セッションデータを削除**
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="contact.css">
  <title>送信完了</title>
</head>

<body>
  <div class="container">
    <div class="form-wrapper">
      <h1>送信完了</h1>
      <p>お問い合わせいただきありがとうございます。</p>
      <?php if ($mail_success): ?>
      <p>担当者よりご連絡いたしますので、しばらくお待ちください。</p>
      <?php else: ?>
      <p>申し訳ありませんが、メールの送信に失敗しました。もう一度お試しください。</p>
      <?php endif; ?>

      <div class="form-footer">
        <form action="index.php" method="GET">
          <button type="submit">トップページへ戻る</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>