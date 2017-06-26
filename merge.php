<?php
$connections=array();
$connection=array();
$complete_json =array();
$resource=array();
$app_compo=array();
foreach (glob('./json/*.json') as $json_path)
{
    $complete_json[]=$json_path;
}

foreach ($complete_json as $sender_app)
{
  $send_intent_a=array();
  $resources=array();
  $compo=array();
  $res=array();
  $c=array();
  $res_final=array();
  $Component=array();

  $file_sender=file_get_contents($sender_app);
  $json_sender=json_decode($file_sender,true);

  $con=count($json_sender['components']['Component']);
  if($con >1)
  {
    $sum=count($json_sender['components']['Component']['name']);
    if($sum<1 && $con>1)
    {
      foreach ($json_sender['components']['Component'] as $comp)
      {
        $compo[]=$comp['name'];
      }
    }
  else
  {
    $compo[]=$json_sender['components']['Component']['name'];
  }
}
    $c=array_unique($compo);

    foreach($c as $co)
    {
      $Component[]=$co;
    }
    $app_compo[]=array('source'=> $json_sender['name'], 'components'=> $Component);

foreach ($json_sender['usesPermissions']['permission'] as $permission) {
  $resources[]=$permission;
}

  $res=array_unique($resources);
  foreach($res as $r)
  {
    $res_final[]=$r;
  }
  $resource[]=array('source'=> $json_sender['name'], 'permission'=> $res_final);


  foreach ($complete_json as $reciever_app)
  {

    $recieve_intent_b=array();
    $sender_component=array();
    $reciever_component=array();


    $file_reciever=file_get_contents($reciever_app);
    $json_reciever=json_decode($file_reciever,true);

    foreach ((array)$json_sender['newIntents']['Intent'] as $intent)
      {
        $string=$intent['action'];
        $s=str_replace('"', "", $string);
        $send_intent_a[]=$s;
      }
      $send=array_unique($send_intent_a);


    foreach ($json_reciever['components']['Component'] as $component)
    {

        if(isset($component['filter']))
        {

          $check=count($component['filter']);
          if($check>1)
          {
              for($i=0;$i<$check;$i++)
              {
                $recieve_length=count($component['filter'][$i]['actions']);

                for($j=0;$j<$recieve_length;$j++)
                {
                  $recieve_intent_b[]=$component['filter'][$i]['actions'][$j];
                }
              }
          }
          else
          {
            $recieve_length=count($component['filter']['actions']);
            if($recieve_length>1)
            {
              for($j=0;$j<$recieve_length;$j++)
              {
                $recieve_intent_b[]=$component['filter']['actions'][$j];
              }
            }
            else
            {
              $recieve_intent_b[]=$component['filter']['actions'];
            }
          }
        }
        else if(isset($component['IntentFilter']))
        {
          $check=count($component['IntentFilter']['filter']);
          if($check>1)
          {
            $action_count=count($component['IntentFilter']['filter']['actions']);
            if($action_count<1 && $check>1)
            {
              for($i=0;$i<$check;$i++)
              {
                $recieve_length=count($component['IntentFilter']['filter'][$i]['actions']);
                for($j=0;$j<$recieve_length;$j++)
                {
                  $recieve_intent_b[]=$component['IntentFilter']['filter'][$i]['actions'][$j];
                }
              }
            }
            else
            {
            $recieve_intent_b[]=$component['IntentFilter']['filter']['actions'];
            }
          }
          else
          {
            $recieve_length=count($component['IntentFilter']['filter']['actions']);
            if($recieve_length>1)
            {
              for($j=0;$j<$recieve_length;$j++)
              {
                $recieve_intent_b[]=$component['IntentFilter']['filter']['actions'][$j];
              }
            }
            else
            {
              $recieve_intent_b[]=$component['IntentFilter']['filter']['actions'];
            }
          }
        }
      }
    $recieve=array_unique($recieve_intent_b);
    $sender_result=array_intersect((array)$send,(array)$recieve);



  foreach($sender_result as $result)
    {
      foreach((array)$json_sender['newIntents']['Intent'] as $intent)
      {
        $senderapp=$json_sender['name'];
        $send=$intent['action'];
        $s=str_replace('"', "", $send);
        if($s===$result)
        {
          $sender_component[]=$intent['sender'];
        }
      }
      $sendercomponent=array_unique($sender_component);


     foreach($json_reciever['components']['Component'] as $component)
      {
        $recieverapp=$json_reciever['name'];
        if(isset($component['filter']))
        {
          $check=count($component['filter']);
          if($check>1)
          {
              for($i=0;$i<$check;$i++)
              {
                $recieve_length=count($component['filter'][$i]['actions']);
                for($j=0;$j<$recieve_length;$j++)
                {
                  $recieve=$component['filter'][$i]['actions'][$j];
                    if($recieve===$result)
                    {
                      $reciever_component[]=$component['name'];
                    }
                }
              }
          }
          else
          {
            $recieve_length=count($component['filter']['actions']);
            if($recieve_length>1)
            {
              for($j=0;$j<$recieve_length;$j++)
              {
                $recieve=$component['filter']['actions'][$j];
                  if($recieve===$result)
                  {
                    $reciever_component[]=$component['name'];
                  }
              }
            }
            else
            {
              $recieve=$component['filter']['actions'];
                if($recieve===$result)
                {
                  $reciever_component[]=$component['name'];
                }
            }
          }
        }
        else if(isset($component['IntentFilter']))
        {
          $check=count($component['IntentFilter']['filter']);
          if($check>1)
          {
            $action_count=count($component['IntentFilter']['filter']['actions']);
            if($action_count<1 && $check>1)
            {
              for($i=0;$i<$check;$i++)
              {
                $recieve_length=count($component['IntentFilter']['filter'][$i]['actions']);
                for($j=0;$j<$recieve_length;$j++)
                {
                  $recieve=$component['IntentFilter']['filter'][$i]['actions'][$j];
                    if($recieve===$result)
                    {
                      $reciever_component[]=$component['name'];
                    }
                }
              }
            }
            else
            {
              $recieve=$component['IntentFilter']['filter']['actions'];
              if($recieve===$result)
              {
                $reciever_component[]=$component['name'];
              }
            }
          }
          else
          {
            $recieve_length=count($component['IntentFilter']['filter']['actions']);
            if($recieve_length>1)
            {
              for($j=0;$j<$recieve_length;$j++)
              {
                $recieve=$component['IntentFilter']['filter']['actions'][$j];
                  if($recieve===$result)
                  {
                    $reciever_component[]=$component['name'];
                  }
              }
            }
            else
            {
              $recieve=$component['IntentFilter']['filter']['actions'];
                if($recieve===$result)
                {
                  $reciever_component[]=$component['name'];
                }
            }
          }
        }
      }
      $recievercomponent=array_unique($reciever_component);

      foreach($sendercomponent as $sender)
      {

        foreach ($recievercomponent as $reciever )
        {

          $connection[]= array('source'=> $senderapp, 'sendercomponent'=> $sender,'target'=>$recieverapp,'recievercomponent'=>$reciever,'type'=>$result);
        }
      }
  }
}
}

 $connections['connection'] = $connection;
  $connections['resources']=$resource;
  $connections['components']=$app_compo;
  $file = fopen('final_result.json', 'w');
  $s=json_encode($connections);
  fwrite($file,$s);
  fclose($file);
?>
