<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); $arComponentDescription = array(
"NAME" => "Список разделов",
"DESCRIPTION" => "Вывести список разделов",
"SORT" => 20,
"PATH" => array(
  "ID" => "content",
  "CHILD" => array(
    "ID" => "sections",
    "NAME" => GetMessage("T_IBLOCK_DESC_SECTION_LIST"),
    "SORT" => 40,
			"CHILD" => array(
			"ID" => "sections_cmpx",
		),
  ),
),
);
?>