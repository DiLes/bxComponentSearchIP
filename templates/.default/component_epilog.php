<?
// делаем $APPLICATION глобальной переменной
global $APPLICATION;
// устанавливаем titele
$APPLICATION->SetTitle($this->arResult['SEO']['ELEMENT_META_TITLE']);
// устанавливаем keywords
$APPLICATION->SetPageProperty("keywords",  $this->arResult["SEO"]["ELEMENT_META_KEYWORDS"]);
// устанавливаем description
$APPLICATION->SetPageProperty("description",  $this->arResult["SEO"]["ELEMENT_META_DESCRIPTION"]);
