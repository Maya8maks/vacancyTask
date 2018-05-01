<?php
include_once("db.php");
include_once("simple_html_dom.php");
include_once ("funct.php");

$tableName='vacancy_description';
$query=[
'id'=>'id',
'query'=>'description',
'status'=>'status'
];

$description = getSelect($db, $tableName, $query);
if(!empty($description)) {

    $stringDescript = $description[0]['description'];

    /*замінюю спецсимволи на пробел*/
    $stringDescript = preg_replace("~(\\\|\*|\.|\;|\:|\—|\"|\&|\/|\#|\@|\(|\)|\?|'\?|\!|\,|\[|\?|\]|\(|\\\$|\))~", " ", $stringDescript);
    /*видаляю лишні пробели*/
    $stringDescript = preg_replace("/ +/", " ", $stringDescript);

    $stringDescript = rtrim($stringDescript);

    $stringDescript = str_replace("\r\n", ' ', $stringDescript);

    $stringDescriptLow = mb_strtolower($stringDescript);

    $arrWords = explode(" ", $stringDescriptLow);
    $descriptWord = [];
    $maxWordLength = 3;
    foreach ($arrWords as $word) {

        if (mb_strlen($word) > $maxWordLength) {
            $descriptWord[] = trim($word);
        }
    }
   var_dump( $descriptWord);
    exit();
    $result = array_unique($descriptWord);

    $descriptWordUniq = [];
    $n = 0;
    foreach ($result as $key => $item) {
        $descriptWordUniq[$item] = $n;
    }

    foreach ($descriptWordUniq as $word => $number) {
        foreach ($descriptWord as $elem) {
            if ($word == $elem) {
                $descriptWordUniq[$word] = ++$number;

            }
        }
    }

    foreach ($descriptWordUniq as $word=> $number) {
    $insertWords = $db->prepare("INSERT INTO vacancy_words(`vacancy_description_id`, `word`,`qty`, `skill_id`, `type`) VALUES (?, ?, ?, ?, ?)");
    $insertWords->execute(array($description[0]['id'], $word, $number, 0, 0));
    }
    $vacancyApdate = saveStatus($db, $tableName, $description[0]['id'], $query['status']);
}