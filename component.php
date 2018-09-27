<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"]))
  $arParams["CACHE_TIME"] = 36000000;

if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;


//Все что находится в этом if кешируется
if ($this->StartResultCache(false, $_GET['PAGEN_1'])) // ТУТ НАДО ПОЗЖЕ РАЗОБРАТЬСЯ И ЧУТЬ ПЕРЕДЕЛАТЬ, А ТО СТРАНИЦА МОЖЕТ НЕ ТОЛЬКО ЧЕРЕЗ PAGEN_1 задаваться
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
    $arIblocks[$iblockParams['ID']] =  $iblockParams;
  }

  $arResult['IBLOCKS'] = $arIblocks;

  $arResult['SECTIONS'] = Array();
  foreach($arIblocks as $arIblock){
    $arFilter = array('IBLOCK_ID' => $arIblock['ID']); 
    $arSort = Array($arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']);
    if($arParams['SECTIONS_COUNT'])
      $arPageParams = Array('nPageSize' => $arParams['SECTIONS_COUNT']);
    $rsSect = CIBlockSection::GetList($arSort,$arFilter, false, Array('UF_*'), $arPageParams);
    while ($arSect = $rsSect->GetNext())
    {
      $arResult['SECTIONS'][$arSect['IBLOCK_ID']][] = $arSect;
    }
  }

  
  $navComponentParameters = array();
	if ($arParams["PAGER_BASE_LINK_ENABLE"] === "Y")
	{
		$pagerBaseLink = trim($arParams["PAGER_BASE_LINK"]);
		if ($pagerBaseLink === "")
		{
			if (
				$arResult["SECTION"]
				&& $arResult["SECTION"]["PATH"]
				&& $arResult["SECTION"]["PATH"][0]
				&& $arResult["SECTION"]["PATH"][0]["~SECTION_PAGE_URL"]
			)
			{
				$pagerBaseLink = $arResult["SECTION"]["PATH"][0]["~SECTION_PAGE_URL"];
			}
			elseif (
				isset($arItem) && isset($arItem["~LIST_PAGE_URL"])
			)
			{
				$pagerBaseLink = $arItem["~LIST_PAGE_URL"];
			}
		}

		if ($pagerParameters && isset($pagerParameters["BASE_LINK"]))
		{
			$pagerBaseLink = $pagerParameters["BASE_LINK"];
			unset($pagerParameters["BASE_LINK"]);
		}

		$navComponentParameters["BASE_LINK"] = CHTTP::urlAddParams($pagerBaseLink, $pagerParameters, array("encode"=>true));
	}

  $arResult["NAV_STRING"] = $rsSect->GetPageNavStringEx(
		$navComponentObject,
		$arParams["PAGER_TITLE"],
		$arParams["PAGER_TEMPLATE"],
		$arParams["PAGER_SHOW_ALWAYS"],
		$this,
		$navComponentParameters
  );

  $arResult["NAV_CACHED_DATA"] = null;
	$arResult["NAV_RESULT"] = $rsElement;
	$arResult["NAV_PARAM"] = $navComponentParameters;
  
   // Если выполнилось какое-то условие, то кешировать
   // данные не надо
   //  if (condition)
   //     $this->AbortResultCache();

   // Подключить шаблон вывода
   $this->IncludeComponentTemplate();
}
?>

