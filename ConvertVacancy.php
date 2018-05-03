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

    echo $stringDescript = $description[0]['description'].'</br>';
    if(isset($stringDescript)) {
        $stringDescriptLow = cleanString($stringDescript);

        $arrWords = explode(" ", $stringDescriptLow);

        $descriptWord = [];
        $maxWordLength = 2;
        foreach ($arrWords as $word) {
            if (mb_strlen($word) > $maxWordLength) {
                $descriptWord[] = trim($word);
            }
        }
        
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

        foreach ($descriptWordUniq as $word => $number) {
            $insertWords = insertWord($db, $description[0]['id'], $word, $number);
        }
        $vacancyUpdate = saveStatus($db, $tableName, $description[0]['id'], $query['status']);
    }
}