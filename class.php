<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc; // класс для работы с языковыми файлами
use Bitrix\Main\SystemException; // класс для всех исключений в системе
use Bitrix\Main\Loader; // класс для загрузки необходимых файлов, классов, модулей
use Bitrix\Highloadblock\HighloadBlockTable; // пространства имен highloadblock
use Bitrix\Main\Errorable;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;
use Bitrix\Main\Web\HttpClient;

Bitrix\Main\Loader::includeModule('highloadblock');
// основной класс, является оболочкой компонента унаследованного от CBitrixComponent
class SearchIP extends CBitrixComponent implements Controllerable
{

    // выполняет основной код компонента, аналог конструктора (метод подключается автоматически)
    public function executeComponent()
    {
        try {
            // подключаем метод проверки подключения модуля «Информационные блоки»
            $this->checkModules();
            // подключаем метод подготовки массива $arResult
            //$this->getResult();
            // подключаем поиск
            //$this->searchAction();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }

        $this->IncludeComponentTemplate();
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
    protected function checkDb($ip)
    {
        $hlbl = 14; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => ["*"],
            "order" => ["ID" => "ASC"],
            "filter" => ["UF_IP" => $ip]
        ))->Fetch();

        return !empty($rsData) ? $rsData : false;
    }

    protected function addDB($geoData) {

        $hlbl = 14; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        // Массив полей для добавления
        $data = array(
            "UF_IP" => $geoData['ip'],
            "UF_COUNTRY" => $geoData['country']['name_ru'],
            "UF_REGION" => $geoData['region']['name_ru'],
            "UF_CITY" => $geoData['city']['name_ru'],
            "UF_LATITUDE" => $geoData['city']['lat'],
            "UF_LONGITUDE" => $geoData['city']['lon']
        );
        if (!empty($geoData['ip'])){
            $result = $entity_data_class::add($data);
            return true;
        }

    }


    public function configureActions()
    {
        return [
            'send' => [
                'prefilters' => [
                    new HttpMethod(
                        array(HttpMethod::METHOD_POST)
                    ),
                    new Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    // Сюда передаются параметры из ajax запроса, навания точно такие же как и при отправке запроса.
    public function searchAction($ip)
    {
        if (!empty($ip)) {
            $issetIp = $this->checkDb($ip);
        }else{
            return '!!!поле IP пусто!!!';
        }


        $httpClient = new HttpClient();
//        $getResult = $httpClient->getResult(); // текст ответа
//        $getStatus = $httpClient->getStatus(); // код статуса ответа
//        $getContentType = $httpClient->getContentType(); // Content-Type ответа
//        $getEffectiveUrl = $httpClient->getEffectiveUrl(); // реальный url ответа, т.е. после редиректов
//        $getCookies = $httpClient->getCookies(); // объект Bitrix\Main\Web\HttpCookies
//        $getHeaders = $httpClient->getHeaders(); // объект Bitrix\Main\Web\HttpHeaders
//        $error = $httpClient->getError(); // массив ошибок
        $result = $httpClient->get("http://api.sypexgeo.net/jJIRp/json/$ip");
        @file_put_contents(__DIR__ . '/result.txt', print_r(json_decode($result), true));
//        @file_put_contents(__DIR__ . '/getResult.txt', print_r($getResult, true));
//        @file_put_contents(__DIR__ . '/getStatus.txt', print_r($getStatus, true));
//        @file_put_contents(__DIR__ . '/getContentType.txt', print_r($getContentType, true));
//        @file_put_contents(__DIR__ . '/getEffectiveUrl.txt', print_r($getEffectiveUrl, true));
//        @file_put_contents(__DIR__ . '/getCookies.txt', print_r($getCookies, true));
//        @file_put_contents(__DIR__ . '/getHeaders.txt', print_r($getHeaders, true));
//        @file_put_contents(__DIR__ . '/error.txt', print_r($error, true));


        if (!$issetIp) {
            @file_put_contents(__DIR__ . '/issetIp.txt', print_r($issetIp, true));

            // Фильтруем ботов, чтобы не было лишних запросов к API
            $is_bot = empty($_SERVER['HTTP_USER_AGENT']) || preg_match(
                    "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl|request|Guzzle|Java)~i",
                    $_SERVER['HTTP_USER_AGENT']
                );

            $geo = !$is_bot ? json_decode(
                $result,
                true) : [];
            // Все данные о IP
            @file_put_contents(__DIR__ . '/geo.txt', print_r($geo, true));

            // Выбираем нужные данные
            if ($is_bot) {
                echo 'Привет Google Bot, и другие боты';
            }
            elseif ($geo->country->iso == 'UA'){
                switch($geo->city->name_en) {
                    case 'Kyiv': echo 'Ваш город - Киев'; break;
                    case 'Lviv': echo 'Ваш город - Львов'; break;
                    default: echo 'Другой город Украины';
                }
            }
            else {
                echo 'Вы не из Украины';
            }

            if (!empty($geo)) {
                $this->addDB($geo);
            }else{
                return 'IP пустое или не найдено!!!';
            }


        } else {
            return $issetIp;

        }
    }
}
