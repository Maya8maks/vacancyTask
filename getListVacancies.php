<?php
include_once("db.php");
include_once("simple_html_dom.php");
include_once ("funct.php");

$tableName='explorer';
$query=[];
$query['id']='id';
$query['query']='explorer.query';
$query['status']='status';

$vacancyLink = getSelect($db, $tableName, $query);
if(!empty($vacancyLink)) {
    $siteLinks = $vacancyLink[0]['query'];
    if(isset($siteLinks)) {
        $htmlLinks = file_get_html($siteLinks);

        foreach ($htmlLinks->find('nav a[title]') as $element) {
        }
        $lastPage = $element->plaintext;

        $listPages = [];
        for ($i = 1; $i <= $lastPage; $i++) {
            $listPages[] = $siteLinks . '/?page=' . $i;
        }

        $listVacancies = [];
        foreach ($listPages as $htmlPage) {
            foreach (file_get_html($htmlPage)->find('h2.add-bottom-sm a') as $element) {
                $listVacancies[] = 'https://www.work.ua' . str_replace('/ua', '', $element->href);
            }
        }
        foreach ($listVacancies as $vacancy) {
            $insertVacancy = insertVacancy($db, $vacancyLink[0]['id'], $vacancy);
        }
        $explorerUpdate = saveStatus($db, $tableName, $vacancyLink[0]['id'], $query['status']);
    }

}