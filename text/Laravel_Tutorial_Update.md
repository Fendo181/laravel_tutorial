# はじめてのLaravelアプリケーションガイド


## はじめに

この記事は[はじめてのLaravelアプリケーションを構築する為のStep by Step Guide](http://qiita.com/Fendo181/items/55701abc11c205b9c057)を元に、改良したチュートリアル記事です。一部内容が被る部分はありますがLaravelを初めて触る人向けに少しだけ説明を加えています。

今回ここで作成するwebアプリケーションは以下の機能を持ったリンク投稿アプリになります。

- **1.リンクの簡単なリストを表示します。**
- **2.新しいリンクを提出できるフォームを作成します。**
- **3.フォームを検証する**
- **4.検証が通ったらデータをデータベースに保存します。**


このような簡単なwebアプリケーションをこのチュートリアルでは1つ1つ機能を紹介しながら説明していきます。しかし私がこのチュートリアルで一番に掲げる目的は、**今まさにフレームワークを勉強し始めた人たちのためのガイドを作ることです**。従って、Laravelの高度な機能や説明に関してはこのチュートリアルでは詳細に紹介しません。あくまでも、このチュートリアルはLaravelを始めるきっかけの1つとして作りました。


## 環境構築

まずはLaravelをローカル環境にインスールする作る作業からはじめます。
プロジェクトをすぐに始める為にLaravelではVagrantのboxで最初から自動で必要なファイルを用意してくれている[Laravel Homestead](https://laravel.com/docs/5.6/homestead)や、Mac向けに用意された[Valet](https://laravel.com/docs/5.6/valet)が用意されています。

>- [Laravel Homestead](https://laravel.com/docs/5.6/homestead)
>- [Valet](https://laravel.com/docs/5.6/valet)

また上記の方法以外にも、Dockerを使った[Laradock](https://github.com/laradock/laradock)という手段もあります。

>- [インストール](https://readouble.com/laravel/5.6/ja/installation.html)

ここではそれぞれの方法での詳細なインスール手順は紹介しませんが、もし不安でしたら以下に手順を書いた記事のリンクを紹介するので迷ったらここを覗いて見てください。

>- [Laravel Valetをつかってみた。](http://qiita.com/nkumag/items/3ba39749e5ad59ede1f5)
>- [Laravel Homestead](https://laravel.com/docs/5.6/homestead)
>- [Laravel開発環境をLaradockで構築する](https://www.tam-tam.co.jp/tipsnote/program/post11885.html)

今回のチュートリアルでは私の開発環境はLaravel Homesteadを選択しての開発を進めていきます。


## プロジェクトを始める。

今回は新しく`Sites`ディレクトリを作ってそこでLaravelのプロジェクトを新しく始めようと思います。

ターミナルを開き、`Sites`ディレクトリに切り替えます。

```
mkdir Sites
cd ~/Sites
```

次に、Laravelのインストーラをインストールします。

```
composer global require "laravel/installer"
```

_(※既に[LaravelHomStead](https://laravel.com/docs/5.6/homestead)をインスールしているのであればここは既にインスール済みなので飛ばして大丈夫です。)_


次のコマンドを実行してプロジェクトを作成します。

```
laravel new links
```

これで"links"とい名前の新しいディレクトリが作成され、laravelのプロジェクトがインスールされました。ブラウザに

```
localhost:8000
```

を入力して以下のwelcomeページが表示される事を確認して下さい。


![laravel.png](https://qiita-image-store.s3.amazonaws.com/0/64829/6d3a23c6-a177-2482-3c08-e019ad883f1f.png)



## MVCパターン

リンクリストを作成する前に私は、ここで1つMVCに関する簡単な講義を始めようと思います。何故、いきなりMVCについて説明をするかというと、今回のチュートリアルで作るLinkアプリケーション自体が最終的にMVCパターンの沿って実装されるからです。従って最初にこの概念が無い人にとっては、理解が難しくなってしまうので、ここでは軽くMVCパターンについて説明します。

![image.png](https://qiita-image-store.s3.amazonaws.com/0/64829/54aa0085-e4a6-dd3d-cdb7-34897735c494.png)
[参考:[初めてのLARAVEL 5.1 : (14) MVC](https://laravel10.wordpress.com/2015/03/06/%E5%88%9D%E3%82%81%E3%81%A6%E3%81%AElaravel-5-14-mvc/)]

MVCパターンとModel・View・Controllerの略でそれぞれに役割を分担させる事でプログラム全体が見通し良くなり、再利用製の高い設計を実現するデザインパターンです。
各役割は以下の通りです。

### Controller

- リクエスト処理、ビューとモデルの制御。
- ユーザーからの入力を受け取る

### View

- クライアントに対してHTMLを出力する


### Model

- データ構造とそれを操作する(処理、検証、保存など)
- データ層へのアクセス

今回新しく作成したLaravelのプロジェクトを見ると、以下のようなディレクトリ構造になっています。


```php
.
├── app
│   ├── Console
│   ├── Exceptions
│   ├── Http
│   │   ├── Controllers # Controllerディレクトリ
│   │   ├── Kernel.php
│   │   ├── Middleware
│   │   └── Requests
│   ├── Link.php
│   ├── Providers
│   └── User.php #Model
.
.
.
├── resources
│   ├── assets
│   ├── lang
│   └── views #View
.
.
.
```

Laravelでは新しく作成するControllerは`app/controller`ディレクトリ直下に生成されます。Viewファイルは`resources/views`直下に配置します。

では「Modelは?」と言うと、ここがLaravelの特徴でもあるのですが、Laravelは`models`に相当するディレクトリがないです。理由は[ここ](https://readouble.com/laravel/5.6/ja/structure.html)に詳細に記載されているのですが、これは意図的に設計されています。なので、最初は戸惑うと思いますが、新しく`Model`を作成した場合は`app`直下に作られる事を覚えておいて下さい。


## Linkモデルを作成する。

DBを操作する為のLinkモデルが必要になります。以下のコマンドでLinkモデルが作られます。

```
php artisan make:model Link
```


## テストデータの作成

ここではmigrationという機能を使って、直接DBにアクセスしてSQLを叩かずに、LinkListで扱うテーブルを作成します。マイグレーションファイルを作成することが最初のステップとなり、Laravel Artisanコマンドラインツールがその作成に役立ちます。

```php
php artisan make:migration create_links_table --create=links
```

オプションで`--create=links`をつける事で、新しくlinksテーブルを生成する事が可能です。

ファイルは`database / migrations / {{datetime}} _ create_links_table.php`にあります。
ファイルを開いてメソッドの中に新しいカラムを追加します。

```php
Schema::create('links', function (Blueprint $table) {
      $table->increments('id');
      $table->string('title');
      $table->string('url')->unique();
      $table->text('description');
      $table->timestamps();
});
```


ファイルを保存して、マイグレーションを実行しますが、その前にMySQLに`.env`の設定に応じたDBとtable、そして権限付きのユーザを作成します。

```sql
create database DB_NAME;
grant all on DB_NAME.* to 'USER_NAME'@HOST_NAME identified by 'PASSWORD';
```
用意ができたら。マイグレーションを実行します。

```
php artisan migrate
```

これでDBに新しくLinkList用のlinksテーブルが作成されました。

`table`が出来たので次はテストデータを入力してみましょう。

と言っても、まだ入力のフォーム画面も用意されてないのにどうするのか?というと、Laravelはこれを助ける2つの機能を提供します。 Seeder(初期値)とModelFactoryです。

>- [ModelFactory](https://readouble.com/laravel/5.6/ja/testing.html#model-factories)
>- [Seeder](https://readouble.com/laravel/5.6/ja/seeding.html)

ここでは2つの機能を使う事でテストデータを作成するModelFactoryを定義し、SeederからModelFactoryを利用することでテストデータを作成する方法を紹介します。

以下のコマンドを実行して`Linkモデル`を作ると同時に、`LinkFactory`も一緒に生成しましょう。
`Factory`でテストデータを生成する際には関連するモデルが必要な為です。

```sh
php artisan make:model --factory Link
```

LinkFactory.phpを開き、カスタマイズしていきます。
LinkFactory.phpは`database/factories/`にあります。


```php
<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Link::class, function (Faker $faker) {
    return [
        'title' => substr($faker->sentence(2), 0, -1),
        'url' => $faker->url,
        'description' => $faker->paragraph,
    ];
});
```

`title`部分でテストデータを生成する際に、`substr`メソッドを使っているのは、テストデータで生成される文字列の文末のピリオドを削除する為です。
次に、テストデータをテーブルに簡単に追加できるように `LinksTableSeeder`を作成します。


```sh
php artisan make:seeder LinksTableSeeder
```

新しく生成したseederファイルは`/database/seeds/`に生成されます。
作成したばかりのLinksTableSeeder.phpファイルを開き、`run`メソッドに上記で作成したリンクモデルファクトリを使用して、10人分のデータを用意するように記述します。

```php
public function run()
{
    factory(App\Link::class, 10)->create();
}
```

`DatabaseSeeder.php`を開き、runメソッドに追加します。

```php
public function run()
{
    $this->call(LinksTableSeeder::class);
}
```

これでテストデータを10人分用意する事ができました。
以下のコマンドでマイグレーションとシードを実行して、テーブルにテストデータを追加します。


```sh
php artisan migrate --seed
```

また`migrate:fresh`を使う事で、一旦tableにあるデータをリセットして、`seeder`で初期値データが入っている状態にする事もできます。

```sh
php artisan migrate:fresh --seed
```

[データベース：マイグレーション 5.6 Laravel 全テーブル削除後のマイグレーション](https://readouble.com/laravel/5.6/ja/migrations.html#rolling-back-migrations)

### ルーティングとビュー

リンクのリストを表示するビューを構築します。最初に`routes/web.php`ファイルを開き、以下のルートがあることを確認して下さい。


```php
Route::get('/', function () {
    return view('welcome');
});
```

最初の`localhost:8000`にアクセスした際の事を思い出して下さい。

ここでの処理は

```
①GETで/(root)にアクセスした際に
②viewヘルパ関数を使って resources/views/ にある welcome.blade.php を呼び出しています。
```

従って最初のwelcomeページの正体は`welcome.blade.html`だとここで気付きます。

ここにリンクリストを取得するためのコードを追加してみましょう。

```php
Route::get('/', function () {
    $links = \App\Link::all();
    return view('welcome', ['links' => $links]);
});
```

ここでは上から

```
①GETで/にアクセスした時に　
②Linksモデル(Eloquent)のallメソッドを使って、取得した全てのデータを$linksに代入する。
③view()を使って第一引数にテンプレートのキー名(welcome.balede.html)を指定して、第二引数で$linksのデータをlinksとして渡す。
```

と処理をしています。
次に、`welcome.blade.php` ファイルを編集し、単純な`foreach`を追加してすべてのリンクを表示します。

```php
@foreach ($links as $link)
  <li>{{ $link->title }}</li>
@endforeach
```

最終的に`welcome.blade.php`は以下のようになります。

```welcome.blade.php
<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        @endif

        <div class="content">
            <div class="title m-b-md">
                Laravel
            </div>

            <div class="links">
                @foreach ($links as $link)
                    <a href="{{ $link->url }}">{{ $link->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
</body>
```

ブラウザを更新すると追加されたすべてのリンクのリストが表示され、先程ModelFactoryとSeedsで生成したテストデータが表示されるのが確認できます。

![リンク.png](https://qiita-image-store.s3.amazonaws.com/0/64829/4991290d-e644-cd66-7fe4-fb2d552990d1.png)

これでLaravelを使った最初のアプリケーションの作成は終わりました。

## 投稿フォーム

次の実装する大きな機能は、フォームからリンクを追加できるようにする事です。
これには、タイトル、URL、説明の3つのフィールドが必要です。

リンクリストに必要な要件を洗い出します。

- タイトル
- URL
- 説明

次に今回作成するフォームの簡単な図です。

![laravel_mock.png](https://qiita-image-store.s3.amazonaws.com/0/64829/936e0d41-4504-85ab-caec-8d7952045123.png)


まず、`routes/web.php`に新しい投稿フォーム用のルーティングを作成します。

```php
Route::get('/submit', function () {
    return view('submit');
});
```

ここでの処理も先程同様に

```
①GETで /submit にアクセスした時に　
②viewヘルパ関数を使って resources/views/ にある submit.blade.php を呼び出しています。
```

ここからは実際にテンプレート元になる`default.blade.php`と、投稿フォーム用の`submit.blade.php`を作ります。

なぜ2つのファイルを用意するのか?というと、LaravelのViewで使われる[Bladeテンプレート](https://readouble.com/laravel/5.6/ja/blade.html)は共通部分をテンプレート化して、そのテンプレートを利用して継承する事ができます。
詳しくは次の章で説明します。

### Bladeテンプレートの概要説明

`submit.blade.php`を作成する前にここで、簡単な`Bladeテンプレート`の利点である継承とセクションについて紹介します。実際に例をみてみましょう。


```app.blade.php
<!-- resources/views/layouts/app.blade.phpとして保存 -->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <!-- Bootstrap4 CDN -->
    <title>@yield('title')</title>
</head>
<body>
    {{-- コンテンツの表示 --}}
    <div class="container">
        @yield('content')
    </div>
</body>
```

`app.blade.php`は親テンプレートになります。
ここで大事なので`@yield('title')`と`@yield('content')`です。これはセクション機能と呼ばれます。この親テンプレート(`default.blade.php`)を継承する事で、継承先の子テンプレートで`@sectionディレクティブ`を定義する事により、指定した内容を表示する事ができます。

例えばこんな感じに`app.blade.php`を継承した子テンプレート(`default.blade.php`)を用意するとします。

```default.blade.php
<!-- resources/views/default.blade.phpとして保存 -->

<!-- 親元のテンプレートを継承する。 -->
@extends('layouts.app')


@section('title', 'Laravelチュートリアル')

@section('content')
  <p>ここが本文のコンテンツになります。</p>
@endsection
```

この用に定義する事で、子テンプレートは親テンプレートを継承しているので読み込まれる際はコードが置き換えられて表示されるようになります。


```html
<html>
    <head>
        <title>Laravelチュートリアル</title>
    </head>
    <body>
        <div class="container">
           <p>ここが本文のコンテンツになります。</p>
        </div>
    </body>
</html>
```

上記のように、子テンプレートは、親テンプレートを継承する事で少ない記述で済み、変更箇所のみテンプレートに書けばいいので、シンプルで分かりやすく、保守しやすいViewファイルが作られるようになっています。

### 投稿フォームを作成する

先程作成した`default.blade.php`を親テンプレートとして使います。
親テンプレートを継承する際は`@extends`セクションを使ってください。


`resources/views/submit.blade.php`に以下のコードを追加して作成して下さい。


```php
@extends('default')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Submit a link</h1>
            <form action="/submit" method="post">
                {!! csrf_field() !!}

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                </div>

                <div class="form-group">
                <label for="url">Url</label>
                    <input type="text" class="form-control" id="url" name="url" placeholder="URL">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" placeholder="description"></textarea>
                </div>

                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
@endsection
```



`localhost:8000/submit`にアクセスして以下のフォーム画面が表示される事を確認して下さい。

![リンク.png](https://qiita-image-store.s3.amazonaws.com/0/64829/4991290d-e644-cd66-7fe4-fb2d552990d1.png)

## 投稿フォームのバリデーションとルーティング

ここでは一気にルーティングからフォームの検証とモデル操作を1つのファイルで一気に処理しています。
再度``routes/web.php`を開いて、以下の処理を追加させます。


```php
use Illuminate\Http\Request;

Route::post('/submit', function (Request $request) {
    $data = $request->validate([
        'title' => 'required | max:255',
        'url'  => 'required | url | max:255',
        'description' => 'required | max:255',
    ]);

    $link = new App\Link($data);
    $link->save();

    return redirect('/');
});
```

上から処理をみていきましょう。

```
①POSTで/submitにアクセスする。
②validateメソッドを使ってバリデーションを行う
エラーが発生した場合、、セッションにエラーメッセージをフラッシュデータとして保存します。
③バリデーションの検証が通ったらLinkモデルを生成してフォームに投稿されたデータをDBに保存する。
④その後に/(root)にリダイレクトさせる。
```

②の`validate`メソッドはLaravel 5.5で追加されたメソッドです。
バリデーションルールに成功すると、コードは通常通り続けて実行されます。
逆にバリデーションに失敗すると、例外が投げられ、ユーザーに対し自動的に適切なエラーレスポンスが返されようになっています。

>[バリデーション 5.6 Laravel](https://readouble.com/laravel/5.6/ja/validation.html#quick-writing-the-validation-logic)

上の処理を意識してもう一度コードを見てみましょう。


```php
use Illuminate\Http\Request;

// ①POSTで/submitにアクセスする。
Route::post('/submit', function (Request $request) {
    // ②validateメソッドを使ってバリデーションを行う
    $data = $request->validate([
        'title' => 'required | max:255',
        'url'  => 'required | url | max:255',
        'description' => 'required | max:255',
    ]);

    // ③ Linkモデルを生成
    $link = new App\Link($data);
    $link->save();

    // リダイレクトする
    return redirect('/');
});
```

これで投稿フォームのバリデーションの準備はできたので、次に送られてきた値を元にデータベースに値を保存する為に、Linkモデルを調整していきます。

Laravelでは既存の機能で、リクエストで送られてきた値を同時に保存する`mass-assigned`を防ぐために、DBに保存する値を`fillable`プロパティで決める事ができます。
以下のように`fillabl`を使用して保存可能なフィールドを定義します。

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'title',
        'url',
        'description'
    ];
}
```

これで、投稿フォームから送られたリンクが新規で作成されるようになります。


## リファクタリング

ここから先はリファクタリングの話になります。
私がこれを見たときは真っ先になぜ複雑なバリデーションの処理を`routes/web.php`に責務を追わせているのかと考えました。
というのは、web.phpはあくまでもルーティング処理を任せて、ここでモデル操作や、バリデーションをするのは、適切ではないと感じたのです。

従ってここでは上のMVCパターンのようContrller側でモデルを呼び出しビジネスロジックを担保し、Requestでバリデーションを行うようにリファクタリングをしてみます。

### バリデーション:LinkRequest

バリデーションを記述するRequestファイルを生成します。

```
php artisan make:request LinkRequest
```

`app/Http/Requests/`に`LinkRequest.php`が新し生成されました。
rulesメソッドに先程ルーティンで書いたバリデーションを`rule`メソッドに記述します。

```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class LinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'url' => 'required|max:255|',
            'description' => 'required|max:255',
        ];
    }
}
```

気づいたと思いますが検証が失敗した場合の`$validator->fails()`の処理が記述されてないです。
Laravelはバリデーションで通らなかった場合、自動的にユーザを直前にアクセスしたページヘリダイレクトします。
付け加えて、バリデーションエラーは全て自動的にフラッシュデータとしてセッションへ保存されます。従って明示的に宣言しなくてみいつでもビューの中で`$errors`変数が使えるようになっています。

>- [Laravel 5.6 バリデーション](https://readouble.com/laravel/5.6/ja/validation.html)

### モデル操作:LinkController

次に実際にフォームからのデータを受け取ってモデル操作を行うLinkControllerを作成します。

```
php artisan make:controller LinkController
```

`LinkController`が生成されたら以下のよう処置を追加します。

```php
<?php
namespace App\Http\Controllers;
use App\Link;
use App\Http\Requests\LinkRequest;

class LinkController extends Controller
{
    public function submit(LinkRequest $request){
        $link = new Link();
        $link->title = $request->title;
        $link->url = $request->url;
        $link->description = $request->description;
        $link->save();
        return redirect('/');
    }
}
```

useを使って`App\Http\Requests\LinkRequest;`を名前空間として呼び出している事に注意して下さい。
ここでは新しくsubmitメソッドを作って、先程作成した`LinkRequest`をタイプヒントで指定する事でバリデーションの検証を行う事ができます。検証が通った後はモデル操作を行います。


### ルーティング:web.php

先程のルーティングで処理していた役割をそれぞれ、`LinkController`と`LinkRequest`で関心の分離をした事でルーティングはこの用に綺麗になりました。

```php
use Illuminate\Http\Request;
Route::post('/submit','LinkController@submit');
```

```
①postで/submitにアクセスした際に、LinkControllerのsubmitアクションを呼び出す。
```

これで以前よりも随分簡潔になりました
今回のケースの場合はアクションが`submit`のみなので、リファクタリングする前ではルーティングで全ての処理をまとめる考えも間違ってはないのですが、今後リンクを消したり、編集したり等のCRUD操作を導入するとなるとルーティングファイルが肥大化して可読性が下がってしまいます。

なので今回のように、ControllerとRequestで処理を分離させる手法を見せましたが、Laravelを知れば知る程このように関心の分離が容易に実現できる事に魅力を感じてくると思います。　　
是非興味があれば、Laravelの醍醐味であるDIコンテナについて調べると良いでしょう。

>- [ララ帳:DIコンテナについての素晴らしい解説記事](https://laravel10.wordpress.com/tag/di/)

## 投稿フォームのテスト

投稿フォームが完成しバリデーションも追加したので、ここで投稿フォームでのバリデーションが正しく動作する事を担保するFeatureテストを追加しましょう。
Laravelでは標準で`phpunit`が入っているので、普段PHPのアプリケーションで書いてる時と同じ要領でテストが実行できます。

最初にデフォルトで存在するテストファイルを削除します。

```sh
rm tests/Feature/ExampleTest.php
```

次に以下のコマンドを実行して、新しく投稿フォーム用のテストファイルを生成します。

```sh
php artisan make:test SubmitLinksTes
```

生成されたテストファイルに以下のように予め今回テストするテストケースを追加します。

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitLinkTest extends TestCase
{
    /** @test */
    function guest_can_submit_a_new_link() {}

    /** @test */
    function link_is_not_created_if_validation_fails() {}

    /** @test */
    function link_is_not_created_with_an_invalid_url() {}

    /** @test */
    function max_length_fails_when_too_long() {}

    /** @test */
    function max_length_succeeds_when_under_max() {}
}
```
これらのテストこれから記述していくテストコードの概要を示してます。

- 1.有効なリンクがデータベースに保存されることを確認する
- 2.バリデーションに失敗すると、リンクはデータベースに保存されない
- 3.有効でないリンクはバリデーションで失敗する
- 4.フィールドに入力された文字がmax:255よりも長い場合は失敗する
- 5.フィールドに入力された文字がmax:255より少ない場合は成功する


では1つ1つのテストケースを通していきましょう。


### 有効なリンクは保存できる場合のテスト

最初に行うテストでは有効なリンクがデータベースに保存されることを確認するテストを実行します


```php
<?php

namespace Tests\Feature;

use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitLinksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guest_can_submit_a_new_link()
    {
        $response = $this->post('/submit', [
            'title' => 'Example Title',
            'url' => 'http://example.com',
            'description' => 'Example description.',
        ]);

        $this->assertDatabaseHas('links', [
            'title' => 'Example Title'
        ]);

        $response
            ->assertStatus(302)
            ->assertHeader('Location', url('/'));

        $this
            ->get('/')
            ->assertSee('Example Title');
    }
}
```

`RefreshDatabase`トレイトを使う事でテスト後にデータベースをリセットできる為、テストデータに影響を及ぼさないようにする事が可能です。
ここでは最初のテストで`$this->post`を使って、有効なデータを`/submit`でpostした際に、レスポンスで帰ってくるオブジェクト(`$response`)を使ってルーティングが期待どおりになっているかを確認しています。

`assertDatabaseHas`は指定したカラムに該当するデータが存在しているかを確認しています。

次に、`assertStatus`を使って返ってくるレスポンスのでステータスコードが正しいかを確認しています。
`302`なので、正常にリダイレクトされている事を期待しています。
最後に、`assertSee`を使ってホームページをリクエストして、その後ホームページにリンクのタイトルが表示されていることを確認しています。

この状態でまずはテストを実行してみます。

```sh
$./vendor/bin/phpunit ./tests/Feature/SubmitLinkTest.php

.                                1 /1 (100%)

Time: 146 ms, Memory: 16.00MB

OK (1 test, 5 assertions)
```

こんな感じでテストを実行していきます。
このテストケースでは投稿フォームで送信されたリンクが正しくデータベースに保存されて、リダイレクトされる事がわかったので、次のテストケースに進みます。

### バリデーション失敗時のテスト

ユーザーが一般的に不正なデータを送信すると、バリデーション時に例外が発生するのでこれを使ってバリデーション時の機能が動作しているかを確認する事ができます。

```php
/** @test */
function link_is_not_created_if_validation_fails()
{
    $response = $this->post('/submit');

    $response->assertSessionHasErrors(['title', 'url', 'description']);
}
```

`assertSessionHasErrors`を使用して各フィールドに対してバリデーションエラーがあることを確認します。
ここで行っているテストはルーティングには空のデータが投稿された場合の為、各フィールドに対して必須項目のバリデーションで落ちる事を確認できます。

### URLバリデーション時のテスト

ここのテストケースでは有効なURLのみがバリデーションを通過して、アプリケーション側で無効なデータが表示されない事を期待します。

```php
/** @test */
function link_is_not_created_with_an_invalid_url()
{
    $this->withoutExceptionHandling();

    $cases = ['//invalid-url.com', '/invalid-url', 'foo.com'];

    foreach ($cases as $case) {
        try {
            $response = $this->post('/submit', [
                'title' => 'Example Title',
                'url' => $case,
                'description' => 'Example description',
            ]);
        } catch (ValidationException $e) {
            $this->assertEquals(
                'The url format is invalid.',
                $e->validator->errors()->first('url')
            );
            continue;
        }

        $this->fail("The URL $case passed validation when it should have failed.");
    }
}
```

`withoutExceptionHandling()`に注目してください。
これを追加する事で、Laravle側でHTTPレスポンスを生成する際の例外処理が原因で、それ以降のテストが実行できないのを防ぐ事ができます。

ここでは`foreach`を使って使ってさまざまなケースを試しています。
誤ったテキストがpostされると、そのたびに`ValidationExcepiton`例外が発生して手動で落ちるようになっています。

エラーが発生した場合は、`assertEquals`を使って表示されるエラーメッセージが期待しているものと一致しているかを確認しています。

### MaxLengthバリデーション時のテスト

次に `max：255`のバリデーションルールに関するテストケースを用意して、期待通りに動作するか確認します。
フィールドが長さ `256`文字の最大長の場合はバリデーションに失敗し、フィールドが`255`文字以下の場合はバリデーションが通る事を確認します。
最小と最大のバリデーションルールのしきい値をテストして、アプリケーションが設定した最小値と最大の境界を担保しているかどうかをテストケースで実装していきます。

```php
/** @test */
function max_length_fails_when_too_long()
{
    $this->withoutExceptionHandling();

    $title = str_repeat('a', 256);
    $description = str_repeat('a', 256);
    $url = 'http://';
    $url .= str_repeat('a', 256 - strlen($url));

    try {
        $this->post('/submit', compact('title', 'url', 'description'));
    } catch(ValidationException $e) {
        $this->assertEquals(
            'The title may not be greater than 255 characters.',
            $e->validator->errors()->first('title')
        );

        $this->assertEquals(
            'The url may not be greater than 255 characters.',
            $e->validator->errors()->first('url')
        );

        $this->assertEquals(
            'The description may not be greater than 255 characters.',
            $e->validator->errors()->first('description')
        );xw
        return;
    }

    $this->fail('Max length should trigger a ValidationException');
}
```

各フィールドに`max length`のバリデーション時のエラーメッセージがあることを確認するために、各フィールドに対して`assertEquals`を使って確認しています。
最後に例外処理が発生した事を`$this->fail()`を使って確認します。

次に`max length`よりも少ない場合のテストケースを作成します。

```php
/** @test */
function max_length_succeeds_when_under_max()
{
    $url = 'http://';
    $url .= str_repeat('a', 255 - strlen($url));

    $data = [
        'title' => str_repeat('a', 255),
        'url' => $url,
        'description' => str_repeat('a', 255),
    ];

    $this->post('/submit', $data);

    $this->assertDatabaseHas('links', $data);
}
```

このテストケースでは`max：255`バリデーションで通る長さのデータを作成して、データを送信した後にデータがデータベースに格納されている事を確認します。

最後にこれらの作成したテストを実行して結果を確認します。

```php
$./vendor/bin/phpunit ./tests/Feature/SubmitLinkTest.php
PHPUnit 7.4.5 by Sebastian Bergmannand contributors.

.....                                5 /5 (100%)

Time: 176 ms, Memory: 16.00MB

OK (5 tests, 16 assertions)
```

お疲れ様です。
以上で投稿フォームを担保するテストコードを追加する作業は終わりです。


### おわりに

チュートリアルを完了したことを祝福します。
このガイドは最初にも述べたように、[はじめてのLaravelアプリケーションを構築する為のStep by Step Guide](http://qiita.com/Fendo181/items/55701abc11c205b9c057)を元に、改良したチュートリアル記事です。
この記事以外にもチュートリアルを探せばいくらでも出て来るので、是非このチュートリアルをきっかけに他のLaravelでのアプリケーション開発に興味を持って頂ければ幸いです。
