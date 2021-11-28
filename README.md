<h1>アプリケーション名</h1>
<h3>Otasche</h3>
<h1>アプリケーションのURL</h1>
<h3>https://otasche.herokuapp.com/</h3>
<h1>概要</h1>
<p>ユーザーの予定の管理、達成、記録をサポートするアプリ</p>
<h1>詳細</h1>
<p>ユーザーが登録した予定にタスクという形で具体的にやることを紐づけできます。これにより、やることの細分化ができ、何から手をつければいいのか分からないという状況にさせません。また、登録した予定には達成度というパラメータがあり、スライダーと数値で予定の達成度を見える化しているのでユーザのモチベーション維持を助けます。</p>
<p>他にもユーザーが登録した予定の期限から残り日数を割り出して画面に表示するので、ユーザーが気づかないうちに期限切れになるのを防ぎます。また、予定の「残り日数÷優先度」という指標を用いてユーザーへ推奨予定を表示します。さらに、各予定に対して推奨タスクも表示します。</p>
<p>とにかく使いやすいアプリを作ることに注力したので、セキュリティ面は最低限しか実装していません。</p>
<p>今後はユーザーがアプリにログインしていなくても期限が迫っている予定に気付けるようにLINEMessagingAPIを導入して、期限が迫っている予定のお知らせ機能を実装できればと考えています。</p>
<h1>開発の経緯</h1>
<p>学生生活の中で、就活、実験、レポート、課題、サークル、趣味、アプリ開発など、やりたいこと、やらなければいけないことがタイミング悪く重なってしまい、どこから手を付ければいいのか分からないという状況に陥いった経験があり、作業の効率化のためにやることを細分化して管理でき、予定の進捗が一目でわかるアプリが欲しいという自分の願望と勉強を兼ねてつくりました。</p>
<h1>工夫点</h1>
<h2>技術視点</h2>
<p>・システム内部からしか呼ばれないAPIに関しては、各vueファイルから直接やりとりするのではなくVuexのストアを通して処理を行うことでエラーが発生した際に原因を分析しやすくしている点</p>
<p>・関数や変数にコメント文をつけてどのような動作を行うのかを分かりやすくしている点</p>
<p>・機密情報はベタ打ちせずに.envファイルに記載し、そこから読み取って利用することで機密情報が漏洩しないようにしている点</p>
<h2>ユーザー視点</h2>
<p>・大枠の予定を登録できるだけでなく、その予定を達成するために必要なタスクを紐づけて登録できることで、予定を達成するために必要な行動が明確になり、ユーザーの予定達成をサポートしている点</p>
<p>・スライダーと％で予定の達成度を一目で分かる形にすることで、ユーザーのモチベーションの維持をサポートしている点</p>
<p>・LINEアカウントで登録・ログインできるので、このアプリケーション用のパスワードの管理が必要ない点</p>
<h1>利用方法</h1>
<h2>予定・タスクの新規作成</h2>
<p>[1]予定一覧の予定の新規作成ボタンを押すと予定新規作成ダイアログが開くので、「予定名、予定の優先度、予定の期限、予定の詳細」の4項目を記入して作成ボタンを押す</p>

![2021-11-15 (41)](https://user-images.githubusercontent.com/85385454/141774967-ef4732ec-c567-49e0-a84e-5ca959f23212.png)

<p>[2]予定一覧から作成した予定を選択して詳細ダイアログを開き、タスク一覧ボタンを押してタスク一覧画面を開く</p>

![2021-11-15 (46)](https://user-images.githubusercontent.com/85385454/141775351-a04ca9ad-9d6b-46f4-8c51-2221fb388411.png)

<p>[3]タスク一覧のタスクの新規作成ボタンを押すとタスク新規作成ダイアログが開くので、「タスク名、タスクの優先度、タスクの詳細」の3項目を記入して作成ボタンを押す</p>

![2021-11-15 (50)](https://user-images.githubusercontent.com/85385454/141775703-be86a09d-55ff-4222-822a-66194fd2558e.png)

<p>これらの操作で予定、タスクの新規作成が行える</p>


<h2>予定の完了・削除</h2>
<p>[1]予定一覧から完了した予定を選択</p>
<p>[2]予定の完了ボタンを押すと予定の達成処理を行う</p>
<p>また、予定の取り消しボタンを押すと確認ダイアログが開くので、ダイアログ内の続行ボタンを押すと予定が削除される</p>

![2021-11-15 (55)](https://user-images.githubusercontent.com/85385454/141775939-7b9e0dbf-18f1-4f32-a0c9-fee766bab8dd.png)
![2021-11-15 (56)](https://user-images.githubusercontent.com/85385454/141775947-b83eadeb-f0bb-41d0-b971-52f20d4b08a7.png)


<h2>タスクの完了・削除</h2>
<p>[1]予定一覧から完了したタスクを含む予定を選択</p>
<p>[2]タスク一覧ボタンを押してタスク一覧画面を開く</p>
<p>[3]画面下部のタスク一覧から完了したタスクを選択する</p>
<p>[4]タスクの完了ボタンを押すとタスクの達成処理を行う</p>
<p>また、タスクの取り消しボタンを押すと確認ダイアログが開くので、ダイアログ内の続行ボタンを押すとタスクが削除される</p>

![2021-11-15 (29)](https://user-images.githubusercontent.com/85385454/141771884-e46f299d-1b20-4dd2-b879-fe528656aded.png)
![2021-11-15 (31)](https://user-images.githubusercontent.com/85385454/141771978-8986dc25-a7f0-415c-8fc1-85bba1fd53cb.png)


<h2>ホーム画面の構成</h2>
<p>ホーム画面では重要な情報が一目で分かるように</p>
<ul>
    <li>推奨予定</li>
    <li>推奨予定の残り日数または残り時間</li>
    <li>推奨予定に結び付いているタスクの中で最も優先度の高い推奨タスク</li>
    <li>推奨予定の進捗率</li>
    <li>期限が迫っている予定一覧</li>
</ul>
<p>を表示する</p>

![2021-11-15 (15)](https://user-images.githubusercontent.com/85385454/141729704-fa17f262-c1c6-4680-a77c-dbd06e80a185.png)


<h2>予定一覧画面の構成</h2>
<p>この画面では未達成予定、達成済み予定、全予定の3種類の予定一覧を表示する</p>
<p>また、並び替えボタンで表示予定のソートを行える
    
![2021-11-15 (75)](https://user-images.githubusercontent.com/85385454/142705840-b6fbdf8d-c3b0-4ef9-8681-d251fbe54f36.png)
![2021-11-15 (60)](https://user-images.githubusercontent.com/85385454/141776377-a9740c96-69c6-41c1-8fee-ecd4dff2d4b5.png)

<h2>タスク一覧画面の構成</h2>
<p>この画面では</p>
<ul>
    <li>選択予定の残り日数または残り時間</li>
    <li>選択予定に結び付いているタスクの中で最も優先度の高い推奨タスク</li>
    <li>選択予定の進捗率</li>
    <li>選択予定の未達成、達成済み、全タスク一覧</li>
</ul>
<p>を表示する</p>

![2021-11-15 (66)](https://user-images.githubusercontent.com/85385454/141777533-4036b32d-c325-4ad6-8163-f9b7fe14ac01.png)

<h2>記録画面の構成</h2>
<p>この画面では、当日達成した予定一覧、当日を含む過去に達成した予定一覧を表示する</p>

![2021-11-15 (70)](https://user-images.githubusercontent.com/85385454/141778521-768f890d-a4ec-46cf-bc81-a8adb4dd1dcb.png)

<h2>アカウント画面の構成</h2>
<p>この画面ではログインしているユーザーの情報の確認、編集を行える</p>

![2021-11-15 (74)](https://user-images.githubusercontent.com/85385454/141778988-c42a219a-3008-48a7-b169-b3a8985aa0ec.png)

<h1>使用した技術</h1>
<h2>利用言語</h2>
<p>フロント・クライアント：HTML5,JavaScript</p>
<p>サーバー：PHP--version7.4.21</p>
<h2>利用フレームワーク</h2>
<p>Laravel--version6.20.38,Vue.js--version2.6.14</p>
<h2>DB</h2>
<p>MySQL--version5.6.50</p>
<h2>インフラ・その他</h2>
<p>heroku,LINEログインAPI</p>
<h2>開発環境</h2>
AWS Cloud9
<h1>使用した技術の選定理由</h1>
<p>私は今までWebアプリケーションを作った経験がなく、Webアプリケーションを開発するのに必要な言語、フレームワークの知識が全くなかったので、日本語の公式ドキュメントがあり、Qitaや個人のWebサイトなどの技術記事に情報が多く、学習コストが低いことからフレームワークとしてLaravel,Vue.jsを選びました。また、アプリケーション用のアカウントを管理するのが面倒だと感じたのでsns認証を導入しようと考え、日本語の公式ドキュメントが用意されており学習コストが低いことから、LineログインAPIを選びました。</p>
