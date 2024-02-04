<?php

include('simple_html_dom.php');
$products = [];

// for ($i = 1; $i < 5; $i++) {
$url = 'https://yourpetpa.com.au/collections/horse-vitamins-supplements';
$baseUrl = 'https://yourpetpa.com.au/';
$response = file_get_contents($url);
$html = file_get_html($url);
$dom = new DOMDocument();
@$dom->loadHTML($response);
$xpath = new DOMXPath($dom);
$divElements = $html->find('div.global-border-radius');

$index = 0;

foreach ($divElements as $divElement) {
    $index++;
    $title = $html->find('div.product-block__title')->plaintext;
    $description = $html->find('div.product__description_full--width')->plaintext;
    $categoryElement = $html->find('div.collection-header__info h2', 0)->plaintext;
    $price = $html->find('span.theme_money.product-price__reduced')->plaintext;
    $category = trim($categoryElement);
    $nestedHtml = str_get_html($divElement->innertext);
    $imgElement = $nestedHtml->find('noscript img', 0);
    if ($imgElement !== null) {
        $imgSrc = $imgElement->src;
        $aElement = $nestedHtml->find('a', 0);
    }
    if ($aElement !== null) {
        $originalHref = $aElement->href;
        if (strpos($originalHref, $baseUrl) === 0) {
            $hrefValue = $originalHref;
        } else {
            $hrefValue = $baseUrl . ltrim($originalHref, '/');
        }
    }
    $products[] = [$index,$title, $description, $category,$price, $hrefValue, $imgSrc];
    // echo "Image Src: $imgSrc" . PHP_EOL;
    // echo "Href: $hrefValue" . PHP_EOL;
    // echo "Index: $index" . PHP_EOL;
}
// }
$fp = fopen('products.csv', 'w');
fputcsv($fp, ['Index','Title','Description', 'Category','Price','Product URL', 'Image URL']);
foreach ($products as $products) {
    fputcsv($fp, $products);
}
fclose($fp);



// Don't forget to clean up the nestedHtml object to avoid memory leaks

//   $ol = $xpath->query('//div')->item(0);
//   $articles = $ol->getElementsByTagName('div');
//   $label = $articles->getElementsByTagName('span')->innertext;
//   $ll = $label->getElementsByTagName('span')->plaintex;

//   echo $label;
//   foreach ($articles as $article) {
//     $image = $article->getElementsByTagName('img')->item(0);
//     $title = $image->getAttribute('alt');
//     $starTag = $article->getElementsByTagName('p')->item(0);
//     $starClasses = explode(' ', $starTag->getAttribute('class'));
//     $star = $starClasses[1];
//     $price = $article->getElementsByTagName('p')->item(1)->nodeValue;
//     $price = (float)substr($price, 1);
//     $books[] = [$title, $star, $price];
//   }


// $fp = fopen('books.csv', 'w');
// fputcsv($fp, ['Title', 'Star Rating', 'Price']);
// foreach ($books as $book) {
//     fputcsv($fp, $book);
// }
// fclose($fp);
