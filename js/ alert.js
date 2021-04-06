function post_alert() {
    //投稿確認アラートの表示
    let result = confirm('投稿してよろしいですか？');
    
    if(result) {
        alert('投稿に成功しました。');
        return result;
    } else {
        alert('投稿に失敗しました。');
        return result;
    };
}
