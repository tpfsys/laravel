# メンテナンスモードのメッセージオプションのテスト方法

このPRでは、Laravel のメンテナンスモード (`php artisan down`) コマンドに `--message` オプションを追加し、メンテナンス画面に表示されるメッセージをコマンドラインから直接指定できるようにしました。

## 使用方法

### メンテナンスモードをカスタムメッセージで有効化する:

```bash
php artisan down --message="ちょっと今DBメンテ中です！すぐ戻ります！"
```

### メンテナンスモードを解除する:

```bash
php artisan up
```

## 実装の詳細

1. `app/Console/Commands/DownCommand.php` - Laravel の基本 DownCommand を拡張し、`--message` オプションを追加
2. `app/Console/Commands/UpCommand.php` - メンテナンスモードを解除するとメッセージも削除される
3. `resources/views/errors/503.blade.php` - カスタムメッセージを表示するためのメンテナンス画面テンプレート
4. `storage/framework/maintenance-template.php` - メンテナンスモード中に使用されるテンプレート

## 動作の仕組み

1. ユーザーが `php artisan down --message="..."` を実行
2. カスタムコマンドが `down` ファイルの JSON ペイロードにメッセージを追加
3. メンテナンス画面にアクセスすると、このメッセージが表示される
4. `php artisan up` を実行すると、メッセージが含まれる `down` ファイルが削除されるため、メッセージもリセットされる

これにより、ユーザーは簡単にメンテナンス画面のメッセージを変更でき、メンテナンスモードを解除するとメッセージも自動的に削除されます。