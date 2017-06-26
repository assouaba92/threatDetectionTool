<!DOCTYPE HTML>
<html>
    <head>
     <title>Vulnerabilities</title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> 
    <style>         
        body{
         margin-top: 50px;
         text-align: center;
        }

        .panel, pre{
        background-color: rgba(15, 15, 15, 0.3);  
        }

        label{
         color:white; 
        }

        .panel,hr, pre{
         margin:0% 8% 2% 8%;   
        }

        .form-group{
         margin-top: 2%; 
         margin-left: 1%;
         margin-right: 1%;
        }

        #fileToUpload{
         margin: 0 auto;   
        }
        
        button{
         margin-top: 10px; 
         margin-right: 10px;
        }

        .glyphicon{
         margin-right: 10%;   
        }

        hr{
         border-width: 2px;  
         border-color: rgba(245, 245, 245, 0.4); 
        }
        
        .link {
         fill: none;
         stroke: #666;
         stroke-width: 2px;
        }
        .link.THREAT {
          stroke: red;
        }
        
        circle {
         fill: #ccc;
         stroke: #333;
         stroke-width: 1.5px;
        }
        
        text {
         font: 10px sans-serif;
         pointer-events: none;
         text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, 0 -1px 0 #fff, -1px 0 0 #fff;
        }
        div.tooltip {
         position: absolute;
         text-align: center;
         width: auto;
         height: auto;
         padding: 2px;
         font: 12px sans-serif;
         background: lightsteelblue;
         border: 0px;
         border-radius: 8px;
         pointer-events: none;
        }
        
        div#resourceInfo {
           position: absolute;
           right: 4px;
           cursor: text;
           width: auto;
           height: auto;
           z-index: 1000;
           background: #E5E4D6;
           border: solid 1px #aaa;
           border-radius: 8px;
           font-family: Verdana, Arial, Helvetica, sans-serif;
           font-size: 10px;
           padding: 4px;
           text-align: center;
        }   
        
        div#componentInfo {
           position: absolute;
           left: 4px;
           cursor: text;
           width: auto;
           height: auto;
           z-index: 1000;
           background: #E5E4D6;
           border: solid 1px #aaa;
           border-radius: 8px;
           font-family: Verdana, Arial, Helvetica, sans-serif;
           font-size: 10px;
           padding: 4px;
           text-align: center;
        }
     </style>
    </head>

    <body>
    <h2 class="text-center">Vulnerability Search</h2>
        
        <div class="panel">
           <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                    <div class="form-group">
                        <label>Select APK file to upload:</label><br/>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <button type="submit" name="upload" class="btn btn-primary"><span class="glyphicon glyphicon-import"></span>Upload APK</button>
                    </div>

                    <div class="form-group text-center">  
                        <button type="submit" name="uploaded" class="btn btn-default"><span class="glyphicon glyphicon-saved"></span>Check Uploaded</button><br/>
                        <button type="submit" name="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>Find Vulnerabilities</button>
                        <button type="submit" name="clear" class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span>Clear Result</button><br/>  

                    </div>
                    </div>
                 </form>  
            </div>
        </div>
    
    <hr>
        
    <?php
    // Check if file already exists
    if(isset($_FILES["fileToUpload"])){
    $target_dir = "COVERT/app_repo/ApkFiles/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $apkFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    }

    if(isset($_POST["upload"])) {
        if (file_exists($target_file)) {
            echo "<script type='text/javascript'>alert('Sorry, file already exists');</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($apkFileType != "apk") {
            echo "<script type='text/javascript'>alert('Sorry, only APK files allowed');</script>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<script type='text/javascript'>alert('Sorry, your file was not uploaded');</script>";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<script type='text/javascript'>alert('The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded');</script>";
            } else {
                echo "<script type='text/javascript'>alert('Sorry, there was an error uploading your file');</script>";
            }
        }  
    }
    
    if(isset($_POST["uploaded"])){
      $output= shell_exec('cd COVERT/app_repo/ApkFiles; ls *.apk');
      echo "<pre>$output</pre>";  
    }

    if(isset($_POST["submit"])){
      $cmd= shell_exec('cd COVERT; sh ./covert.sh ApkFiles');
      $output= shell_exec('cd COVERT/app_repo/ApkFiles; cp *.xml ../../../; ls *.xml');
      $vulnerabilities= shell_exec('cd COVERT/app_repo/ApkFiles/analysis/model; cp *.xml ../../../../../xml; ls');
      $command= shell_exec('php extract.php; php merge.php; php covert.php; php threats.php');
        
      echo '<div id="resourceInfo" class="panel_off"></div>
  <div id="componentInfo" class="panel_off"></div>
  <div id="feedback">
        <h4>Do you think the results are displayed correctly?</h4>
        <form>
            <button type="submit" class="btn btn-primary">Yes</button>
            <button type="reset" class="btn btn-default">No</button> 
        </form>
    </div>
<script src="//d3js.org/d3.v3.min.js"></script>
<script>

function getResourceInfo(n) {
  info="<div>Resources <br>";
  for(var i=0;i<n.length;i++)
  {
   info+=n[i];
   info+="<br>";
  }
 info+="</div>"
 return info;
}


function getComponentInfo(n) {
  info="<div>Components <br>";
  for(var i=0;i<n.length;i++)
  {
   info+=n[i];
   info+="<br>";
  }
 info+="</div>"
 return info;
}
// http://blog.thomsonreuters.com/index.php/mobile-patent-suits-graphic-of-the-day/
d3.json("covert_result.json", function(error, data){
   //use data here
var links=data.connection;
var resource=data.resources;
var component=data.components;
var nodes = {};
var action=[];
var result=[];

// Compute the distinct nodes from the links.
links.forEach(function(link) {
 link.source = nodes[link.source] || (nodes[link.source] = {name: link.source});
 link.target = nodes[link.target] || (nodes[link.target] = {name: link.target});
 action.push(link.type);
});
for (var i = 0; i < action.length; i++) {
       if (result.indexOf(action[i]) == -1) {
         result.push(action[i]);
}
}

var width =1000,
   height = 1000;

var force = d3.layout.force()
   .nodes(d3.values(nodes))
   .links(links)
   .size([width, height])
   .linkDistance(500)
   .charge(-300)
   .on("tick", tick)
   .start();

var svg = d3.select("body").append("svg")
   .attr("width", width)
   .attr("height", height);

// Per-type markers, as they dont inherit styles.
svg.append("defs").selectAll("marker")
   .data(result)
 .enter().append("marker")
   .attr("id", function(d) { return d; })
   .attr("viewBox", "0 -5 10 10")
   .attr("refX", 15)
   .attr("refY", -1.5)
   .attr("markerWidth", 5)
   .attr("markerHeight", 5)
   .attr("orient", "auto")
 .append("path")
   .attr("d", "M0,-5L10,0L0,5");

   var tooltip = d3.select("body").append("div")
   .attr("class", "tooltip")
   .style("opacity", 0);

var path = svg.append("g").selectAll("path")
   .data(force.links())
   .enter().append("path")
   .attr("class", function(d) {
     if(d.threat)
     {
       var newtype="THREAT";
       return "link " + newtype;
     }
     else
     {
      return "link " + d.type;
     }
   })
   .attr("marker-end", function(d) { return "url(#" + d.type + ")"; })
   .on("mouseover", function(d) {
     if(d.threat)
     {
       tooltip.transition().duration(500).style("opacity", .9);
        tooltip.html("<b>Action:</b>"+d.type+"<br>"+"<b>Sender Component:</b>"+d.sendercomponent+"<br>"+"<b>Receiver Component:</b>"+d.recievercomponent+"<br>"+"<b>Threat:</b>"+d.threat)
          .style("left", (d3.event.pageX) + "px")
          .style("top", (d3.event.pageY - 28) + "px");
      }
      else
      {
        tooltip.transition().duration(500).style("opacity", .9);
         tooltip.html("<b>Action:</b>"+d.type+"<br>"+"<b>Sender Component:</b>"+d.sendercomponent+"<br>"+"<b>Receiver Component:</b>"+d.recievercomponent)
           .style("left", (d3.event.pageX) + "px")
           .style("top", (d3.event.pageY - 28) + "px");
      }
 })
 .on("mouseout", function(d) {
   tooltip.transition().duration(500).style("opacity", 0);
 });

resourceInfoDiv = d3.select("#resourceInfo");
componentInfoDiv=d3.select("#componentInfo");
var circle = svg.append("g").selectAll("circle")
   .data(force.nodes())
 .enter().append("circle")
   .attr("r", 6)
   .call(force.drag)
   .on("click", function(d) {
   resource.forEach(function(res){
       if(d.name===res.source)
       {
       var permission=res.permission;
         showResourcePanel(permission);
       }
     //  console.log(str);
   })
   component.forEach(function(com){
       if(d.name===com.source)
       {
       var component=com.components;
         showComponentPanel(component);
       }
     //  console.log(str);
   })
 });
 function showResourcePanel( node) {
     // Fill it and display the panel
     resourceInfoDiv
 .html( getResourceInfo(node) )
 .attr("class","panel_on");
   }
   function showComponentPanel( node) {
       // Fill it and display the panel
       componentInfoDiv
   .html( getComponentInfo(node) )
   .attr("class","panel_on");
     }
var text = svg.append("g").selectAll("text")
   .data(force.nodes())
 .enter().append("text")
   .attr("x", 15)
   .attr("y", ".31em")
   .text(function(d) { return d.name; });

// Use elliptical arc path segments to doubly-encode directionality.
function tick() {
 path.attr("d", linkArc);
 circle.attr("transform", transform);
 text.attr("transform", transform);
}

function linkArc(d) {
 if(d.recievercomponent===null)
     {
       return "";
 }
 
 else{
 var dx = d.target.x - d.source.x,
     dy = d.target.y - d.source.y,
     dr = Math.sqrt(dx * dx + dy * dy);
 return "M" + d.source.x + "," + d.source.y + "A" + dr + "," + dr + " 0 0,1 " + d.target.x + "," + d.target.y;
 }
}

function transform(d) {
 return "translate(" + d.x + "," + d.y + ")";
}
})

$(document).ready(function() {
    $("#feedback form button").click( function() {
        $("#feedback").remove();
        alert("Thank you for the Feedback!");
    });
});
    </script>';
    }

    if(isset($_POST["clear"])){
      $cmd= shell_exec('rm -r *.xml; cd xml; rm -r *; cd ..; rm -r *.json; cd json; rm -r *; cd ../COVERT/app_repo/ApkFiles; rm -r ./*');
    }
    ?> 
        
    </body>
</html> 