<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"]))
  $arParams["CACHE_TIME"] = 36000000;

if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;

//Все что находится в этом if кешируется
if ($this->StartResultCache())
{

  CModule::IncludeModule("iblock");

  $arIblocks = Array();

  $iblockList = CIBlock::GetList(
    Array('SORT' => 'ASC'), 
    Array(
        'ID' => $arParams['IBLOCK_ID']
    ), false
  );

  while($iblockParams = $iblockList->Fetch())
  {
    $arIblocks[] =  $iblockParams;
  }

  $arResult['IBLOCKS'] = $arIblocks;

  $arResult['SECTIONS'] = Array();
  foreach($arIblocks as $arIblock){
    $arFilter = array('IBLOCK_ID' => $arIblock['ID']); 
    $arSort = Array($arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']);
    $rsSect = CIBlockSection::GetList($arSort,$arFilter, false, Array('UF_*'));
    while ($arSect = $rsSect->GetNext())
    {
      $arResult['SECTIONS'][$iblockID][] = $arSect;
    }
  }

  

   // Если выполнилось какое-то условие, то кешировать
   // данные не надо
   //  if (condition)
   //     $this->AbortResultCache();

   // Подключить шаблон вывода
   $this->IncludeComponentTemplate();
}
?>

