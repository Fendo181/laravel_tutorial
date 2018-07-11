# はじめてのLaravelアプリケーションを構築する為のStep by Step Guide

この記事は [Step by Step Guide to Building Your First Laravel Application](https://laravel-news.com/your-first-laravel-application)を翻訳したチュートリアル記事です。日本語の翻訳ならびに、記事の公開、コードの引用は製作者である[Eric L. Barnes](https://twitter.com/ericlbarnes)氏に既に許可を取ってあります。


Thank you for making great tutorial articles!

※追記
こちらにチュートリアルに説明を加え、改良した記事を公開したので
良かったらこちらもご覧下さい。

- [はじめてのLaravelアプリケーションガイド](http://qiita.com/Fendo181/items/dece727ea402552fee19)

___



## **はじめに**

>Since its initial release in 2011, Laravel has experienced exponential growth. In 2015, it became the most starred PHP framework on GitHub and rose to the go-to framework for people all over the world.

**2011年の最初のリリース以来、Laravelは急激な成長を経験してきました。 2015年には、LaravelはGithubでもっとも花形のPHPフレームワークになり、世界中の人々のための頼りになるフレームワークになるまで出世しました。**

>Laravel focuses on you, the end user, first which means its focus is on simplicity, clarity, and getting work done. People and companies are using it to build everything from simple hobby projects all the way to Fortune 500 companies.

**Laravelはあなた、つまりエンドユーザーに焦点を当てています。これはまず、シンプルさ、明快さ、作業の効率化に重点を置いていることを意味します。人々や企業は、単純な趣味のプロジェクトからフォーチュン500に出て来る企業まで、あらゆるものを構築するためにこれを使用しています。**

_※フォーチュン500（Fortune 500）は、アメリカ合衆国のフォーチュン誌が年1回編集・発行するリストの1つで、全米上位500社がその総収入に基づきランキングされる企業が記載されています。_

>My goal with this is to create a guide for those just learning the framework. This guide will take you from the very beginning of an idea into a real deployable application.

**これを書いた私の目的は、今まさにフレームワークを勉強し始めた人たちのためのガイドを作ることです。このガイドでは、アイデアの初めから実際の展開可能なアプリケーションまで導きます。**

>This look at Laravel will not be exhaustive, covering every detail of the framework, and it does expect a few prerequisites. Here is what you will need to follow along:

**Laravelのこの見方(前述に記述されている目標や方向性)は完璧ではないですし、フレームワークのを細部を補ってない為、Laravelを動かすために、いくつか用意するものがあります。**

- **ローカルPHP環境（Valet, Homestead, Vagrant, MAMP, etc.）。**
- **データベース。私はMySQLを使用します。**
- **PHPUnitがインストールされている。**
- **Nodeがインストールされている。**


>Note: For the local PHP environment I am using a Mac and like to use Valet because it automatically sets up everything. If you are on Windows, you should consider Homestead or some flavor of a virtual machine.



**ローカルPHP環境について、私はMacを使っていて自動的になんでもセットアップしてくれるので[Valet](https://laravel.com/docs/5.4/valet)を使うのが好きです。もしあなたがWindowsなら、Homesteadか、ほかのお気に入りの仮想マシンにするかを熟考すべきです。**

>I am attempting to go through the process of creating a new application just as I would in a real world environment. In fact, the code and idea are taken from a project I built.

**実環境でいつも私がしているように、新しいアプリケーションを作る過程を始めようと思います。実際にここで紹介するコードやアイデアは私が構築したプロジェクトから得ています。**

## **計画**

>Every project has to start from somewhere, either assigned to you by your work or just an idea in your head. No matter where it originates, thoroughly planning out all the features before you start coding is paramount in completing a project.

**どんなプロジェクトでも、仕事によって割り当てられたか、もしくは頭の中のアイデアから始まります。最初がどうであれコーディングを開始する前にすべての機能を計画しておくことが、プロジェクトを完了する上で最も重要です。**

>How you plan is dependent on how your mind works. As a visual person, I like to plan on paper, drawing out the way I picture the screens looking and then working backward into how I’d code it out. Others prefer to write a project plan in a text file, wiki, or some mind mapping tool. It doesn’t matter how you plan, just that you do it.


**どのように計画するかは、あなたの心がどのように働くか次第です。目で見る方が早い私は紙の上に計画を立て、スクリーンを描き、それを裏側で動作するようにコードに落としこんでいきます。 他の人は、テキストファイルやwiki、もしくはマインド・マッピング・ツールなどに書き込むことを好みます。 しかし計画する方法など問題ではありません。**

>For this guide, we are going to be building a link directory. Here is a list of basic goals for this links app:

**このガイドでは、リンクディレクトリを構築する予定です。 このリンクアプリの基本的な目標リストは次のとおりです：**

- **1.簡単なリンクリストを表示する。**
- **2.新しいリンクを投稿できるフォームを作成する。**
- **3.フォームを検証する**
- **4.データをデータベースに保存する。**

## **最初のステップ**

>With a simple plan of attack outlined, it’s time to get a brand new empty project up and running. I like to put all my projects in a ~/Sites directory, and these instructions will use that location. I’ve already “parked” this directory in Valet, so any folders will automatically be mapped to “foldername.dev” in the browser.

**シンプルな計画をざっくり書くと、空のプロジェクトを使ってそこで始めてみましょう。 私はすべてのプロジェクトを〜/ Sitesディレクトリに入れたいと思っており、チュートリアルでこの場所を使用します。 私は既にこのディレクトリをValetに "展開"しているので、どのフォルダもブラウザの "foldername.dev"に自動的にマップされます。**

>Open your terminal application and switch into this directory.

**ターミナルアプリケーションを開き、このディレクトリに切り替えます。**


```bash
ccd ~/Sites
```

>Next, install Laravel’s command line installer:

**次に、Laravelのインストーラをインストールします。**

```bash
composer global require "laravel/installer"

```

>You need to make sure that the global Composer bin is in your path. You can do so by adding the following to your PATH in your ~/.bash_profile or ~/.zshrc if you are using Z shell:

**インストールしたComposerがPATHに含まれていることを確認する必要があります。`~/.bash_profile`または zシェルを使っている場合は `~/.zshrc` のPATHに次の行を追加してください。**

```bash
export PATH="$HOME/.composer/vendor/bin:$PATH"
```

>For the path to take effect, you need to restart your terminal session of source the file again:

**追加したパスを有効化させるために、再度terminal上で`source`コマンドを実行します。**

```bash
source ~/.bash_profile
```

>Now you can use the Laravel installer to create new projects from the command line:

**これで、Laravelインストーラを使ってコマンドライン上から新しいプロジェクトを作成することができます。**

```bash
laravel new links
```


>This will create a new directory at ~/Sites/links and install an stock Laravel project. Visiting links.dev in the browser now shows the default Laravel welcome page:
 
**新しく`~/Sites/links`ディレクトリが作られ、Laravelプロジェクトがインストールされています。 ブラウザーの "links.dev"を訪れると、デフォルトのLaravelのwelcomeページが表示されます：**

![laravel.png](https://qiita-image-store.s3.amazonaws.com/0/64829/6d3a23c6-a177-2482-3c08-e019ad883f1f.png)



>Now scaffold out the authentication system by running

**次のコマンドを実行して認証システムの土台を作りましょう。**

```bash
php artisan make:auth
```


>Even though this tutorial will not dive into authentication by running this command, it will modify our views and routes. So by doing it early, we don’t have to worry about it messing with any of our code.

**このチュートリアルでは、認証を行うことはありませんが、上のコマンドを実行する事でビューとルートが変更されます。従って早い段階で実行する事で、コードが乱れる心配をせずに済みます。**

>With the basics setup and working it’s time to start doing some coding.

**いよいよここから基本的なセットアップと作業で、コーディングを始める時が来ました。**


## **リンクリストの作成**

>If you start thinking about the whole finished project, it’s easy to get overwhelmed. The best way to fight this is to break everything down into small tasks. So, let’s start with showing a list of links.


**完成したプロジェクト全体について考え始めたとしたら、圧倒されるのは当然です。 これに取り組む最善の方法は、すべて小さな仕事に分割することです。 まずはリンクのリストを表示することから始めましょう。**


>Even though showing a list of links sounds like a small task it still requires a database, a database table, data in the table, a database query, and a view file.

**リンクのリストを表示することは小さなタスクのように聞こえますが、依然としてデータベース、データベーステーブル、テーブル内のデータ、データベースクエリ、およびビューファイルが必要です。**

>Creating a migration will be the first step, and the Laravel Artisan command line tool can help us create that.

**マイグレーションファイルを作成することが最初のステップとなり、Laravel Artisanコマンドラインツールがその作成に役立ちます。**

```bash
php artisan make:migration create_links_table --create=links
```

>Now, open the file this command created. It will be located at database/migrations/{{datetime}}_create_links_table.php

**次にこのコマンドで作成したファイルを開きます。 ファイルは`database/migrations/{{datetime}}_create_links_table.php`にあります。**


>Inside the up method add our new columns:

**メソッドの中に新しい列を追加します。**

```php
Schema::create('links', function (Blueprint $table) {
      $table->increments('id');
      $table->string('title');
      $table->string('url’)->unique();
      $table->text('description');
      $table->timestamps();
});
```
>Save the file and run the migration:

**ファイルを保存して、マイグレーションを実行します。**

```bash
php artisan migrae
```

>While you are working with test data, you can quickly apply the schema:

**一方でテストデータを実行するために、以下のコマンドを実行する事で素早く実行できる**

```bash
php artisan migrate:fresh
```


>Next, we need some data and a model to work with our database table. Laravel provides two features which help with this: the first is a database seeder, which populates the database with data, and second, the model factory files that allow us to generate fake model data that we can use to fill our development database and tests:

**次に、DBのtableからいくつかのデータとそれを扱うモデルが必要です。Laravelはこれを助ける2つの機能を提供します。 1つめはデータベースシードでこの機能はDBにデータを登録させる事ができます。2つめはモデルファクトリーで、この機能を使う事で開発用のDBに偽のモデルデータを生成する事ができます。**

```bash
php artisan make:model --factory Link
```

>The `--factory` flag will generate a new factory file in the `database/factories` path, in our case a new `LinkFactory` file will include an empty factory definition for our Link model.

**`--factory`フラグは`database/factories`に新しいファクトリーを生成し、今回のケースでは`LinkFactory`を生成されリンクモデルの空のファクトリ定義が含まれます。**


>Open the LinkFactory.php file and fill in the following:

**LinkFactory.phpを開き、次の情報を入力します。**

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

>We use the $faker->sentence() method to generate a title, and substr to remove the period at the end of the sentence.

**`$faker->sentence()`メソッドを使う事でテストデータのイトルを生成し、`substr`で文末のピリオドを削除します。**


>Next, create the link seeder, so we can easily add demo data to the table:

**次に、テストデータをテーブルに簡単に追加できるように`link seeder`を作成します。**

```bash
php artisan make:seeder LinksTableSeeder

```

>The make:seeder command generates a new database seeder class to seed our links table. Open the database/seeds/LinksTableSeeder.php file and add the following:

**`make:seeder`コマンドは新しいデータベースシーダクラスを生成して、リンクテーブル用の初期値設定を行います。`database/seeds/LinksTableSeeder.php`を開いて次に以下のようにコードを追加して下さい。**

```php
public function run()
{
    factory(App\Link::class, 5)->create();
}
```

>In order to “activate” the LinksTableSeeder, we need to call it from the main database/seeds/DatabaseSeeder.php run method:

**LinksTableSeederを"有効化"するために`database/seeds/LinksTableSeeder.php `の`run`メソッド内で`call`を実行する必要があります。**

```php
public function run()
{
    factory(App\Link::class, 10)->create();
}
```

>Open the DatabaseSeeder.php and add this to the run method:

**DatabaseSeeder.phpを開き、runメソッドに追加します：**


```php
$this->call(LinksTableSeeder::class);
```


>You can now run the migrations and seeds to automatically add data to the table:


**以下のコマンドでマイグレーションとシードを実行して、テーブルに自動的にテストデータを追加することができます。**


```php
php artisan migrate --seed
```



## ルーティングとビュー

>To build out a view showing the list of links first open the routes/web.php file and you should see the default route below:

**リンクのリストを表示するビューを構築するには、最初にroutes / web.phpファイルを開き、以下のデフォルトルートが表示されるはずです：**


```php
Route::get('/', function () {
    return view('welcome');
});
```

>Laravel provides us two options at this point. We can either add our code additions directly to the route closure, where the “return view..” is, or we can move the code to a controller. For simplicity let’s add our needed code to fetch the links directory in the closure.


**この時点で、Laravelは2つのオプションを提供します。 "return view.."と言う処理をroute closure内に直接追加することができ、またはこの処理をコントローラーに移動する事もできます。 簡単にするために、closure内のリンクディレクトリを取得するためのコードを追加してみましょう。**


```php

Route::get('/', function () {
    $links = \App\Link::all();
    return view('welcome', ['links' => $links]);
});

```

>Next, edit the welcome.blade.php file and add a simple foreach to show all the links:

**次に、welcome.blade.phpファイルを編集し、単純な`foreach`を追加してすべてのリンクを表示します。**

```php

@foreach ($links as $link) 
  <li>{{ $link->title }}</li>
@endforeach

```

>If you refresh your browser, you should now see the list of all the links added. With that all set, let’s move to submitting links.

**ブラウザを更新すると、追加されたすべてのリンクのリストが表示されます。 準備が整ったので、リンクを追加するフォームを加えてみましょう。**

![リンク.png](https://qiita-image-store.s3.amazonaws.com/0/64829/4991290d-e644-cd66-7fe4-fb2d552990d1.png)



## リンクの送信

>The next major feature is the ability for others to submit links into the app. This will require three fields: title, URL, and a description.

**次の実装する大きな機能は、フォームからリンクをアプリに追加できるようにする事です。 これには、タイトル、URL、説明の3つのフィールドが必要です。**

>I am a visual person and before planning out features that will require HTML I like to draw them out so I can get an idea of what I’m building in my head. Here is a simple drawing of this form:

**私は先程も述べたように目で見て覚えるタイプなので、HTMLを必要とする機能を計画する前に絵を書いて頭の中で何を構築しているのかを引き出す事が好きです。 以下はこのフォームの簡単な図です：**

_※この絵は[元の絵](https://i2.wp.com/wp.laravel-news.com/wp-content/uploads/2016/03/form-drawing.jpg?resize=768%2C857)を参考にして私自身が書きました。_

![laravel_mock.png](https://qiita-image-store.s3.amazonaws.com/0/64829/936e0d41-4504-85ab-caec-8d7952045123.png)





>Since we’ve added all the core structure, model factory, migration, and model, in the last section, we can reap the benefits by reusing all those for this section.

**これまでのセクションで、すべてのコア構造、モデルファクトリ、マイグレーション、モデルを追加しました。このセクションではこれら再利用することで追加したメリットを享受できます。**

>First, create a new route in the routes/web.php file:

**まず、`routes / web.php`ファイルに新しいルーティングを作成します。**
 
 ```php
 Route::get('/submit', function () {
    return view('submit');
});
```
 
 >We will also need this view file so we can go ahead and create it at resources/views/submit.blade.php and add the following boilerplate bootstrap code:
 


**投稿する為のビューファイルも必要ですので、`resources / views / submit.blade.php`に作成し、いつものBootstrapコードを追加してください**
 
 ```php
 
@extends('layouts.app')
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
 
 
 ![form.png](https://qiita-image-store.s3.amazonaws.com/0/64829/46dba030-3aac-0056-e07d-4a26a019bcb6.png)

 

>Now, let’s create a route to handle the POST data and do our validation. Let’s create that route and add our validation rules in

**次に、POSTデータを処理し、検証を行うルートを作成しましょう。 そのルートを作成してバリデーションルールを追加しましょう：**
 
 ```php
 use Illuminate\Http\Request;
 
 Route::post('/submit', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'title' => 'required|max:255',
        'url' => 'required|max:255',
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
 
 >This route is a little more complex than the others. First, we are injecting the Illuminate\Http\Request which will hold all of the POST data. Then, we create a new Validator instance with our rules. If this validation fails, it returns the user back with the original input data and with the validator errors.

**このルートは、他のルートより少し複雑です。 まず、すべてのPOSTデータを保持する`Illuminate \ Http \ Request`を注入します。 次に、新しいValidatorインスタンスを作成します。 この検証に失敗すると、元の入力データとバリデーターエラーをユーザーに返します。**

>Finally, if everything passed validation, we use the “App::Link” model to add the data.

**最後に、すべてが検証に合格した場合、"App :: Link"モデルを使用してデータを追加します。**

## **まとめ**

>Congratulations on making it through the tutorial. This guide was designed to get you started on building your app, and you can use this as a building block to you gain the skills you need to build your application. I know this covers a lot of features and can be overwhelming if you are not familiar with the framework.

**チュートリアルを完了したことを祝福します。 このガイドは、アプリケーションの構築を開始するためのもので、これを最初の土台として使用することで、アプリケーションを構築するために必要なスキルを身に付けることができます。 私はこのチュートリアルに多くの機能をカバーしており、フレームワークに精通していないと圧倒されてしまう事を知っています。**

>I hope this introduction to Laravel shows you why so many people are excited about the framework.

**私はこのLaravelでのチュートリアル記事が、なぜ多くの人々がLaraveに興奮しているのかを示してくれることを願っています。**
 




