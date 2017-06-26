<?php
$file_threat=file_get_contents('covert.json');
$json_threat=json_decode($file_threat,true);

$file_result=file_get_contents('final_result.json');
$json_result=json_decode($file_result,true);
$resource=array();
$connections=array();
$connection=array();
$component=array();

foreach($json_threat['vulnerabilities']['vulnerability'] as $threats)
{
  $threat_type=$threats['type'];

  foreach($threats['vulnerabilityElements'] as $t)
  {
    $type=$t['type'];
    if($type==='COMPONENT')
    {
      $component[]=$t['description'];
    }
  }
}
 foreach ($json_result['connection'] as $s_component)
  {
    foreach ($component as $c)
    {
      if($s_component['sendercomponent']===$c)
      {
        $source=$s_component['source'];
        $sendercomponent=$s_component['sendercomponent'];
        $target=$s_component['target'];
        $recievercomponent=$s_component['recievercomponent'];
        $type=$s_component['type'];
        $intentname=$threat_type;
        $connection[]= array('source'=> $source, 'sendercomponent'=> $sendercomponent,'target'=>$target,'recievercomponent'=>$recievercomponent,'type'=>$type,'threat'=>$intentname);
      }
    }
      $source=$s_component['source'];
      $sendercomponent=$s_component['sendercomponent'];
      $target=$s_component['target'];
      $recievercomponent=$s_component['recievercomponent'];
      $type=$s_component['type'];
      $connection[]= array('source'=> $source, 'sendercomponent'=> $sendercomponent,'target'=>$target,'recievercomponent'=>$recievercomponent,'type'=>$type);
    }
foreach ($json_result['resources'] as $permission)
{
  $resources=array();
  $res=array();
  $res_final=array();
  $name=$permission['source'];
  foreach($permission['permission'] as $p)
  {
      $resources[]=$p;
  }

  $res=array_unique($resources);
  foreach($res as $r)
  {
    $res_final[]=$r;
  }
  $resource[]=array('source'=>$name , 'permission'=> $res_final);
}

foreach ($json_result['components'] as $component)
{
  $comp=array();
  $c=array();
  $com_final=array();
  $name=$component['source'];
  foreach($component['components'] as $co)
  {
      $comp[]=$co;
  }

  $c=array_unique($comp);
  foreach($c as $com)
  {
    $com_final[]=$com;
  }
  $app_comp[]=array('source'=>$name , 'components'=> $com_final);
}
$connections['connection'] = $connection;
$connections['resources']=$resource;
$connections['components']=$app_comp;
$file = fopen('covert_result.json', 'w');
$s=json_encode($connections);
fwrite($file,$s);
fclose($file);
?>
