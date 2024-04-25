<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc; // класс для работы с языковыми файлами
use Bitrix\Main\SystemException; // класс для всех исключений в системе
use Bitrix\Main\Loader; // класс для загрузки необходимых файлов, классов, модулей
use Bitrix\Highloadblock\HighloadBlockTable; // пространства имен highloadblock

// основной класс, является оболочкой компонента унаследованного от CBitrixComponent
class CIblocListOborudovanie extends CBitrixComponent
{

    // выполняет основной код компонента, аналог конструктора (метод подключается автоматически)
    public function executeComponent()
    {
        try {
            // подключаем метод проверки подключения модуля «Информационные блоки»
            $this->checkModules();
            // подключаем метод подготовки массива $arResult
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }

    // подключение языковых файлов (метод подключается автоматически)
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    // проверяем установку модуля «Информационные блоки» (метод подключается внутри класса try...catch)
    protected function checkModules()
    {
        // если модуль информационные блоки не подключен
        if (!Loader::includeModule('iblock')) {
            // выводим сообщение в catch
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }

        // если модуль highload блоки не подключен
        if (!CModule::IncludeModule('highloadblock')) {
            // выводим сообщение в catch
            throw new SystemException(Loc::getMessage('HIGHLOAD_MODULE_NOT_INSTALLED'));
        }
    }

    // обработка массива $arParams (метод подключается автоматически)
    public function onPrepareComponentParams($arParams)
    {
        // время кеширования
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 36000;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }
        // возвращаем в метод новый массив $arParams     
        return $arParams;
    }

    // подготовка массива $arResult (метод подключается внутри класса try...catch)
    protected function getResult()
    {
        $hlbl = 1; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            //"filter" => array("UF_PRODUCT_ID"=>"77","UF_TYPE"=>'33')  // Задаем параметры фильтра выборки
        ));

        while($arData = $rsData->Fetch()){
            echo '<pre>'; print_r($arData); echo '</pre>';
        }

        /*
        $res = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'select' => array('*', 'NAME_LANG' => 'LANG.NAME'),
            'order' => array('NAME_LANG' => 'ASC', 'NAME' => 'ASC')
        ));
        while ($row = $res->fetch())
        {
            echo '<pre>'; print_r($row); echo '</pre>';
            if ($row['NAME_LANG'] != '')
            {
                echo $row['NAME_LANG'];//языкозависимое название есть
            }
            else
            {
                echo $row['NAME'];//языкозависимого названия нет
            }
        }*/

        /*
        // если нет валидного кеша, получаем данные из БД
        if ($this->startResultCache()) {

            $flag = true;

            // создаем объект Query, в качестве параметра передаем объект сущности (элемент инфоблока)
            $query = new Bitrix\Main\Entity\Query(
                \Bitrix\Iblock\Elements\ElementOborudovanieapiTable::getEntity()
            );

            // выбираем что попадет в выборку
            $query->setSelect(array('ID', 'NAME', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'OBORUDOVANIE_' => 'OBORUDOVANIE'))
                // ставим фильтр
                ->setFilter(array('IBLOCK_ID' => $this->arParams['IBLOCK_ID']));
            // выполняем запрос
            $result = $query->exec();

            // заполняем arResult
            while ($row = $result->fetch()) {

                // получаем данные картинки
                if (!empty($row['DETAIL_PICTURE']) && $flag) {
                    $row['DETAIL_PICTURE'] = CFile::GetFileArray($row['DETAIL_PICTURE']);
                }

                // один раз заполняем общий массив
                if ($row && $flag) {
                    $this->arResult = $row;
                    $flag = false;
                }

                // если поле оборудование заполнено
                if (!empty($row['OBORUDOVANIE_VALUE'])) {
                    // делаем выборку хайлоуд блока
                    $arHLBlock = HighloadBlockTable::getById($this->arParams['HL_BLOCK'])->fetch();
                    // инициализируем класс сущности хайлоуд блока
                    $obEntity = HighloadBlockTable::compileEntity($arHLBlock);
                    // обращаемся к DataManager
                    $strEntityDataClass = $obEntity->getDataClass();
                    // стандартный запрос getList 
                    $rsData = $strEntityDataClass::getList(array(
                        'select' => array('*'),
                        'order' => array($this->arParams['HL_BLOCK_SORT'] => 'ASC'),
                        'filter' => array('=UF_XML_ID' => $row['OBORUDOVANIE_VALUE']),
                    ));
                    // выполняем запрос
                    $array = $rsData->fetchALL();
                    // получаем данные картинки
                    $array[0]['PICTURE'] = CFile::GetFileArray($array[0]['UF_FILE']);
                    $array[0]['ITOGO'] = $array[0]['UF_PRICE_1'] + $array[0]['UF_PRICE_2'] + $array[0]['UF_PRICE_3'] + $array[0]['UF_PRICE_4'] + $array[0]['UF_PRICE_5'];
                    // заполняем arResult
                    $this->arResult['OBORUDOVANIE'][] = $array[0];
                }
            }

            // устанавливаем SEO
            $ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($this->arParams['IBLOCK_ID'], $this->arParams['ELEMENT_ID']);
            $this->arResult['SEO'] = $ipropElementValues->getValues();

            // очищаем массив
            unset($this->arResult['OBORUDOVANIE_VALUE'], $this->arResult['OBORUDOVANIE_ID'], $this->arResult['OBORUDOVANIE_IBLOCK_ELEMENT_ID'], $this->arResult['OBORUDOVANIE_IBLOCK_PROPERTY_ID']);

            // кэш не затронет весь код ниже, он будут выполняться на каждом хите, здесь работаем с другим $arResult, будут доступны только те ключи массива, которые перечислены в вызове SetResultCacheKeys()
            if (isset($this->arResult)) {
                // ключи $arResult перечисленные при вызове этого метода, будут доступны в component_epilog.php и ниже по коду, обратите внимание там будет другой $arResult
                $this->SetResultCacheKeys(
                    array(
                        'SEO'
                    )
                );
                // подключаем шаблон и сохраняем кеш
                $this->IncludeComponentTemplate();
            } else { // если выяснилось что кешировать данные не требуется, прерываем кеширование и выдаем сообщение «Страница не найдена»
                $this->AbortResultCache();
                \Bitrix\Iblock\Component\Tools::process404(
                    Loc::getMessage('PAGE_NOT_FOUND'),
                    true,
                    true
                );
            }
        }*/
    }
}
