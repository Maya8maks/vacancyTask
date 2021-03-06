<?php
include_once "simple_html_dom.php";
include_once "db.php";
include_once ("funct.php");

$tableName='vacancy';

$query=[];
$query['id'] = 'id';
$query['query'] = 'vacancy_url';
$query['status'] = 'vacancy_status';
$vacancyDescriptLink = getSelect($db, $tableName, $query);
if(!empty($vacancyDescriptLink)) {
    $site = $vacancyDescriptLink[0]['vacancy_url'];
    if(isset($site)) {

        $html = file_get_html($site);

        $vacDiscript = [];
        foreach (($html->find('div.wordwrap p, div.wordwrap li')) as $elem) {
            if ($elem->find('*[class]')) {
                continue;
            } else {
                if (empty($elem->children())) {
                    $vacDiscript[] = strip_tags($elem->outertext . PHP_EOL);
                } else {
                    foreach ($elem->children() as $child) {
                        if ($child->find('*[class]')) {
                            continue;
                        } else {
                            $vacDiscript[] = strip_tags($child->outertext . PHP_EOL);
                        }
                    }
                }
            }
        }

        $strDiscription = implode('', $vacDiscript);

        insertVacancyDescript($db, $vacancyDescriptLink[0]['id'], $strDiscription);

        $vacancyApdate = saveStatus($db, $tableName, $vacancyDescriptLink[0]['id'], $query['status']);
    }
}