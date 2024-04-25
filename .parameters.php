<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// пространства имен highloadblock
use Bitrix\Highloadblock\HighloadBlockTable;

// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}

// проверяем установку модуля «Highload блоки»
if (!CModule::includeModule('highloadblock')) {
    return;
}

/*******************************/
// Инфоблоки                   //
/*******************************/

// получаем массив всех типов инфоблоков для возможности выбора
$arIBlockType = CIBlockParameters::GetIBlockTypes();
// пустой массив для вывода 
$arInfoBlocks = array();
// выбираем активные инфоблоки
$arFilterInfoBlocks = array('ACTIVE' => 'Y');
// сортируем по возрастанию поля сортировка
$arOrderInfoBlocks = array('SORT' => 'ASC');
// если уже выбран тип инфоблока, выбираем инфоблоки только этого типа
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
// метод выборки информационных блоков
$rsIBlock = CIBlock::GetList($arOrderInfoBlocks, $arFilterInfoBlocks);
// перебираем и выводим в адмику доступные информационные блоки
while ($obIBlock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}
// выбираем элементы инфоблока
$arFilterElement = ['IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']];
// метод выборки элементов инфоблока
$rsElement = CIBlockElement::GetList(
    array("ID" => "ASC"),
    $arFilterElement,
    false,
    false,
    array()
);
while ($obElement = $rsElement->Fetch()) {
    $arElementInfoBlocks[$obElement['ID']] = '[' . $obElement['ID'] . '] ' . $obElement['NAME'];
}

/*******************************/
// Highload блоки              //
/*******************************/

// пустой массив для вывода 
$arHlBlocksList = [];
// получаем список всех Highload блоков для возможности выбора
$hlblockIterator = HighloadBlockTable::getList();
while ($hlblock = $hlblockIterator->fetch()) {
    $arHlBlocksList[$hlblock['ID']] = '[' . $hlblock['ID'] . '] ' . $hlblock['NAME'];;
}
// получаем поля выбранного HL блока
if (!empty($arCurrentValues['HL_BLOCK'])) {
    $hlblockId = $arCurrentValues['HL_BLOCK'];
    // получаем информацию о Highload блоке
    $hlblock = HighloadBlockTable::getById($hlblockId)->fetch();
    // получаем описание сущности Highload блока
    $hlEntity = HighloadBlockTable::compileEntity($hlblock);
    // получаем список полей сущности
    $hlFields = $hlEntity->getFields();
    // наполняем список доступных полей
    foreach ($hlFields as $fieldName => $field) {
        $arHlBlocksFields[$fieldName] = $fieldName;
    }
}

// настройки компонента, формируем массив $arParams
$arComponentParameters = array(
    // основной массив с параметрами
    'PARAMETERS' => array(
        // выбор типа инфоблока
        'IBLOCK_TYPE' => array(                  // ключ массива $arParams в component.php
            'PARENT' => 'BASE',                  // название группы
            'NAME' => 'Выберите тип инфоблока',  // название параметра
            'TYPE' => 'LIST',                    // тип элемента управления, в котором будет устанавливаться параметр
            'VALUES' => $arIBlockType,           // входные значения
            'REFRESH' => 'Y',                    // перегружать настройки или нет после выбора (N/Y)
            'DEFAULT' => '',                 // значение по умолчанию
            'MULTIPLE' => 'N',                   // одиночное/множественное значение (N/Y)
        ),
        // выбор самого инфоблока
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Выберите родительский инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y',
            "DEFAULT" => '',
            "ADDITIONAL_VALUES" => "Y",
        ),
        // выбор элемента инфоблока
        'ELEMENT_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Выберите элемент инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arElementInfoBlocks,
            'REFRESH' => 'Y',
            "DEFAULT" => '',
            "ADDITIONAL_VALUES" => "Y",
        ),
        // выбор типа highload блока
        'HL_BLOCK' => [
            'PARENT' => 'BASE',
            'NAME' => 'Выберите тип HL блока',
            'TYPE' => 'LIST',
            'VALUES' => $arHlBlocksList,
            'REFRESH' => 'Y',
        ],
        // выбор самого highload блока
        'HL_BLOCK_SORT' => [
            'PARENT' => 'BASE',
            'NAME' => 'Поле для сортировки',
            'TYPE' => 'LIST',
            'VALUES' => $arHlBlocksFields,
            'REFRESH' => 'N',
        ],
        // настройки кэширования
        'CACHE_TIME' => array(
            'DEFAULT' => 3600
        ),
    ),
);
