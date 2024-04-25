<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    // название компонента
    'NAME' => 'Поиск по IP',
    // описание компонента
    'DESCRIPTION' => 'Поиск по IP',
    // путь к иконке компонента относительно папки компонента
    'ICON' => '/images/eaddlist.gif',
    // показывать кнопку очистки кеша
    'CACHE_PATH' => 'Y',
    // порядок сортировки в визуальном редакторе
    'SORT' => 30,
    // признак комплексного компонента
    'COMPLEX' => 'N',
    // расположение компонента в визуальном редакторе
    "PATH" => array(
        "ID" => "other",
    ),
);
