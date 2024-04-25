<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<div class="container">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Введите IP...">
        <button class="btn btn-outline-secondary" type="button" id="search-ip">Button</button>
    </div>
</div>
<div class="oborudovanie-head">
    <div class="container">
        <div class="oborudovanie-head__inner">
            <div class="oborudovanie-head__content">
                <h1><?= $arResult['SEO']['ELEMENT_PAGE_TITLE'] ?></h1>
                <?= $arResult['DETAIL_TEXT'] ?>
                <a class="ms-button ms-button-primary oborudovanie-open" href="#">Оставить заявку</a>
            </div>
            <div class="oborudovanie-head__image">
                <img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" alt="Автоматизация розницы под ключ" width="<?= $arResult['DETAIL_PICTURE']['WIDTH'] / 2 ?>" height="<?= $arResult['DETAIL_PICTURE']['HEIGHT'] / 2 ?>">
            </div>
        </div>
    </div>
</div>

<div class="oborudovanie-card">
    <div class="container">
        <div class="grid">
            <div class="col-xs-12 col-sm-4">
                <div class="oborudovanie-card__item">
                    <div class="oborudovanie-card__tetle">Необходимая техника</div>
                    <div class="oborudovanie-card__description">Проверенные модели ККТ для&nbsp;вашего бизнеса. Интеграция с&nbsp;ОФД</div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="oborudovanie-card__item">
                    <div class="oborudovanie-card__tetle">Все по закону</div>
                    <div class="oborudovanie-card__description">Полное соответствие законам&nbsp;об&nbsp;онлайн-кассах и&nbsp;маркировке товаров</div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="oborudovanie-card__item">
                    <div class="oborudovanie-card__tetle">Быстрый старт </div>
                    <div class="oborudovanie-card__description">Помощь в&nbsp;настройке, сопровождение партнерами и&nbsp;поддержка 24/7</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="oborudovanie-tool">
    <div class="container">
        <div class="oborudovanie-tool__tetle">
            <h2>Комплекты торгового <br class="d-none d-lg-block"> оборудования</h2>
        </div>
        <div class="oborudovanie-tool__description">
            <p>&mdash;&nbsp;Выберите готовый комплект или <br class="d-none d-lg-block"> мы&nbsp;поможем собрать подходящий.</p>
            <p>&mdash;&nbsp;Все модели онлайн-касс соответствуют 54-ФЗ <br class="d-none d-lg-block"> и&nbsp;включены в&nbsp;госреестр контрольно-кассовой техники.</p>
            <p>&mdash;&nbsp;Подходят рознице, общепиту, сфере услуг.</p>
        </div>
        <? foreach ($arResult['OBORUDOVANIE'] as $key => $value) : ?>
            <div class="oborudovanie-tool__item">
                <div class="grid">
                    <div class="col-12 col-md-6 order-2 order-md-1">
                        <div class="oborudovanie-tool__item-tetle"><?= $value['UF_NAME'] ?></div>
                        <div class="oborudovanie-tool__item-description"><?= $value['UF_DESCRIPTION'] ?></div>
                        <ul>
                            <? for ($i = 1; $i < 5; $i++) : ?>
                                <? if (!empty($value['UF_NAIMENOVANIE_' . $i]) && !empty($value['UF_PRICE_' . $i])) : ?>
                                    <li><?= $value['UF_NAIMENOVANIE_' . $i] ?> <span><?= number_format($value['UF_PRICE_' . $i], 0, '', ' ') ?> ₽</span></li>
                                <? endif ?>
                            <? endfor ?>
                        </ul>
                        <div class="container">
                            <div class="grid">
                                <a class="ms-button ms-button-primary oborudovanie-open order-2 order-sm-1" href="#" data_oborudovanie="<?= $value['UF_NAME'] ?>">Заказать</a>
                                <div class="oborudovanie-tool__item-price order-1 order-sm-2">Цена: <?= number_format($value['ITOGO'], 0, '', ' ') ?>₽</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 order-1 order-md-2">
                        <img src="<?= $value['PICTURE']['SRC'] ?>" alt=" <?= $value['UF_NAME'] ?>">
                    </div>
                </div>
            </div>
        <? endforeach ?>
    </div>
</div>

<div class="oborudovanie-icemaker">
    <div class="container">
        <div class="oborudovanie-icemaker__card with-3">
            <div class="oborudovanie-icemaker__card-item oborudovanie-icemaker__card-item--full">
                <h3>Возьмем все <br class="d-none d-sm-block"> вопросы на себя</h3>
                <p>Подбираем и настраиваем: <br> ККТ, сканеры штрихкодов, ТСД, принтеры для печати этикеток и другое оборудование.</p>
                <p>Также настраиваем интеграцию с товароучетной системой персонально под ваши задачи.</p>
                <svg viewBox="0 0 470 255" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_99_7750)">
                        <rect x="83" y="1" width="170" height="170" rx="36.7188" fill="url(#paint0_radial_99_7750)" />
                        <g clip-path="url(#clip1_99_7750)">
                            <path d="M145.943 75.3536L161.727 50.1236C162.339 49.1264 163.198 48.3046 164.222 47.7383C165.246 47.172 166.399 46.8805 167.569 46.8922V46.8922C168.49 46.8589 169.409 47.0107 170.27 47.3385C171.132 47.6664 171.919 48.1636 172.585 48.8009C173.252 49.4381 173.783 50.2025 174.149 51.0487C174.515 51.895 174.707 52.806 174.715 53.7279V71.8115H201.996C203.004 71.8442 203.993 72.0899 204.9 72.5324C205.806 72.9749 206.608 73.6043 207.254 74.3791C207.9 75.1539 208.374 76.0566 208.646 77.0279C208.918 77.9991 208.981 79.0169 208.831 80.0143L203.86 112.08C203.648 113.794 202.817 115.371 201.524 116.515C200.231 117.66 198.565 118.292 196.838 118.294H156.072C154.132 118.302 152.216 117.855 150.479 116.989L146.005 114.752" stroke="white" stroke-width="9.17969" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M145.943 75.3542V114.566" stroke="white" stroke-width="9.17969" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M131.214 75.3535H145.942V114.566H131.214C130.39 114.566 129.6 114.238 129.017 113.656C128.435 113.073 128.107 112.283 128.107 111.459V78.4607C128.107 77.6366 128.435 76.8463 129.017 76.2636C129.6 75.6809 130.39 75.3535 131.214 75.3535V75.3535Z" stroke="white" stroke-width="9.17969" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <rect x="217" y="86" width="170" height="170" rx="36.7188" fill="white" />
                        <path d="M288.864 137.428L291.813 129.817C292.31 128.528 293.185 127.42 294.323 126.637C295.461 125.854 296.808 125.433 298.19 125.428H303.813C305.194 125.433 306.542 125.854 307.68 126.637C308.818 127.42 309.692 128.528 310.19 129.817L313.138 137.428L323.15 143.188L331.241 141.954C332.588 141.771 333.96 141.993 335.181 142.591C336.402 143.19 337.417 144.137 338.098 145.314L340.841 150.114C341.544 151.31 341.868 152.69 341.77 154.074C341.672 155.457 341.157 156.778 340.293 157.863L335.287 164.24V175.76L340.43 182.137C341.294 183.222 341.809 184.543 341.907 185.926C342.005 187.31 341.681 188.69 340.978 189.886L338.235 194.686C337.554 195.862 336.539 196.81 335.318 197.408C334.097 198.007 332.726 198.228 331.378 198.046L323.287 196.811L313.275 202.571L310.327 210.183C309.83 211.471 308.955 212.58 307.817 213.363C306.679 214.146 305.331 214.567 303.95 214.571H298.19C296.808 214.567 295.461 214.146 294.323 213.363C293.185 212.58 292.31 211.471 291.813 210.183L288.864 202.571L278.853 196.811L270.761 198.046C269.414 198.228 268.043 198.007 266.822 197.408C265.601 196.81 264.585 195.862 263.904 194.686L261.161 189.886C260.458 188.69 260.134 187.31 260.232 185.926C260.33 184.543 260.845 183.222 261.71 182.137L266.715 175.76V164.24L261.573 157.863C260.708 156.778 260.193 155.457 260.095 154.074C259.997 152.69 260.321 151.31 261.024 150.114L263.767 145.314C264.448 144.137 265.463 143.19 266.684 142.591C267.905 141.993 269.277 141.771 270.624 141.954L278.715 143.188L288.864 137.428ZM287.287 170C287.287 172.712 288.091 175.364 289.598 177.619C291.105 179.874 293.247 181.632 295.753 182.67C298.259 183.708 301.016 183.98 303.677 183.451C306.337 182.921 308.781 181.615 310.699 179.697C312.617 177.779 313.923 175.336 314.452 172.675C314.981 170.015 314.709 167.258 313.671 164.752C312.633 162.246 310.876 160.104 308.62 158.597C306.365 157.09 303.714 156.286 301.001 156.286C297.364 156.286 293.876 157.73 291.304 160.302C288.732 162.874 287.287 166.363 287.287 170Z" fill="url(#paint1_radial_99_7750)" />
                    </g>
                    <defs>
                        <radialGradient id="paint0_radial_99_7750" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(185.398 189.007) rotate(-111.896) scale(274.58 311.853)">
                            <stop stop-color="#1EB4FF" />
                            <stop offset="0.933093" stop-color="#64C8FF" stop-opacity="0" />
                            <stop offset="1" stop-color="#64C8FF" stop-opacity="0" />
                        </radialGradient>
                        <radialGradient id="paint1_radial_99_7750" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(309.377 224.013) rotate(-110.255) scale(142.401 151.808)">
                            <stop stop-color="#1EB4FF" />
                            <stop offset="0.933093" stop-color="#64C8FF" stop-opacity="0" />
                            <stop offset="1" stop-color="#64C8FF" stop-opacity="0" />
                        </radialGradient>
                        <clipPath id="clip0_99_7750">
                            <rect width="470" height="254" fill="white" transform="translate(0 0.750977)" />
                        </clipPath>
                        <clipPath id="clip1_99_7750">
                            <rect width="87" height="87" fill="white" transform="translate(125 39)" />
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="oborudovanie-icemaker__card-item oborudovanie-icemaker__card-item--one">
                <p>Наши партнеры подберут оборудование и&nbsp;предложат программное обеспечение</p>
            </div>
            <div class="oborudovanie-icemaker__card-item oborudovanie-icemaker__card-item--one">
                <p>Доставят, подключат и&nbsp;настроят всю необходимую технику</p>
            </div>
        </div>
    </div>
</div>