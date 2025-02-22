# KDGプラットフォーム

## 公開URL
- 9:00~21:00の公開（現在停止中）

  ~~https://kdg-platform-test.vercel.app/site/login/~~

- テスト環境ユーザー

  <table>
    <tr>
      <td>メールアドレス</td>
      <td>admin.nakao@example.com</td>
    </tr>
    <tr>
      <td>パスワード</td>
      <td>Kate0418</td>
    </tr>
  </table>

## アプリケーション概要
- 自分の通っている`KADOKAWAドワンゴ情報工科学院`のプラットフォームとなるアプリケーションです。
- `管理`, `講師`, `生徒`の三つのユーザータイプが存在します。
- 出席や公欠申請, スケジュールの確認, 本の貸出管理などの機能を開発中です。

## 創意工夫
- `使いやすい（UX）`これだけを重視して制作しています。そのため`画面遷移`と`1ページの情報`は最小限にしています。

**`UXの優れたアプリケーション`は`UI`が優れているが、`UIが優れたアプリケーション`は`UX`が優れているとは限らない。**

## 技術スタック
- フロントエンド

  <table>
    <tr>
      <td>フレームワーク</td>
      <td>Next.js</td>
    </tr>
    <tr>
      <td>デプロイ</td>
      <td>Vercel</td>
    </tr>
    <tr>
      <td>CSSフレームワーク</td>
      <td>Tailwind CSS</td>
    </tr>
    <tr>
      <td>開発環境</td>
      <td>Docker, Git</td>
    </tr>
  </table>

- バックエンド

  <table>
    <tr>
      <td>フレームワーク</td>
      <td>Laravel</td>
    </tr>
    <tr>
      <td>データベース</td>
      <td>RDS Mysql</td>
    </tr>
    <tr>
      <td>デプロイ</td>
      <td>AWS EC2</td>
    </tr>
    <tr>
      <td>キュー</td>
      <td>AWS SQS</td>
    </tr>
    <tr>
      <td>メール送信</td>
      <td>AWS SES</td>
    </tr>
    <tr>
      <td>webサーバー</td>
      <td>Nginx</td>
    </tr>
    <tr>
      <td>開発環境</td>
      <td>Docker, Git</td>
    </tr>
  </table>

## 実際のアプリケーション画像
### 【 共通 】

<table>
  <tr>
    <td colspan="2">ログインページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 35 24" src="https://github.com/user-attachments/assets/d1011536-6ba1-42ea-9bb5-d48072d6ab38" />
    </td>
    <td width="40%">
        <ul">
          <li>メールアドレスとパスワードを用いてログインができます。</li>
          <li>テスト環境ユーザー</li>
        </ul>
        <table>
          <tr>
            <td>メールアドレス</td>
            <td>admin.nakao@example.com</td>
          </tr>
          <tr>
            <td>パスワード</td>
            <td>Kate0418</td>
          </tr>
        </table>
    </td>
  </tr>
</table>

<br />
<br />

### 【 管理 】
<table>
  <tr>
    <td colspan="2">生徒一覧ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 35 59" src="https://github.com/user-attachments/assets/9987e6e1-4611-4584-a6cc-f4904dfb2998" />
    </td>
    <td width="40%">
      <ul>
        <li>生徒情報の一覧を確認できます。</li>
        <li>画面遷移なしで検索, 個別編集, 一括削除, 詳細の閲覧ができます。</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">生徒登録ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 36 38" src="https://github.com/user-attachments/assets/20d13fe3-f28b-4b63-8db9-97843cec86bb" />
    </td>
    <td width="40%">
      <ul>
        <li>生徒情報の登録ができます。</li>
        <li>一度に複数の生徒ユーザーを登録することが可能です。</li>
        <li>登録したユーザーのメールアドレス宛に、ランダムなパスワードとログインのURLが送信されます。</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">講師一覧ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 37 46" src="https://github.com/user-attachments/assets/57d79595-37ee-45a8-b31b-08217a5661be" />
    </td>
    <td width="40%">
      <ul>
        <li>講師情報の一覧を確認できます。</li>
        <li>画面遷移なしで検索, 個別編集, 一括削除, 詳細の閲覧ができます。</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">講師登録ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 38 37" src="https://github.com/user-attachments/assets/75a53844-628d-4eb3-a22f-88961b3112f8" />
    </td>
    <td width="40%">
      <ul>
        <li>講師情報の登録ができます。</li>
        <li>一度に複数の講師ユーザーを登録することが可能です。</li>
        <li>登録したユーザーのメールアドレス宛に、ランダムなパスワードとログインのURLが送信されます。</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">科目一覧ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 39 04" src="https://github.com/user-attachments/assets/ee92b471-a5b6-4e25-8981-6138dbc0adcd" />
    </td>
    <td width="40%">
      <ul>
        <li>科目情報の一覧を確認できます。</li>
        <li>画面遷移なしで検索, 個別編集, 一括削除ができます。</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">科目登録ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 39 24" src="https://github.com/user-attachments/assets/20d58c7c-980c-466d-bced-978d5fb15ac6" />
    </td>
    <td width="40%">
      <ul>
        <li>科目情報の登録ができます。</li>
        <li>一度に複数の科目を登録することが可能です。</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">コース一覧ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 43 32" src="https://github.com/user-attachments/assets/e3168ed0-55ed-4399-9572-bcf6fa12c7a8" />
    </td>
    <td width="40%">
      <ul>
        <li>コース情報の一覧を確認できます。</li>
        <li>画面遷移なしで検索, 一括削除ができます。</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">コース登録ページ</td>
  </tr>
  <tr>
    <td width="60%">
      <img alt="スクリーンショット 2025-01-05 1 42 38" src="https://github.com/user-attachments/assets/93fe7304-07c5-4bcc-9169-a9aa2aa58ee1" />
    </td>
    <td width="40%">
      <ul>
        <li>コース情報の登録ができます。</li>
        <li>ドラック&ドロップが可能なので楽々と授業コマの変更ができます。</li>
      </ul>
    </td>
  </tr>
</table>

<br />
<br />

### 【 講師 】
- 開発中

<br />
<br />

### 【 生徒 】
- 開発中

## フロントエンドリポジトリ
- https://github.com/Kate0418/kdg-platform-front
