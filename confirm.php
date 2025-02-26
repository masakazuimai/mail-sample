<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['form_data'] = $_POST;

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $_SESSION['file_data'] = $_FILES['file']['name'];
    } else {
        $_SESSION['file_data'] = null;
    }
}

$form_data = $_SESSION['form_data'] ?? [];
$file_data = $_SESSION['file_data'] ?? '未アップロード';

// お問い合わせの目的オプション
$purpose_options = [
    'feedback' => 'フィードバック',
    'inquiry' => '一般的なお問い合わせ',
    'support' => 'サポート依頼'
];

$contact_method_options = [
    'email' => 'メール',
    'phone' => '電話',
    'either' => 'どちらでも'
];

$topics_options = [
    'news' => 'ニュース',
    'updates' => 'アップデート',
    'offers' => '特別オファー'
];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="contact.css">
  <title>確認画面</title>
</head>

<body>
  <div class="container">
    <div class="form-wrapper">
      <h1>確認画面</h1>
      <p><strong>名前:</strong> <?php echo htmlspecialchars($form_data['name'] ?? '未入力'); ?></p>
      <p><strong>メールアドレス:</strong> <?php echo htmlspecialchars($form_data['email'] ?? '未入力'); ?></p>
      <p><strong>電話番号:</strong> <?php echo htmlspecialchars($form_data['phone'] ?? '未入力'); ?></p>
      <p><strong>お問い合わせの目的:</strong> <?php echo htmlspecialchars($purpose_options[$form_data['purpose']] ?? '未選択'); ?>
      </p>
      <p><strong>希望する連絡日:</strong> <?php echo htmlspecialchars($form_data['date'] ?? '未選択'); ?></p>
      <p><strong>ご希望の数量:</strong> <?php echo htmlspecialchars($form_data['quantity'] ?? '未入力'); ?></p>
      <p><strong>お気に入りの色:</strong> <span
          style="display:inline-block;width:30px;height:30px;background-color:<?php echo htmlspecialchars($form_data['color'] ?? '#000000'); ?>;"></span>
      </p>
      <p><strong>ご満足度:</strong> <?php echo htmlspecialchars($form_data['range'] ?? '未入力'); ?></p>
      <p><strong>連絡方法:</strong>
        <?php echo htmlspecialchars($contact_method_options[$form_data['contact_method']] ?? '未選択'); ?></p>
      <p><strong>興味のあるトピック:</strong>
        <?php 
        if (!empty($form_data['topics']) && is_array($form_data['topics'])) {
            $selected_topics = array_map(function($topic) use ($topics_options) {
                return $topics_options[$topic] ?? '不明なトピック';
            }, $form_data['topics']);
            echo htmlspecialchars(implode(", ", $selected_topics), ENT_QUOTES, 'UTF-8');
        } else {
            echo '選択なし';
        }
        ?>
      </p>
      <p><strong>メッセージ:</strong> <?php echo nl2br(htmlspecialchars($form_data['message'] ?? '未入力')); ?></p>

      <form action="thanks.php" method="POST">
        <button type="submit" class="btn-submit">送信</button>
      </form>
      <form action="index.php" method="GET">
        <button type="submit" class="btn-back">戻る</button>
      </form>
    </div>
  </div>
</body>

</html>