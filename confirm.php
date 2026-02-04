<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['form_data'] = $_POST;
}

$form_data = $_SESSION['form_data'] ?? [];

// オプション定義
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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7Z6M3CJEV3"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag("js", new Date());
    gtag("config", "G-7Z6M3CJEV3");
    </script>

    <!-- adsense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4871781946658288"
        crossorigin="anonymous"></script>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="contact.css">
    <title>【サンプル】確認画面</title>
</head>

<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>確認画面</h1>

            <p><strong>名前:</strong> <?= htmlspecialchars($form_data['name'] ?? '未入力') ?></p>
            <p><strong>メールアドレス:</strong> <?= htmlspecialchars($form_data['email'] ?? '未入力') ?></p>
            <p><strong>電話番号:</strong> <?= htmlspecialchars($form_data['phone'] ?? '未入力') ?></p>
            <p><strong>お問い合わせの目的:</strong>
                <?php
                  $purpose_key = $form_data['purpose'] ?? null;
                  echo htmlspecialchars($purpose_options[$purpose_key] ?? '未選択');
                ?>
            </p>
            <p><strong>希望する連絡日:</strong> <?= htmlspecialchars($form_data['date'] ?? '未入力') ?></p>
            <p><strong>ご希望の数量:</strong> <?= htmlspecialchars($form_data['quantity'] ?? '未入力') ?></p>
            <p><strong>お気に入りの色:</strong>
                <span
                    style="display:inline-block;width:30px;height:30px;background-color:<?= htmlspecialchars($form_data['color'] ?? '#000000') ?>;"></span>
            </p>
            <p><strong>ご満足度:</strong> <?= htmlspecialchars($form_data['range'] ?? '未入力') ?></p>
            <?php
              $method_key = $form_data['contact_method'] ?? null;
              echo htmlspecialchars($contact_method_options[$method_key] ?? '未選択');
            ?>
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
            <p><strong>メッセージ:</strong><br>
                <?= nl2br(htmlspecialchars($form_data['message'] ?? '未入力')) ?>
            </p>

            <!-- thanks.phpへPOST送信。データとファイルを一緒に渡す -->
            <form action="thanks.php" method="POST" enctype="multipart/form-data">
                <?php foreach ($form_data as $key => $value): ?>
                <?php if (is_array($value)): ?>
                <?php foreach ($value as $v): ?>
                <input type="hidden" name="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>[]"
                    value="<?= htmlspecialchars($v, ENT_QUOTES, 'UTF-8') ?>">
                <?php endforeach; ?>
                <?php else: ?>
                <input type="hidden" name="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                    value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <?php endforeach; ?>

                <div class="form-group">
                    <label for="file">添付ファイル（任意）:</label>
                    <input type="file" name="file" id="file">
                </div>

                <button type="submit" class="btn-submit">送信</button>
            </form>

            <form action="index.php" method="GET">
                <button type="submit" class="btn-back">戻る</button>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> CodeQuest.work</p>
    </footer>
</body>

</html>