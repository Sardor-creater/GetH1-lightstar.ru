<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
</head>
<body>
<div class="d-flex flex-row-reverse">
    <div class="alert fade alert-success position-fixed" role="alert">
        Copied!
    </div>
</div>

<?php
include_once 'simple_html_dom.php';

function curlGetPage($url, $referer="https://yandex.ru"){
    $ch = curl_init('https://yandex.ru/pogoda/');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36 [HTTP_ACCEPT]');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

$sroch = lighstarsvet();
function lighstarsvet(){

     $arrs = array(
         0 => array('https://lightstar.ru/kupit-lustry-svetilniki-optom/trekovyye-sistemy-pro/trekovye-pro-svetilniki/prorp437130'),
         1 => array('https://lightstar.ru/kupit-lustry-svetilniki-optom/trekovyye-sistemy-pro/trekovye-pro-svetilniki/prorp648786')
     );
    $data = [];

    for ($i = 0; $i <= count($arrs)-1; $i++){
        $url = $arrs[$i][0];

        $page = curlGetPage($url);
        $html = str_get_html($page);

        $artikul_test = $html->find('h1.product__title span', 1);
        if (!$artikul_test){
            continue;
        }

        $artikul_name = $html->find('h1.product__title span', 1)->plaintext;
        $artikul = explode("&nbsp;", $artikul_name);
        $h1 = $html->find('h1.product__title', 0)->plaintext;

        $data[] = ["h1"=>$h1, 'artikul'=>$artikul[1],];
    }
    return $data;
}

?>

<div class="container my-4">
    <h1 class="text-center">Get H1 lightstar.ru</h1>
</div>


<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Artikul</th>
        <th scope="col">Description</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $k = 0;
    foreach ($sroch as $val){
    ?>

    <tr>
        <th scope="row"><?php echo $k;?></th>
        <td><button type="button" class="btn btn-success btn-sm" onclick="copyContent(this.innerText)"><?php echo $val['artikul']; ?></button></td>
        <td><button type="button" class="btn btn-secondary btn-sm" onclick="copyContent(this.getAttribute('artext'))" artext="<?php echo "{$val['h1']}- купить по оптовой цене у официального производителя. Доставка по всей России. Скидка при заказе в личном кабинете."; ?>">Description</button></td>
    </tr>

    <?php $k++; } ?>

    </tbody>
</table>



<div class="container">

</div>

<script>

    var alert = document.querySelector(".alert");
    const copyContent = async (text) => {
        try {
            console.log(text)
            await navigator.clipboard.writeText(text);
            alert.classList.remove("fade");
            setTimeout(() => {
                alert.classList.add("fade");
            }, "1000")
        } catch (err) {
            console.error('Failed to copy: ', err);
        }
    }
</script>

</body>
</html>



