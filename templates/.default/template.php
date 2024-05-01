<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CJSCore::Init(['masked_input']);
?>
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>-->

<div class="container">
    <div class="input-group mb-3">
        <input type="text" id="phone" name="input-ip" class="form-control" placeholder="Введите IP..." />
        <button class="btn btn-outline-secondary" type="button" id="search-ip">Поиск</button>
    </div>
</div>
<script>
    BX.ready(function() {
        var result = new BX.MaskedInput({
            mask: '999.999.999.999', // устанавливаем маску
            input: BX('phone'),
            placeholder: '_' // символ замены ___.___.___.___
        });
        result.setValue('000.000.000.000'); // устанавливаем значение
    });
</script>