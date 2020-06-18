<?php

/**
 * Http Method 主要是 Http 定義出來的方法，沒有強制性。但是在使用方式上會有些不同。
 * GET：參數傳遞透過網址列，依據各家瀏覽器不同會有長度限制。
 *      規範上為「取得」Server 端資料且同一個 URL 應該回傳一樣的結果。
 * POST：透過 Request Body 攜帶參數，也可以使用 query string 帶參數但是一般來說不會這樣做，
 *       能夠攜帶的資料大小由 Server 決定。
 *       Server 端可以針對 Body 設定允許的資料類型 Ex: Content-Type: application/json
 *       以 PHP 來說預設只會接受 x-www-form-urlencoded 格式的資料。
 *       一般使用為「新增、修改、刪除」Server 端資料。
 * 
 * 延伸概念：Restful API
 * 
 * PHP 透過 $_SERVER 管理 Request 相關的資料
 * https://www.php.net/manual/en/reserved.variables.server.php
 * 
 * $_SERVER['REQUEST_METHOD] 可以取得目前 Reuqest 使用甚麼方法發送
 * 
 */
$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . strtok($_SERVER['REQUEST_URI'], '?');
$method = $_SERVER['REQUEST_METHOD'];
// 進入網頁都是以 GET 取得頁面
echo "<h1>$method</h1>";
echo '<pre>';

// 印出所有攜帶的資料
switch ($method) {
    case 'GET':
        print_r($_GET);
        break;
    case 'POST':
        // POST 狀況不強制表單攜帶 query string 的話，可以發現參數不會顯示在網址列
        echo '<pre>';
        print_r($_POST);
        break;
    default:
        break;
}
echo '</pre>';

if ($method === 'POST') {
    $url = strtok($_SERVER['HTTP_REFERER'], '?');
    // 保留參數跳轉回前一頁
    $queryStr = http_build_query(array_filter($_REQUEST));
    // 通過 location 轉移畫面都會以 GET 發送
    // header("Location:$url?$queryStr");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Request & Response</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css' />
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <a class="col-1 text-center" href="<?= $url ?>">回首頁</a>
            <a class="col-1 text-center" href="<?= $url . '?page=Hello,World' ?>">Hello,world</a>
        </div>
        <div class="row mt-5">
            <div class="card border">
                <?php if ($method === 'GET' && !empty($_GET)) : ?>
                    <div class="card-title text-center">這是新的文章：<?= $_GET['page'] ?? '' ?></div>
                    <?php if (isset($_GET['value'])) : ?>
                        <div class="card-title">
                            <span class="p-4 float-right">
                                這是新的文章，而且呼叫過後端了 Value=<?= $_GET['value'] ?>
                            </span>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="card-title text-center">這是一篇文章</div>
                <?php endif; ?>
                <div class="card-body text-center">
                    Labore exercitation fugiat culpa reprehenderit pariatur. Velit quis sint tempor in reprehenderit quis velit mollit magna fugiat amet sint qui. Ex nostrud sunt consectetur dolor ullamco pariatur. Anim voluptate aliqua sint incididunt nisi anim eiusmod minim proident labore irure culpa ullamco magna. Voluptate dolore labore veniam veniam culpa est elit. Laboris reprehenderit magna magna ipsum culpa voluptate excepteur et sit sint. Irure deserunt veniam qui aliquip voluptate ea sunt non in eiusmod excepteur aliquip sint incididunt.
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <form action="<?= $url ?>" method="POST">
                <input type="text" name="value" placeholder="隨便寫點甚麼">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
</body>

</html>