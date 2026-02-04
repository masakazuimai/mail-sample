<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // thanks.php 経由で戻ってきたときにセッションを初期化
  $_SESSION['form_data'] = [];
}

$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
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

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="contact.css">
    <title>【サンプル】お問い合わせフォーム</title>
    <style>
    .inline-group {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .inline-group label {
        margin-left: 10px;
        margin-right: 20px;
    }

    .color-preview {
        display: inline-block;
        width: 30px;
        height: 30px;
        border: 1px solid #000;
        margin-left: 10px;
    }
    </style>
    <script>
    function updateColorPreview() {
        var colorInput = document.getElementById('color');
        var colorPreview = document.getElementById('color-preview');
        colorPreview.style.backgroundColor = colorInput.value;
    }
    </script>
</head>

<body>
    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>お問い合わせ</h1>
            </div>

            <form action="confirm.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">名前:</label>
                    <input type="text" id="name" name="name" class="form-control" required
                        value="<?php echo htmlspecialchars($form_data['name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">メールアドレス:</label>
                    <input type="email" id="email" name="email" class="form-control" required
                        value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">電話番号:</label>
                    <input type="tel" id="phone" name="phone" class="form-control"
                        value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="purpose">お問い合わせの目的:</label>
                    <select id="purpose" name="purpose" class="form-control">
                        <option value="" disabled <?php echo empty($form_data['purpose']) ? 'selected' : ''; ?>>選択してください
                        </option>
                        <option value="feedback"
                            <?php echo ($form_data['purpose'] ?? '') == 'feedback' ? 'selected' : ''; ?>>
                            フィードバック</option>
                        <option value="inquiry"
                            <?php echo ($form_data['purpose'] ?? '') == 'inquiry' ? 'selected' : ''; ?>>
                            一般的なお問い合わせ</option>
                        <option value="support"
                            <?php echo ($form_data['purpose'] ?? '') == 'support' ? 'selected' : ''; ?>>サポート依頼
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">希望する連絡日:</label>
                    <input type="date" id="date" name="date" class="form-control"
                        value="<?php echo htmlspecialchars($form_data['date'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="quantity">ご希望の数量:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" min="1" max="100"
                        value="<?php echo htmlspecialchars($form_data['quantity'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="color">お気に入りの色:</label>
                    <input type="color" id="color" name="color" class="form-control"
                        value="<?php echo htmlspecialchars($form_data['color'] ?? '#000000'); ?>"
                        onchange="updateColorPreview()">
                    <div id="color-preview" class="color-preview"
                        style="background-color: <?php echo htmlspecialchars($form_data['color'] ?? '#000000'); ?>;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="range">ご満足度:</label>
                    <input type="range" id="range" name="range" class="form-control" min="0" max="10"
                        value="<?php echo htmlspecialchars($form_data['range'] ?? '5'); ?>">
                </div>

                <div class="form-group">
                    <label>連絡方法:</label>
                    <div class="inline-group">
                        <input type="radio" id="email" name="contact_method" value="email"
                            <?php echo ($form_data['contact_method'] ?? 'email') == 'email' ? 'checked' : ''; ?>>
                        <label for="email">メール</label>
                        <input type="radio" id="phone" name="contact_method" value="phone"
                            <?php echo ($form_data['contact_method'] ?? '') == 'phone' ? 'checked' : ''; ?>>
                        <label for="phone">電話</label>
                        <input type="radio" id="either" name="contact_method" value="either"
                            <?php echo ($form_data['contact_method'] ?? '') == 'either' ? 'checked' : ''; ?>>
                        <label for="either">どちらでも</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>興味のあるトピック:</label>
                    <div class="inline-group">
                        <input type="checkbox" id="news" name="topics[]" value="news"
                            <?php echo in_array('news', $form_data['topics'] ?? []) ? 'checked' : ''; ?>>
                        <label for="news">ニュース</label>
                        <input type="checkbox" id="updates" name="topics[]" value="updates"
                            <?php echo in_array('updates', $form_data['topics'] ?? []) ? 'checked' : ''; ?>>
                        <label for="updates">アップデート</label>
                        <input type="checkbox" id="offers" name="topics[]" value="offers"
                            <?php echo in_array('offers', $form_data['topics'] ?? []) ? 'checked' : ''; ?>>
                        <label for="offers">特別オファー</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">メッセージ:</label>
                    <textarea id="message" name="message" class="form-control"
                        rows="4"><?php echo htmlspecialchars($form_data['message'] ?? ''); ?></textarea>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">確認</button>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> CodeQuest.work</p>
    </footer>
</body>

</html>