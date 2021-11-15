<h1>アプリケーション名</h1>
<h3>Otasche</h3>
<h1>アプリケーションのURL</h1>
<h3>https://otasche.herokuapp.com/</h3>
<h1>(1)概要</h1>
<p>ユーザーの予定の管理、達成、記録をサポートするアプリ</p>
<h1>(2)詳細</h1>
<p>ユーザーが登録した予定にタスクという形で具体的にやることを紐づけできます。これにより、やることの細分化ができ、何から手をつければいいのか分からないという状況にさせません。また、登録した予定には達成度というパラメータがあり、スライダーと数値で予定の達成度を見える化しているのでユーザのモチベーション維持を助けます。</p>
<p>他にもユーザーが登録した予定の期限から残り日数を割り出して画面に表示するので、ユーザーが気づかないうちに期限切れになるのを防ぎます。また、予定の「残り日数÷優先度」という指標を用いてユーザーへ推奨予定を表示します。さらに、各予定に対して推奨タスクも表示します。</p>
<p>その他にも、月ごとに達成したタスク一覧を表示する機能もあり、振り返りを行うこともできます。</p>
<p>さらに、アカウントの新規登録・ログインには通常の方法に加えてLine認証を行うこともできるので、このアプリケーションアカウント用のメールアドレス、パスワードを用意しなくても利用できます。</p>
<p>とにかく使いやすいアプリを作ることに注力したので、セキュリティ面は最低限しか実装していません。</p>
<p>今後はユーザーがアプリにログインしていなくても期限が迫っている予定に気付けるようにLineMessagingAPIを導入して、期限が迫っている予定のお知らせ機能を実装できればと考えています。</p>
<h1>(3)開発の経緯</h1>
<p>学生生活の中で、就活、実験、レポート、課題、サークル、趣味、アプリ開発など、やりたいこと、やらなければいけないことがタイミング悪く重なってしまい、どこから手を付ければいいのか分からないという状況に陥いった経験があり、作業の効率化のためにやることを細分化して管理でき、予定の進捗が一目でわかるアプリが欲しいという自分の願望と勉強を兼ねてつくりました。</p>
<h1>(4)利用方法</h1>
<h2>予定・タスクの新規作成</h2>
<p>[1]予定一覧の予定の新規作成ボタンを押すと予定新規作成ダイアログが開くので、「予定名、予定の優先度、予定の期限、予定の詳細」の4項目を記入して作成ボタンを押す</p>

![2021-11-15 (41)](https://user-images.githubusercontent.com/85385454/141774967-ef4732ec-c567-49e0-a84e-5ca959f23212.png)

<p>[2]予定一覧から作成した予定を選択して詳細ダイアログを開き、タスク一覧ボタンを押してタスク一覧画面を開く</p>

![2021-11-15 (43)](https://user-images.githubusercontent.com/85385454/141775140-95e6cea3-50bf-4a74-96a3-b7c0a9ccd681.png)
![2021-11-15 (46)](https://user-images.githubusercontent.com/85385454/141775351-a04ca9ad-9d6b-46f4-8c51-2221fb388411.png)
![2021-11-15 (47)](https://user-images.githubusercontent.com/85385454/141775358-a6f773eb-5c1a-480f-a8ea-058422ffdf75.png)

<p>[3]タスク一覧のタスクの新規作成ボタンを押すとタスク新規作成ダイアログが開くので、「タスク名、タスクの優先度、タスクの詳細」の3項目を記入して作成ボタンを押す</p>

![2021-11-15 (50)](https://user-images.githubusercontent.com/85385454/141775703-be86a09d-55ff-4222-822a-66194fd2558e.png)
![2021-11-15 (51)](https://user-images.githubusercontent.com/85385454/141775713-7e022a3f-d3cd-4442-86dc-aa576a811391.png)

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

![2021-11-15 (55)](https://user-images.githubusercontent.com/85385454/141776139-bb7fd1c2-5608-45bf-8616-72168d6e5d58.png)
![2021-11-15 (27)](https://user-images.githubusercontent.com/85385454/141771798-207ea075-feac-4321-8e0c-15490d641bf1.png)
![2021-11-15 (29)](https://user-images.githubusercontent.com/85385454/141771884-e46f299d-1b20-4dd2-b879-fe528656aded.png)
![2021-11-15 (31)](https://user-images.githubusercontent.com/85385454/141771978-8986dc25-a7f0-415c-8fc1-85bba1fd53cb.png)


<h2>ホーム画面の構成</h2>
<p>ホーム画面上部では、「予定の残り日数　÷　優先度」の値をもとに推奨予定を表示する</p>
<p>また、タスクの達成数に応じて、進捗率をスライダーと％表記で表示する</p>
<p>さらに、推奨予定に含まれるタスクの中で最も優先度の高いタスクを推奨タスクとして表示する</p>
<p>予定の調整ボタンを押すと推奨予定のタスク一覧へと遷移する</p>
<p>ホーム画面下部では、期限が迫っている予定一覧を表示する</p>
<p>表示切替にカーソルを合わせると表示する予定の残り日数を1,5,10,30から選択できる</p>
<p>さらに、予期限が迫っている予定一覧の予定を選択すると、選択した予定のタスク一覧へと遷移する</p>

![2021-11-15 (15)](https://user-images.githubusercontent.com/85385454/141729704-fa17f262-c1c6-4680-a77c-dbd06e80a185.png)


<h2>予定一覧画面の構成</h2>
<p>この画面では未達成予定、達成済み予定、全予定の3種類の予定一覧を表示する</p>
<p>表示切替にカーソルを合わせると未達成予定、達成済み予定、全予定の3種類が選択でき、選択した種類の予定一覧を画面に表示する</p>

![2021-11-15 (59)](https://user-images.githubusercontent.com/85385454/141776367-e75042e2-f74b-48b2-a003-99e4dbe5fc06.png)

<p>また、ソートボタンを押すとダイアログが開くので、ソートさせたい条件を選択し、実行ボタンを押すと表示している予定一覧がソートされる</p>

![2021-11-15 (60)](https://user-images.githubusercontent.com/85385454/141776377-a9740c96-69c6-41c1-8fee-ecd4dff2d4b5.png)

<p>予定の新規作成ボタンを押すと、予定新規作成ダイアログが開く</p>
<p>予定の新規作成方法については「予定・タスクの新規作成」に記載</p>
<p>予定一覧に表示されている予定を選択すると、タスク一覧画面へ遷移する</p>

<h2>タスク一覧画面の構成</h2>
<p>この画面ではホーム画面と同様に、「予定の残り日数　÷　優先度」の値をもとに推奨タスクを表示する</p>
<p>また、タスクの達成数に応じて、進捗率をスライダーと％表記で表示する</p>

![2021-11-15 (62)](https://user-images.githubusercontent.com/85385454/141777289-c96130b8-fb15-4172-a63e-cb825e48fca8.png)

<p>画面下部ではタスク一覧を表示しており、表示切替にカーソルを合わせると未達成タスク、達成済みタスク、全タスクの3種類が選択でき、選択した種類のタスク一覧を画面に表示する</p>

![2021-11-15 (66)](https://user-images.githubusercontent.com/85385454/141777533-4036b32d-c325-4ad6-8163-f9b7fe14ac01.png)

<p>タスクの新規作成ボタンを押すと、タスク新規作成ダイアログが開く</p>
<p>タスクの新規作成方法については「予定・タスクの新規作成」に記載</p>
<p>予定一覧へボタンを押すと、予定一覧画面へ遷移する</p>

<h2>記録画面の構成</h2>
<p>記録画面では、画面上部に当日達成した予定一覧を表示する</p>
<p>また画面下部には、当日を含む過去に達成した予定一覧を指定した年月ごとに表示する</p>
<p>カレンダーアイコンのある部分を押すと年月を選択するダイアログが開くので、表示させたい年月を選択する</p>

![2021-11-15 (69)](https://user-images.githubusercontent.com/85385454/141778511-8b0fdedc-e618-4ef8-9c7e-5fd1a25d8b24.png)
![2021-11-15 (70)](https://user-images.githubusercontent.com/85385454/141778521-768f890d-a4ec-46cf-bc81-a8adb4dd1dcb.png)


<h2>アカウント画面の構成</h2>
<p>この画面ではログインしているユーザーの情報を表示する</p>
<p>アカウント情報の変更ボタンを押すと、アカウント情報入力用ダイアログが表示されるので、変更したい内容に書き換えて変更ボタンを押すとアカウント情報が更新される</p>

![2021-11-15 (73)](https://user-images.githubusercontent.com/85385454/141778983-2603322c-a167-49d3-a890-01acbffd00dc.png)
![2021-11-15 (74)](https://user-images.githubusercontent.com/85385454/141778988-c42a219a-3008-48a7-b169-b3a8985aa0ec.png)

<h1>(5)使用した技術</h1>
<h2>利用言語</h2>
<p>フロント・クライアント：HTML,CSS,JavaScript</p>
<p>サーバー：PHP</p>
<h2>利用フレームワーク</h2>
<p>Laravel,Vue.js</p>
<h2>DB</h2>
<p>MySQL</p>
<h2>インフラ・その他</h2>
<p>heroku,Lineログイン</p>
<h2>開発環境</h2>
AWS Cloud9
<h1>(6)使用した技術の選定理由</h1>
<p>私は今までWebアプリケーションを作った経験がなく、Webアプリケーションを開発するのに必要な言語、フレームワークの知識が全くなかったので、日本語の公式ドキュメントがあり、Qitaや個人のWebサイトなどの技術記事に情報が多く、学習コストが低いことからフレームワークとしてLaravel,Vue.jsを選びました。また、アプリケーション用のアカウントを管理するのが面倒だと感じたのでsns認証を導入しようと考え、日本語の公式ドキュメントが用意されており学習コストが低いことから、LineログインAPIを選びました。</p>
