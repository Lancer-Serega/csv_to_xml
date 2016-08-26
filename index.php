<?php
//Run: php script.php

define('CSV_FILE', 'data-example-4.csv');
define('JSON_FILE', 'data-example-1.json');
define('XML_FILE', 'data-example-1.xml');
define('ARRAY_SIZE', 26);

$tab = '    ';
$break = "\n";
//die(__DIR__);
/**
 * @param array  $key
 * @param array  $value
 * @param string $type |xml
 *
 * @return string
 */
function generate($key, $value, $type = 'xml')
{
    global $tab, $break;
    $str = '';

    if('xml' === $type) {
        $str = $tab . '<page>' . $break;

        for($i = 0; $i < ARRAY_SIZE; $i++) {
            if(!empty($key[$i])) {
                $str .= $tab . $tab . '<' . $key[$i] . '>' . $value[$i] . '</' . $key[$i] . '>' . $break;
            }
        }

        $str .= $tab . '</page>' . $break;
    }
    else if('json' === $type) {
        $str = $tab . '{' . $break;

        for($i = 0; $i < ARRAY_SIZE; $i++) {
            if($i !== 25) {
                $str .= $tab . $tab . $key[$i] . "\":\"" . $value[$i] . "\"," . $break;
            }
            else {
                $str .= $tab . $tab . $key[$i] . "\":\"" . $value[$i] . "\"" . $break;
            }
        }

        if($i !== 25) {
            $str .= $tab . '},' . $break;
        }

        else {
            $str .= $tab . '}' . $break;
        }
    }

    return $str;
}

$pathToModuleDir = __DIR__ . '\\files\\data-example-4.csv';

echo "Working... \n";

if(!empty($pathToModuleDir)) {
    echo 'File exists';
}
else {
    echo 'File is not exists';
}

$handle = fopen($pathToModuleDir, "r");
$keys = [];
$firstly = true;

//$json = "{\n";
$xml
    = '<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.10/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.10/ http://www.mediawiki.org/xml/export-0.10.xsd"
           version="0.10" xml:lang="en">' . "\n";

$xml .= $tab . '<siteinfo>' . $break;
$xml .= $tab . $tab . '<sitename>' . $break;

if($handle !== false) {

    while(($data = fgetcsv($handle, 0, ',')) !== false) {
        if($firstly) {
            $keys = $data;
            $firstly = false;
        }
        else if(!$firstly) {
//            $json .= generate($keys, $data, 'json');
            $xml .= generate($keys, $data, 'xml');
        }
    }

//    $json .= '}';
    $xml .= '</siteinfo>';

//    file_put_contents(JSON_FILE, $json);
    file_put_contents(XML_FILE, $xml);
    echo "Complete \n";
}

fclose($handle);

/**
 *  Для решения данной задачи мне потребуется:

 *  1. Изучить XML, CSV файл и понять где какие же данные и на каком месте находятся.
 *  2. Где находятся названия таблиц, где имена столбцов, а где находятся сами данные которые заполняются в данную
 * таблицу
 *  3. Написать парсер который будет парсить csv файл.
 *  4. Написать подпрограмму которая будет создавать XML файл и параллельно с парсером сразу в него записывать (в
 * случае ошибки все записанные в XML файл данные будут (опционально)уничтожены вместе с файлом)
 *  5. "Подточить" данное приложение под mediawiki
 *
 *
 *  Принцып работы программы по шагам
 *
 *  1. Проверить csv файл на существование (если не существует то 1.1)
 *      1.1 Вернуть ответ пользователю (Exception file is not found)
 *  2. Скан символов в поиске "запятой" которая и будет разделителем сущностей
 *      б) Если при скане мы видим "\". Он будет являться разделителем вложенностей DOM
 *      в) Скан должен будет проверять построчно.
 *  3.
 *
 */
