<?php
$dom = new DOMDocument();
$dom->load('ApkFiles.xml');
$xpath = new DOMXPath($dom);
$deleteaction=array();
$type=$dom->getElementsByTagName('type');
foreach ($type as $t)
{
  if($t->nodeValue==='METHOD')
  {
    $deleteaction[] = $t->parentNode;
  }
}
foreach ( $deleteaction as $elementToDelete )
{
    $elementToDelete->parentNode->removeChild($elementToDelete);
}

$alloy=$dom->getElementsByTagName('alloyLabel');
foreach($alloy as $a)
{
  $a->parentNode->removeChild($a);
}

$app=$dom->getElementsByTagName('apps');
foreach ($app as $apps)
{
    $apps->parentNode->removeChild($apps);
}

$name=$dom->getElementsByTagName('name');
foreach ($name as $n)
{
    $n->parentNode->removeChild($n);
}

$s = simplexml_import_dom($dom);
$fp = fopen('covert.json', 'w');
fwrite($fp, json_encode($s));
fclose($fp);

?>
