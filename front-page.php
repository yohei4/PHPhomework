<?php 
//header.phpの読み込み
include './php/module/header.php';

//functions.phpの読み込み
include './php/module/functions.php';

//titleの値が取得できれば代入
$title = isset($_POST["title"]) ? $_POST["title"] : NULL;

//bodyの値が取得できれば代入
$body = isset($_POST["body"]) ? $_POST["body"] : NULL;

//タイトルと記事の文字数取得
$title_length = strlen($title);
$body_length = strlen($body);

//タイトルのエラー検知
if($title === "" || $title_length <= 30) {
    $error_title_msg = "タイトルは必須かつ３０文字以上です。";
    $error_title = false;
} else {
    $error_title = true;
};

//記事のエラー検知
if($body === "") {
    $error_body_msg = "記事は必須項目です。";
    $error_body = false;
} else {
    $error_body = true;
};

// //article.txtのパスを変数に代入
$article_file ='./text/article.txt';

//aritcleの配列を生成
if($error_title === true && $error_body === true) {
    $data = array(
        'title' => $title,
        'body' => $body,
        'article_id' => create_ID($article_file, 8, 'article_id'),
    );
};

//jsonに書き換え
if(!($data === NULL)) {
    $data_jsoncode = json_encode($data);
};

//data_jsonをarticle.txtに書き込む
if(!($data_jsoncode === NULL)) {
    if ($fp = fopen($article_file, 'a+')){
        if (flock($fp, LOCK_EX | LOCK_NB)){
            if (fwrite($fp, $data_jsoncode . PHP_EOL) === FALSE){
                print('ファイル書き込みに失敗しました');
            }
            flock($fp, LOCK_UN);
        }else{
            print('ファイルロックに失敗しました');
        }
        fclose($fp);
    }
}

if(!($_POST['delete'] === null)) {
    delete_mode($article_file, 'article_id');
};

?>
    <main>
        <section class="form-post">
            <div class="form-title">
                <h1>
                    さぁ、最新のニュースをシェアしましょう
                </h1>
            </div>
                <div class="form-group">
                    <form action="" method="POST" onsubmit="return post_alert()">
                        <div class="input-title">
                            <label for="title">タイトル：</label>
                            <input name="title" id="title" type="text">
                        </div>
                        <div class="error_msg"><p class="msg"><?= $error_title_msg;?></p></div>
                        <div class="input-body">
                            <label for="body">記事：</label>
                            <textarea name="body" id="body" cols="30" rows="10"></textarea>
                        </div>
                        <div class="error_msg"><p class="msg"><?= $error_body_msg;?></p></div>
                        <div class="input-submit">
                            <button type="submit" value="投稿">投稿</button>
                        </div>
                    </form>
                </div>
        </section>
        <section class="posts">
            <ul class="article-list">
            
<?php 
//$article_fileを読み込み可能な状態で開く
if($fp = fopen($article_file, 'r')){
    // ファイルから1行ずつデータを取得する
    while($json = fgets($fp)) {
        $data = json_decode($json, true);
        if(!($data['title'] === "" && $data['body'] === "")) {
            ?><li>
            <article>
                <div class="aritcle-title">
                    <h3><?php echo $data['title'];?></h3>
                </div>
                <div class="article-body">
                    <p><?php echo $data['body']?></p>
                </div>
                <div class="airticle-link">
                    <p><a href="./single.php?article_id=<?php echo $data['article_id']; ?>">記事全文•コメントを見る</a></p>
                </div>
                <form action="" method="POST" class="comment">
                    <input name="mode" type="hidden" value="DELETE">
                    <input name="id" type="hidden" value="<?php echo $data['article_id']; ?>">
                    <div class="delete-btn">
                        <input type="submit" name="delete" class="comment-btn" value="記事を消す">
                    </div>
                </form>
                </form>
            </article>
        </li><?php
        }
    }
    fclose($fp);
}
?>
            </ul>
            <nav>
                <ul class="page-list">
                    <li class="page-item">
                        <span><a href="./article.php">&lsaquo;</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">1</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">2</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">3</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">4</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">5</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">6</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">7</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">8</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">9</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">10</a></span>
                    </li>
                    <li class="page-item">
                        <span><a href="./article.php">&rsaquo;</a></span>
                    </li>
                </ul>
            </nav>
        </section>
    </main>
    <script src="./js/ alert.js"></script>
</body>
</html>