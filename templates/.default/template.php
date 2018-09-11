<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?foreach($arResult['SECTIONS'] as $arIblockSection):?>
  
  <?foreach($arIblockSection as $arSection):?>

    <div>
      <div class="title"><b><?=$arSection['NAME']?></b></div>
      <div class="text"><?=$arSection['DESCRIPTION']?></div>
    </div>
    <br>

  <?endforeach //$arIblockSection?>

<?endforeach; //$arResult['SECTIONS'] ?>

