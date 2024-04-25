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
            "select" => ["UF_IP"],
            "order" => ["ID" => "ASC"],
        ));

        while($arData = $rsData->Fetch()){
            echo '<pre>'; print_r($arData); echo '</pre>';
        }

        // Массив полей для добавления
        $data = array(
            "UF_IP" => '',
            "UF_COUNTRY" => '',
            "UF_REGION" => '',
            "UF_CITY" => '',
            "UF_LATITUDE" => '',
            "UF_LONGITUDE" => ''
        );

        //$result = $entity_data_class::add($data);

        $this->IncludeComponentTemplate();
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

    }

    public function configureActions(): array
    {
        return [
            'send' => [
                'prefilters' => [
                    // здесь указываются опциональные фильтры, например:
                    new ActionFilter\Authentication(), // проверяет авторизован ли пользователь
                ]
            ]
        ];
    }

    // Сюда передаются параметры из ajax запроса, навания точно такие же как и при отправке запроса.
    // $_REQUEST['username'] будет передан в $username, $_REQUEST['email'] будет передан в $email и т.д.
    public function sendAction($username = '', $email = '', $message = '')
    {
        try {
            $this->doSomeWork();
            return [
                "result" => "Ваше сообщение принято",
            ];
        } catch (Exceptions\EmptyEmail $e) {
            $this->errorCollection[] = new Error($e->getMessage());
            return [
                "result" => "Произошла ошибка",
            ];
        }
    }
}
