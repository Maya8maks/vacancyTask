<?php
function getSelect($db, $tableName, $query )
{
    $res = sql($db, "SELECT ".$tableName.'.id'." ,".$query['query']." FROM `".$tableName."` WHERE ".$query['status']." = 0 LIMIT 1", [], 'rows');
    return $res;
}

function saveStatus($db, $tableName, $query, $columnStatus)
{
    $resrUpdate = sql($db,
        'UPDATE `'.$tableName.'` set 
        `'.$columnStatus.'` = 2  
        WHERE `id` = ' . $query
    );
    return $resrUpdate;
}