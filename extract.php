<?php
$xmlfiles=array();
foreach (glob('./xml/*.xml') as $xml)
{
    $xmlfiles[]=$xml;
}
foreach ($xmlfiles as $xml_name)
{
$dom = new DOMDocument();
$dom->load($xml_name);
$xpath = new DOMXPath($dom);
$deleteaction=array();

$Intent = $dom->getElementsByTagName('Intent');
foreach ($Intent as $i)
{
  $child=$i->childNodes;
  foreach ($child as $c)
  {
    if($c->nodeName==="action")
    {
      if($c->nodeValue==='')
      {
         $deleteaction[] = $c->parentNode;
      }
    }
    else if($c->nodeName==="sender")
      {
        continue;
      }
    else
      {
        $deleteaction[]=$c;
      }
  }

}
foreach ( $deleteaction as $elementToDelete ) {
    $elementToDelete->parentNode->removeChild($elementToDelete);
}

$contentprovider= $dom->getElementsByTagName('ContentProviders');
foreach ($contentprovider as $content)
    {
      $content->parentNode->removeChild($content);
    }


$permission= $dom->getElementsByTagName('actuallyUsesPermissions');
foreach ($permission as $actualpermission)
    {
      $actualpermission->parentNode->removeChild($actualpermission);
    }


$requirepermission= $dom->getElementsByTagName('requiredPermissions');
foreach ($requirepermission as $rpermission)
    {
      $rpermission->parentNode->removeChild($rpermission);
    }

$component=$dom->getElementsByTagName('Component');
for($i=0;$i<=$component->length;$i++)
{

$propagatedpermission = $dom->getElementsByTagName('PropagatedPermissions');
foreach ($propagatedpermission as $pp)
    {
      $pp->parentNode->removeChild($pp);
    }

$requiredpermission = $dom->getElementsByTagName('RequiredPermissions');
foreach ($requiredpermission as $rr)
    {
      $rr->parentNode->removeChild($rr);
    }


$pathdata = $dom->getElementsByTagName('pathData');
foreach ($pathdata as $pd)
    {
      $pd->parentNode->removeChild($pd);
    }
}

$packagename=$dom->getElementsByTagName('packageName');
foreach ($packagename as $pn)
  {
    $pn->parentNode->removeChild($pn);
  }

$apk=$dom->getElementsByTagName('apkFile');
foreach ($apk as $a)
  {
    $a->parentNode->removeChild($a);
  }

foreach( $xpath->query('//*[not(node())]') as $node ) {
     $node->parentNode->removeChild($node);
   }
$name=$dom->getElementsByTagName('name');
foreach ($name as $Name ) {
  $parent=$Name->parentNode;
  if($parent->nodeName==='application')
{
  $app_name=$Name->nodeValue;
}

}
$app_name.=".json";
$file="./json/";
$file.=$app_name;
$s = simplexml_import_dom($dom);
$fp = fopen($file, 'w');
fwrite($fp, json_encode($s));
fclose($fp);
}
?>
