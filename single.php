<?php 
//header.phpの読み込み
include './php/module/header.php';

//functions.phpの読み込み
include './php/module/functions.php';

//titleの値が取得できれば代入
$comment = isset($_POST["comment"]) ? $_POST["comment"] : NULL;

//comment.txtのパスを変数に代入
$comment_file = './text/comment.txt';

//記事のコメントのエラー検知
if($comment === "") {
    $error_comment_msg = "コメントは必須項目です。";
    echo $error_comment_msg;
    $error_comment = false;
} else {
    $error_comment = true;
};

//コメントエラーが起きていなければ、comment.txtに書き込む要素を配列にする
if($error_comment && !($comment === null)) {
    $data = array(
        'comment' => $comment,
        'article_id' => $_GET['article_id'],
        'comment_id' => create_ID($comment_file, 6, 'comment_id'),
    );
};

//$dataをjsonに書き換え
if($data !== NULL) {
    $data_jsoncode = json_encode($data);
};

//article.txtのパスを変数に代入
$article_file ='./text/article.txt';

//$article_fileを読み込み可能な状態で開く
$fp = fopen($article_file, 'r');

//$comment_fileを読み込み、書き込み可能な状態で開く
$file_open = fopen($comment_file, 'a+');

//data_jsonをarticle.txtに書き込む
if(!($data_jsoncode === NULL)) {
    if ($file_open){
        if (flock($file_open, LOCK_EX | LOCK_NB)){
            if (fwrite($file_open, $data_jsoncode . PHP_EOL) === FALSE){
                print('ファイル書き込みに失敗しました');
            }
            flock($file_open, LOCK_UN);
        }else{
            print('ファイルロックに失敗しました');
        }
    }
};

if(!($_POST['delete'] === null)) {
    delete_mode($comment_file, 'comment_id');
};

?>
    <main>
<?php
    // article.txtから1行ずつデータを取得する
    while($json = fgets($fp)) {
        $article_data = json_decode($json, true);
        if($article_data['article_id'] == $_GET['article_id']) {
?>
<article>
    <div class="aritcle-title">
        <h3><?php echo $article_data['title'];?></h3>
    </div>
    <div class="article-body">
        <p><?php echo $article_data['body'];?></p>
    </div>
</article>
<?php
        }
    }
?>
        <hr/>
        <section class="comment-posts">

            <div class="comment-form">
                <form action="" method="POST">
                    <div class="input-body">
                        <textarea name="comment" class="comment-body" id="comment" cols="30" rows="10"></textarea>
                        <input type="submit" class="comment-btn" value="コメントを書く">    
                    </div>
                </form>
            </div>
            <ul class="comment-list">
<?php 
//$comment_fileを読み込み可能な状態で開く
$file_open = fopen($comment_file, 'r');

// ファイルから1行ずつデータを取得する
while($json = fgets($file_open)) {
    $data = json_decode($json, true);
    if(!($data['comment'] === null) && $data['article_id'] === $_GET['article_id']) {
?>
<li class="comment-item">
    <form action="" method="POST" class="comment">
    <input name="mode" type="hidden" value="DELETE">
    <input name="id" type="hidden" value="<?php echo $data['comment_id']; ?>">
        <div class="input-body">
            <div class="input-data comment-body"><p><?php echo $data['comment'];?></p></div>
            <input type="submit" name="delete" class="comment-btn" value="コメントを消す">
        </div>
    </form>
</li>
<?php
    }
}
?>
            </ul>
        </section>
    </main>
</body>
</html>