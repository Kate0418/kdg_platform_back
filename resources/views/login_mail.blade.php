<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインリマインダー</title>
    <style>
        body {
            line-height: 1;
            padding: 10px;
        }
        p {
            margin: 0;
        }
        li {
            font-weight: bold;
        }
        .element {
            margin: 14px 0;
            line-height: 1.2;
        }
        .accent {
            color: #344699;
        }
        .left {
            padding-left: 10px
        }
    </style>
</head>
<body>
    <h2 class="accent">アカウントが作成されました！</h2>
    <p>アカウントを安全に利用するために、以下のステップを完了してください</p>
    <ul>
        <li>URLからログインする</li>
        <div class="element">
            <p>ログインページ</p>
            <a class="left" href="https://kdg-platform-test.vercel.app/site/login/">https://kdg-platform-test.vercel.app/site/login/</a>
        </div>
        <div class="element">
            <p>メールアドレス</p>
            <a class="left">{{ $user->email }}</a>
        </div>
        <div class="element">
            <p>パスワード</p>
            <p class="left">{{ $user->first_password }}</p>
        </div>

        <li>パスワードを変更する</li>
    </ul>
</body>
</html>
