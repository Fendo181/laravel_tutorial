# はじめてのLaravelアプリケーションガイド


## はじめに

この記事は[はじめてのLaravelアプリケーションを構築する為のStep by Step Guide](http://qiita.com/Fendo181/items/55701abc11c205b9c057)を元に、改良したチュートリアル記事です。一部内容が被る部分はありますがLaravelを初めて触る人向けに少しだけ説明を加えています。

今回ここで作成するwebアプリケーションは以下の機能を持ったリンク投稿アプリになります。

- **1.リンクの簡単なリストを表示します。**
- **2.新しいリンクを提出できるフォームを作成します。**
- **3.フォームを検証する**
- **4.検証が通ったらデータをデータベースに保存します。**


このような簡単なwebアプリケーションをこのチュートリアルでは一つ一つ機能を紹介しながら説明していきます。しかし私がこのチュートリアルで一番に掲げる目的は、**今まさにフレームワークを勉強し始めた人たちのためのガイドを作ることです**。従って、Laravelの高度な機能や説明に関してはこのチュートリアルでは詳細に紹介しません。あくまでも、このチュートリアルはLaravelを始めるきっかけの1つとして作りました。


## 環境構築

まずはLaravelをローカル環境にインスールする作る作業からはじめます。
プロジェクトをすぐに始める為にLaravelではVagrantのboxで最初から自動で必要なファイルを用意してくれている[Laravel Homestead](https://laravel.com/docs/5.4/homestead)や、Mac向けに用意された[Valet](https://laravel.com/docs/5.4/valet)が用意されています。

>- [Laravel Homestead](https://laravel.com/docs/5.4/homestead)
>- [Valet](https://laravel.com/docs/5.4/valet)

また上記の方法以外にも、Dockerを使った[Laradock](https://github.com/laradock/laradock)という手段もあります。もし、これらの手段を使わないのであればLaravelは以下の要件を満たす環境でなければならない事に注意して下さい。

- PHP >= 5.6.4
- OpenSSL PHP拡張
- PDO PHP拡張
- Mbstring PHP拡張
- Tokenizer PHP拡張
- XML PHP拡張

>- [インストール](https://readouble.com/laravel/5.4/ja/installation.html)

ここではそれぞれの方法での詳細なインスール手順は紹介しませんが、もし不安でしたら以下に手順を書いた記事のリンクを紹介するので迷ったらここを覗いて見てください。


>- [Laravel Valetをつかってみた。](http://qiita.com/nkumag/items/3ba39749e5ad59ede1f5)
>- [Laravel Homestead](https://laravel.com/docs/5.4/homestead)
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

_(※既に[LaravelHomStead](https://laravel.com/docs/5.4/homestead)をインスールしているのであればここは既にインスール済みなので飛ばして大丈夫です。)_


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

リンクリストを作成する前に私は、ここで一つMVCに関する簡単な講義を始めようと思います。何故、いきなりMVCについて説明をするかというと、今回のチュートリアルで作るLinkアプリケーション自体が最終的にMVCパターンの沿って実装されるからです。従って最初にこの概念が無い人にとっては、理解が難しくなってしまうので、ここでは軽くMVCパターンについて説明します。

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

laravelでは新しく作成するControllerは`app/controller`ディレクトリ直下に生成されます。Viewファイルは`resources/views`直下に配置します。

ではModelはと言うと?ここがLaravelの特徴でもあるのですが、Laravelは`models`に相当するディレクトリがないです。理由は[ここ](https://readouble.com/laravel/5.4/ja/structure.html)に詳細に記載されているのですが、これは意図的に設計されています。なので、最初は戸惑うと思いますが、新しく`Model`を作成した場合は`app`直下に作られる事を覚えておいて下さい。


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

>- [モデルファクトリー](https://readouble.com/laravel/5.1/ja/testing.html#model-factories)
>- [Seeder](https://readouble.com/laravel/5.1/ja/seeding.html)

ここでは2つの機能を使う事でテストデータを作成するModelFactoryを定義し、SeederからModelFactoryを利用することでテストデータを作成する方法を紹介します。

ModelFactory.phpを開き、リンクテーブルにファイルを追加しましょう。
ModelFactory.phpは`database/factories/`にあります。


```php
$factory->define(App\Link::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'url' => $faker->url,
        'description' => $faker->paragraph,
    ];
});
```



次に、テストデータをテーブルに簡単に追加できるようにLinksTableSeederを作成します。


```
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


DatabaseSeeder.phpを開き、runメソッドに追加します。


```php
public function run()
{
　    $this->call(LinksTableSeeder::class);
    }
```

これでテストデータを10人分用意する事ができました。

以下のコマンドでマイグレーションとシードを実行して、テーブルにテストデータを追加します。


```php
php artisan migrate --seed
```


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
①GETで`/`(root)にアクセスした際に
②viewヘルパ関数を使って`resources/views/`にある`welcome.blade.php`を呼び出しています。
```

従って最初のwelcomeページの正体はwelcome.blade.htmlだとここで気付きます。

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
③welcome.balede.htmlに$linksのデータをlinksとして渡す。
```

と処理をしています。
次に、welcome.blade.phpファイルを編集し、単純な`foreach`を追加してすべてのリンクを表示します。

```php
<div class="content">
  <div class="title m-b-md">
    Laravel
  <div class="title m-b-md">        
@foreach ($links as $link) 
  <li>{{ $link->title }}</li>
@endforeach
```

ブラウザを更新すると、追加されたすべてのリンクのリストが表示され、先程ModelFactoryとSeedsで生成したテストデータが表示されるのが確認できます。

![リンク.png](https://qiita-image-store.s3.amazonaws.com/0/64829/4991290d-e644-cd66-7fe4-fb2d552990d1.png)



## 投稿フォーム

次の実装する大きな機能は、フォームからリンクを追加できるようにする事です。 これには、タイトル、URL、説明の3つのフィールドが必要です。

リンクリストに必要な要件を洗い出します。

- URLのタイトル
- URL
- 説明



フォームの簡単な図です。

![laravel_mock.png](https://qiita-image-store.s3.amazonaws.com/0/64829/936e0d41-4504-85ab-caec-8d7952045123.png)


まず、`routes / web.php`ファイルに新しいルーティングを作成します。**
 
```php
Route::get('/submit', function () {
    return view('submit');
});
```

ここでの処理も先程同様に

```
①GETで`/submit`にアクセスした時に　
②viewヘルパ関数を使って`resources/views/`にある`submit.blade.php`を呼び出しています。
```

ここからは実際にテンプレート元になる`default.blade.php`と、投稿フォーム用の`submit.blade.php`を作ります。

なぜ2つのファイルを用意するのか?というと、LaravelのViewで使われる[Bladeテンプレート](https://readouble.com/laravel/5.6/ja/blade.html)は共通部分をテンプレート化して、そのテンプレートを利用して継承する事ができます。詳しくは次の章で説明します。

### Bladeテンプレートの概要説明

`submit.blade.php`を作成する前にここで、簡単な`Bladeテンプレート`の利点である継承とセクションについて紹介します。実際に例をみてみましょう。

```default.blade.php
<!-- resources/views/layouts/app.blade.phpとして保存 -->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
</head>
<body>
    {{-- コンテンツの表示 --}}
    <div class="container">
        @yield('content')
    </div>
</body>
```

`default.blade.php`は親テンプレートになります。
ここで大事なので`@yield('title')`と`@yield('content')`です。これはセクション機能と呼ばれます。この親テンプレート(`default.blade.php`)を継承する事で、継承先の子テンプレートで`@sectionディレクティブ`を定義する事により、指定した内容を表示する事ができます。

例えばこんな感じに`default.blade.php`を継承した子テンプレート(`app.blade.php`)を用意するとします。

```app.blade.php
# 親元のテンプレートを継承する。
@extends('layouts.default')


@section('title', 'Laravelチュートリアル')

@section('content')
  <p>ここが本文のコンテンツになります。</p>
@endsection
```

この用に定義する事で、子テンプレートは親テンプレートを継承しているので、読み込まれる際は以下のようにコードが置き換えられて、表示されるようになります。

```app.php
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

では次の投稿フォーム同じ要領で`default.blade.php`を継承して作成してみまましょう。

### 投稿フォームを作成する

親テンプレートは先程作成した`default.blade.php`を使います。
`resources/views/submit.blade.php`に以下のコードを追加して作成して下さい。


```php
#<!-- resources/views/submit.blade.php.blade.phpとして保存 -->
@extends('layouts.default')

@section('content')
<div class="row">
    <h1>Submit</h1>
    <form action="/submit" method="post">
        {!! csrf_field() !!}
        <div class="form-groupe">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ old('title') }}">
            @if ($errors->has('title'))
                <div class="alert alert-danger">{{ $errors->first('title') }}</div>
            @endif
        </div>
        <div class="form-group">
           <label for="url">Url</label>
           <input type="text" class="form-control" id="url" name="url" placeholder="URL" value="{{ old('url') }}">
           @if ($errors->has('url'))
               <div class="alert alert-danger">{{ $errors->first('url') }}</div>
           @endif
       </div>
       <div class="form-group">
           <label for="description">Description</label>
           <textarea class="form-control" id="description" name="description" placeholder="description">{{ old('description') }}</textarea>
           @if ($errors->has('description'))
               <div class="alert alert-danger">{{ $errors->first('description') }}</div>
           @endif
       </div>
       <button type="submit" class="btn btn-success">Submit</button>
       <a href="{{ url('/') }}" class="btn btn-default" role="button">Back</a>
    </form>
@endsection
```

`localhost:8000/submit`にアクセスして以下のフォーム画面が表示される事を確認して下さい。
 
![form.png](https://qiita-image-store.s3.amazonaws.com/0/64829/46dba030-3aac-0056-e07d-4a26a019bcb6.png)




## 投稿フォームのバリデーションとルーティング

ここでは一気にルーティングからフォームの検証とモデル操作を1つのファイルで一気に処理しています。
再度``routes/web.php`を開いて、以下の処理を追加させます。


```php
use Illuminate\Http\Request;

 Route::post('/submit', function(Request $request) {

    $validator = Validator::make($request->all(), [
        'title' => 'required|max:255',
        'url' => 'required|max:255|url',
        'description' => 'required|max:255',
    ]);
    if ($validator->fails()) {
        return back()
            ->withInput()
            ->withErrors($validator);
    }
    $link = new \App\Link;
    $link->title = $request->title;
    $link->url = $request->url;
    $link->description = $request->description;
    $link->save();
    return redirect('/');
});
```

上から処理をみていきましょう。

```
①POSTで/submitにアクセスする。
②バリデーションチェック
③バリデーションでエラーが発生した場合、、セッションにエラーメッセージをフラッシュデータとして保存して元のページへリダイレクトさせます。
④バリデーションの検証が通ったらLinkモデルを操作してフォームに投稿されたデータをDBに保存する。
⑤その後に/(root)にリダイレクトさせる。
```

上の処理を意識してもう一度コードを見てみましょう。


```php
use Illuminate\Http\Request;
 
 // ①POSTで/submitにアクセスする。
 Route::post('/submit', function(Request $request) {
     
    // ②バリデーションチェック 
    $validator = Validator::make($request->all(), [
        'title' => 'required|max:255',
        'url' => 'required|max:255|url',
        'description' => 'required|max:255',
    ]);
    // ③バリデーションでエラーが発生した場合、、セッションにエラーメッセージをフラッシュデータとして保存して元のページへリダイレクトさせます。
    if ($validator->fails()) {
        return back()
            ->withInput()
            ->withErrors($validator);
    }
    // ④バリデーションの検証が通ったらLinkモデルを操作してフォームに投稿されたデータをDBに保存する。
    $link = new \App\Link;
    $link->title = $request->title;
    $link->url = $request->url;
    $link->description = $request->description;
    $link->save();
    // ⑤その後に/(root)にリダイレクトさせる。
    return redirect('/');
});
```

## リファクタリング

ここから先はリファクタリングの話になります。
私がこれを見たときは真っ先になぜ複雑な処理を`routes/web.php`に責務を追わせているのかと考えました。
というのは、web.phpはあくまでもルーティング処理を任せて、ここでモデル操作や、バリデーションチェックをするのは、適切ではないと感じたのです。

従ってここでは上のMVCパターンのようContrllerでモデルを操作を行い、Requestでバリデーションチェックを行うようにリファクタリングをしてみます。

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
Laravelはバリデーションで通らなかった場合、自動的にユーザを以前のページヘリダイレクトします。
付け加えて、バリデーションエラーは全部自動的にフラッシュデータとしてセッションへ保存されます。従って明示的に宣言しなくてみいつでもビューの中で$errors変数が使えます。

>- [Laravel 5.4 バリデーション](https://readouble.com/laravel/5.4/ja/validation.html)



### モデル操作:LinkController

次に実際にフォームからのデータを受け取ってモデル操作を行うLinkControllerを作成します。

```
php artisan make:controller LinkController
```

上でuseで`App\Http\Requests\LinkRequest;`を名前空間として呼び出している事に注意して下さい。
ここでは新しくsubmitメソッドを作って、先程作成した`LinkRequest`をタイプヒントで指定する事でバリデーションの検証を行う事ができます。検証が通った後はモデル操作を行います。

```php
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

#LinkRequest 
use App\Http\Requests\LinkRequest;

class LinkController extends Controller
{
    public function submit(LinkRequest $request){
        $link = new \App\Link;
        $link->title = $request->title;
        $link->url = $request->url;
        $link->description = $request->description;
        $link->save();
        return redirect('/');
    }
}
```


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
今回のケースの場合はアクションが`submit`のみなので、リファクタリングする前のように、ルーティングファイル一箇所で処理をまとめる考えも間違ってはないのですが、今後リンクを消したり、編集したり等のCRUD操作を導入するとなると、ルーティングファイルが肥大化して可読性が下がってしまいます。

ここではControllerとRequestで処理をぶんりさせる手法を見せましたが、Laravelを知れば知る程、このように関心の分離が容易に実現できる事に魅力を感じてくると思います。是非興味があれば、Laravelの醍醐味であるDIコンテナについて調べると良いでしょう。

>- [ララ帳:DIコンテナについての素晴らしい解説記事](https://laravel10.wordpress.com/tag/di/)


## テスト

後日公開


### おわりに

チュートリアルを完了したことを祝福します。 
このガイドは最初にも述べたように、[はじめてのLaravelアプリケーションを構築する為のStep by Step Guide](http://qiita.com/Fendo181/items/55701abc11c205b9c057)を元に、改良したチュートリアル記事です。この記事以外にもチュートリアルを探せば、いくらでも出て来るので、是非このチュートリアルをきっかけに他のLaravelでのアプリケーション開発に興味を持って頂ければ幸いです。



