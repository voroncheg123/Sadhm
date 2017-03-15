<?php
function GG_funx_calculate_moonphase($term_in){
  $term_out="";
  if($term_in==0 or $term_in==29){$term_out="New";}
  elseif($term_in==7){$term_out="First Quarter";} 
  elseif($term_in==14){$term_out="Full";} 
  elseif($term_in==22){$term_out="Third Quarter";} 
  if($term_out==""){
      $term_in=floor($term_in/29*4);
      if($term_in==3){$term_out="Waning Crescent";}
      elseif($term_in==2){$term_out="Waning Gibbous";}
      elseif($term_in==1){$term_out="Waxing Gibbous";}
      elseif($term_in==0){$term_out="Waxing Crescent";} 
    }
  return $term_out;
}


function GG_funx_cutout($data, $start, $end) {
    $from = strpos($data, $start) + strlen($start);
    if($from === false) {return false;}
    $to = @strpos($data, $end, $from); 
    if($to === false) {return false;} 
    return substr($data, $from, $to-$from);
  }
  
function GG_funx_get_code($code_string){  
  $j=substr_count($code_string,"*");
  $code_array[0][0]=0;
  for($i=1;$i<=$j;$i++){$code_array[$i][0]=strpos($code_string,"*",$code_array[$i-1][0]+1);}
  $code_array[$i][0]=strlen($code_string);
  for($i=0;$i<=$j;$i++){$code_array[$i][1]=str_replace("*","",substr($code_string,$code_array[$i][0],$code_array[$i+1][0]-$code_array[$i][0]));}
  $back=$code_array[rand(0,$j)][1];
  return $back; 
}

function GG_funx_get_content($term_in,$timeout)
{
    if( ini_get('allow_url_fopen') ) 
      {
      $opts = array('http' => array('timeout' => $timeout));
      $context  = stream_context_create($opts);
      $term_out = @file_get_contents($term_in,false,$context);
    }
    else {
      $ch = curl_init();
      curl_setopt ($ch, CURLOPT_URL, $term_in);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
      $term_out = @curl_exec($ch);
      curl_close($ch);  
    }
    return $term_out;
}

function GG_funx_get_weather_data($gg_weather,$key_wun,$location_string_wun,$key_fwo,$location_string_fwo,$opt_provider_preference,$opt_get_better,$imagefolder_check,$imageloc,$imagefolder,$time_corr,$timeout){

        list($wun_help_a,$wun_parsed,$fwo_help_a,$fwo_parsed)=GG_funx_initialize_GG_arrays();        
        if($opt_provider_preference=="wun")
        {
            $url="http://api.wunderground.com/api/".$key_wun."/astronomy/conditions/forecast7day/q/".$location_string_wun.".json";
            $wun_string = GG_funx_get_content($url,$timeout); 
            $wun_parsed = json_decode($wun_string,true);
            //print_r($wun_parsed); 
            $wun_help_a=$wun_parsed['current_observation'];
            if(!$wun_help_a){$gg_weather[0][99][0]="Error";}
            else
            {
              $gg_weather[0][0][0]="";   //Date_mon_weekday
              $gg_weather[0][1][0]=$wun_help_a['display_location']['full'];
              $gg_weather[0][1][1]=$wun_help_a['observation_time'];  //Ort und Land
              $gg_weather[0][2][0]=$wun_help_a['icon'];      //zB chancerain!!!!  CODE
              $gg_weather[0][2][1]=$wun_help_a['icon_url'];
              $gg_weather[0][2][2]=$wun_help_a['weather'];
              //$gg_weather[0][2][2]="Sunny";    //"Chance of rain" // "Rain Showers"
              $gg_weather[0][5][0]=$wun_help_a['temp_f']; //aktuelle Temperatur
              $gg_weather[0][5][1]=$wun_help_a['temp_c'];   //aktuelle Temperatur
              $gg_weather[0][6][0]=$wun_help_a['windchill_f'];    //Windchill
              $gg_weather[0][6][1]=$wun_help_a['windchill_c'];
              if($gg_weather[0][6][0]="NA"){$gg_weather[0][6][0]=$gg_weather[0][5][0];$gg_weather[0][6][1]=$gg_weather[0][5][1];}
              $gg_weather[0][7][0]="";    //hi
              $gg_weather[0][7][1]="";    //hi
              $gg_weather[0][8][0]="";    //lo
              $gg_weather[0][8][1]="";    //lo
              if(substr_count($wun_help_a['relative_humidity'],'%')){
                $gg_weather[0][10]=substr($wun_help_a['relative_humidity'],0,strlen($wun_help_a['relative_humidity'])-1);}
              else{$gg_weather[0][10]=$wun_help_a['relative_humidity'];}
              $gg_weather[0][11][0]=$wun_help_a['wind_dir'];    //Text??? West statt W???
              $gg_weather[0][11][1]="";    //Kurz
              $gg_weather[0][11][2]=$wun_help_a['wind_degrees'];    //Degrees
              $gg_weather[0][11][1]=GG_funx_translate_winddirections_degrees($gg_weather[0][11][2]);
              $gg_weather[0][11][3]=$wun_help_a['wind_mph'];    //speed mph
              $gg_weather[0][11][4]=gg_funx_translate_speed($gg_weather[0][11][3],"kmph");    //speed kmh
              $gg_weather[0][11][5]=$wun_help_a['wind_gusts_mph'];    //Gusts  mph
              $gg_weather[0][11][6]=gg_funx_translate_speed($gg_weather[0][11][5],"kmph");;    //Gusts  kmh
              $gg_weather[0][12][1]=$wun_help_a['pressure_mb'];    //Pressure_MB
              $gg_weather[0][12][0]=$wun_help_a['pressure_in'];    //Presssure_IN
              $gg_weather[0][13]=$wun_help_a['pressure_trend'];    //Presssure_trend
              $gg_weather[0][14][0]=$wun_help_a['dewpoint_f'];    //dewpoint f
              $gg_weather[0][14][1]=$wun_help_a['dewpoint_c'];    //dewpoint c
              $gg_weather[0][15][0]=$wun_help_a['visibility_mi']; //visibility
              $gg_weather[0][15][1]=$wun_help_a['visibility_km'];
              $gg_weather[0][16]=$wun_help_a['pop'];           //probABILITY of precipitation 
              $gg_weather[0][17][0]=$wun_help_a['precip_today_inch'];           //amount of precipitation 
              $gg_weather[0][17][1]=$wun_help_a['precip_today_metric'];    
              $gg_weather[0][18]="";           //Cloadcover
              $wun_help_a=$wun_parsed['moon_phase'];
              $gg_weather[0][19][0]=$wun_help_a['percentIlluminated'];
              $gg_weather[0][19][1]=$wun_help_a['ageOfMoon'];
              $gg_weather[0][19][2]=GG_funx_calculate_moonphase($gg_weather[0][19][1]);
              $gg_weather[0][19][3]=$wun_help_a['sunset']['hour'];
              $gg_weather[0][19][4]=$wun_help_a['sunset']['minute'];
              $gg_weather[0][19][5]=$wun_help_a['sunrise']['hour'];
              $gg_weather[0][19][6]=$wun_help_a['sunrise']['minute'];
              $gg_weather[0][19][7]=""; //resesrved for day night flag           
              $wun_help_a=$wun_parsed['forecast']['simpleforecast']['forecastday']['0'];
              $gg_weather[0][0][1]= $wun_help_a['date']['month'];
              $gg_weather[0][0][2]= $wun_help_a['date']['day'];
              $gg_weather[0][0][3]= $wun_help_a['date']['weekday'];   
              $wun_counter=count($wun_parsed['forecast']['simpleforecast']['forecastday']);
              for($i=0;$i<count($wun_parsed['forecast']['simpleforecast']['forecastday']);$i++)
              {
                $wun_help_a=$wun_parsed['forecast']['simpleforecast']['forecastday'][$i];
                $gg_weather[$i+1][0][1]= $wun_help_a['date']['month'];
                $gg_weather[$i+1][0][2]= $wun_help_a['date']['day'];
                if(substr($gg_weather[$i+1][0][2],0,1)=="0"){$gg_weather[$i+1][0][2]=substr($gg_weather[$i+1][0][2],1,1);}
                $gg_weather[$i+1][0][3]= $wun_help_a['date']['weekday'];
                $gg_weather[$i+1][2][0]= $wun_help_a['icon'];
                $gg_weather[$i+1][2][1]= $wun_help_a['icon_url'];
                $gg_weather[$i+1][2][2]= $wun_help_a['conditions'];    
                $gg_weather[$i+1][2][3]="";    
                $gg_weather[$i+1][2][4]="";  
                $gg_weather[$i+1][2][5]="";
                $gg_weather[$i+1][2][6]="";
                $gg_weather[$i+1][7][0]= $wun_help_a['high']['fahrenheit'];
                $gg_weather[$i+1][7][1]= $wun_help_a['high']['celsius'];
                $gg_weather[$i+1][8][0]= $wun_help_a['low']['fahrenheit'];
                $gg_weather[$i+1][8][1]= $wun_help_a['low']['celsius'];
                $gg_weather[$i+1][10]=   $wun_help_a['avehumidity']; //'relative_humidity'];}}
                $gg_weather[$i+1][11][0]=$wun_help_a['avewind']['dir'];; //'wind_dir'];}    //Text??? West statt W???
                $gg_weather[$i+1][11][1]=GG_funx_translate_winddirections_degrees($wun_help_a['avewind']['degrees']);    //Kurz   WNW for example
                $gg_weather[$i+1][11][2]=$wun_help_a['avewind']['degrees'];   //Degrees
                $gg_weather[$i+1][11][3]=$wun_help_a['avewind']['mph'];"";   //speed mph
                $gg_weather[$i+1][11][4]=$wun_help_a['avewind']['kph'];;  //speed kmh
                $gg_weather[$i+1][11][5]=$wun_help_a['maxwind']['mph'];;  //Gusts  mph
                $gg_weather[$i+1][11][6]=$wun_help_a['maxwind']['kph'];; //Gusts  kmh
                $gg_weather[$i+1][16]=$wun_help_a['pop'];
                $gg_weather[$i+1][17][0]=$wun_help_a['qpf_allday']['in'];           //amount of precipitation 
                $gg_weather[$i+1][17][1]=$wun_help_a['qpf_allday']['mm'];  
              }   
              $j=0;
              for($i=0;$i<count($wun_parsed['forecast']['txt_forecast']['forecastday'])-1;$i++)
                {
                $wun_help_a=$wun_parsed['forecast']['txt_forecast']['forecastday'][$i];
                $pos=strpos($wun_help_a['title'],"ight",0);
                  if($pos>0){
                  $gg_weather[-$j+$i][4][0]= $wun_help_a['fcttext'];
                  $gg_weather[-$j+$i][4][1]= $wun_help_a['fcttext_metric'];
                  $j=$j+1;
                  }
                  else
                  {
                  //echo "Old: ".$gg_weather[-$j+$i+1][2][0];
                  $gg_weather[-$j+$i+1][3][0]= $wun_help_a['fcttext'];
                  $gg_weather[-$j+$i+1][3][1]= $wun_help_a['fcttext_metric'];
                  $gg_weather[-$j+$i+1][2][0]= $wun_help_a['icon'];
                  $gg_weather[-$j+$i+1][2][1]= $wun_help_a['icon_url'];
                  ///echo "New: ".$wun_help_a['icon']." ".$gg_weather[-$j+$i+1][3][0]."<br />";
                  }
                }
              if($gg_weather[0][2][0]==""){  //there are some stations which dont deliver an actual weather image -> take it from the fc_text, if this fails as well from today
                $wun_help_a=$wun_parsed['forecast']['txt_forecast']['forecastday'][0];
                $gg_weather[0][2][0]=str_replace("nt_","",$wun_help_a['icon']);
                $gg_weather[0][2][1]=$wun_help_a['icon_url'];
                $gg_weather[0][2][2]=$wun_help_a['weather'];  
              }
              if($gg_weather[0][2][0]==""){  //there are some stations which dont deliver an actual weather image -> take it from the fc_text, if this fails as well from today
                $wun_help_a=$wun_parsed['forecast']['txt_forecast']['forecastday'][1];
                $gg_weather[0][2][0]=str_replace("nt_","",$wun_help_a['icon']);
                $gg_weather[0][2][1]=$wun_help_a['icon_url'];
                $gg_weather[0][2][2]=$wun_help_a['weather'];  
              }
            }
        } // end wu
        if($opt_provider_preference=="fwo")
        {
            $url=  "http://free.worldweatheronline.com/feed/weather.ashx?q=".$location_string_fwo."&format=json&num_of_days=5&key=".$key_fwo;
            $fwo_string = GG_funx_get_content($url,$timeout); 
            $fwo_parsed = json_decode($fwo_string,true);
            //print_r($fwo_parsed); 
            $fwo_help_a=$fwo_parsed['data']['request'];
            $gg_weather[0][1][0]=  $fwo_help_a['0']['query'];  //Ort und Land
            $fwo_help_a=$fwo_parsed['data']['current_condition'];
            if(!$fwo_help_a){$gg_weather[0][99][1]="Error";}
            else
            {
              $gg_weather[0][1][1]=$fwo_help_a['0']['observation_time'];
              $gg_weather[0][2][0]=$fwo_help_a['0']['weatherCode'];
              $gg_weather[0][2][1]=$fwo_help_a['0']['weatherIconUrl']['0']['value'];
              $gg_weather[0][2][2]=$fwo_help_a['0']['weatherDesc']['0']['value'];
              $gg_weather[0][5][0]=$fwo_help_a['0']['temp_F']; //aktuelle Temperatur
              $gg_weather[0][6][1]=$fwo_help_a['0']['temp_C'];   //aktuelle Temperatur
              $gg_weather[0][6][0]=$fwo_help_a['0']['temp_F']; //aktuelle Temperatur
              $gg_weather[0][5][1]=$fwo_help_a['0']['temp_C']; 
              $gg_weather[0][10]=$fwo_help_a['0']['humidity'];
              $gg_weather[0][11][1]=$fwo_help_a['0']['winddir16Point'];    //Kurz
              $gg_weather[0][11][2]=$fwo_help_a['0']['winddirDegree'];    //Degrees
              $gg_weather[0][11][3]=$fwo_help_a['0']['windspeedMiles'];    //Speed  mph
              $gg_weather[0][11][4]=$fwo_help_a['0']['windspeedKmph'];    //speed  kmmh
              $gg_weather[0][11][5]="";    //Gusts  mph
              $gg_weather[0][11][6]="";    //Gusts  kmh
              $gg_weather[0][12][1]=$fwo_help_a['0']['pressure'];    //Pressure_MB
              $gg_weather[0][12][0]=GG_funx_translate_pressure($gg_weather[0][12][1],"in","xx");    //Presssure_IN
              $gg_weather[0][15][1]=$fwo_help_a['0']['visibility'];
              $gg_weather[0][17][1]=$fwo_help_a['0']['precipMM'];
              $gg_weather[0][17][0]=GG_funx_translate_inch($gg_weather[0][17][1],"->in");             //amount of precipitation 
              $gg_weather[0][18]=$fwo_help_a['0']['cloudcover'];           //Cloadcover            
              for($i=0;$i<count($fwo_parsed['data']['weather']);$i++)
              {
                $fwo_help_a=$fwo_parsed['data']['weather'][$i];
                $gg_weather[$i+1][0][0]= $fwo_help_a['date'];      
                $date=mktime(0,0,0,substr($gg_weather[$i+1][0][0],5,2),substr($gg_weather[$i+1][0][0],8,2),substr($gg_weather[$i+1][0][0],0,4));
                $gg_weather[$i+1][0][1]=substr($gg_weather[$i+1][0][0],5,2);
                $gg_weather[$i+1][0][2]= substr($gg_weather[$i+1][0][0],8,2);
                if(substr($gg_weather[$i+1][0][2],0,1)=="0"){$gg_weather[$i+1][0][2]=substr($gg_weather[$i+1][0][2],1,1);}
                $gg_weather[$i+1][0][3]=date("l",$date);
                $gg_weather[$i+1][2][0]=$fwo_help_a['weatherCode'];
                $gg_weather[$i+1][2][1]= $fwo_help_a['weatherIconUrl']['0']['value'];
                $gg_weather[$i+1][2][2]= $fwo_help_a['weatherDesc']['0']['value'];
                $gg_weather[$i+1][2][3]="";  
                $gg_weather[$i+1][2][4]="";  
                $gg_weather[$i+1][2][5]="";
                $gg_weather[$i+1][2][6]="";
                $gg_weather[$i+1][3][0]="";  
                $gg_weather[$i+1][3][1]="";  
                $gg_weather[$i+1][4][0]="";
                $gg_weather[$i+1][4][1]="";
                $gg_weather[$i+1][7][0]= $fwo_help_a['tempMaxF'];
                $gg_weather[$i+1][7][1]= $fwo_help_a['tempMaxC'];
                $gg_weather[$i+1][8][0]= $fwo_help_a['tempMinF'];
                $gg_weather[$i+1][8][1]= $fwo_help_a['tempMinC'];
                $gg_weather[$i+1][11][0]=""; //'wind_dir'];}    //Text??? West statt W???
                $gg_weather[$i+1][11][1]=$fwo_help_a['winddir16Point'];    //Kurz
                $gg_weather[$i+1][11][2]=$fwo_help_a['winddirDegree'];    //Degrees
                $gg_weather[$i+1][11][3]=$fwo_help_a['windspeedMiles'];    //Speed  mph
                $gg_weather[$i+1][11][4]=$fwo_help_a['windspeedKmph'];
                $gg_weather[$i+1][11][5]="";  //Gusts  mph
                $gg_weather[$i+1][11][6]=""; //Gusts  kmh
                $gg_weather[$i+1][16]="";
                $gg_weather[$i+1][17][1]=$fwo_help_a['precipMM'];
                $gg_weather[$i+1][17][0]=GG_funx_translate_inch($gg_weather[$i+1][17][1],"->in");                                           
              }   
                $gg_weather[0][0][1]=$gg_weather[1][0][1];
                $gg_weather[0][0][2]=$gg_weather[1][0][2]; 
                $gg_weather[0][0][3]=$gg_weather[1][0][3];
          }           
        } //end fwo       
        if(!$gg_weather[0][99][0] and !$gg_weather[0][99][1]){
          
          if($opt_get_better=="checked")
           {//echo "if opt_better";              
              if($opt_provider_preference=="wun"){
                $url=  "http://free.worldweatheronline.com/feed/weather.ashx?q=".$location_string_fwo."&format=json&num_of_days=5&key=".$key_fwo;
                $fwo_string = GG_funx_get_content($url,$timeout); 
                $fwo_parsed = json_decode($fwo_string,true);
                $fwo_help_a=$fwo_parsed['data']['request'];
                if($gg_weather[0][1][0]=="" or !$gg_weather[0][1][0]){$gg_weather[0][1]=  $fwo_help_a['0']['query'];}  //Ort und Land
                $fwo_help_a=$fwo_parsed['data']['current_condition'];
                if($gg_weather[0][1][1]=="") {$gg_weather[0][1][1]=  $fwo_help_a['0']['observation_time'];}
                if($gg_weather[0][2][0]=="") {$gg_weather[0][2][0]=$fwo_help_a['0']['weatherCode'];}
                if($gg_weather[0][2][1]=="") {$gg_weather[0][2][1]=$fwo_help_a['0']['weatherIconUrl']['0']['value'];}
                if($gg_weather[0][2][2]=="") {$gg_weather[0][2][2]=$fwo_help_a['0']['weatherDesc']['0']['value'];}
                if($gg_weather[0][5][0]=="") {$gg_weather[0][5][0]=$fwo_help_a['0']['temp_F'];} //aktuelle Temperatur
                if($gg_weather[0][5][1]=="") {$gg_weather[0][5][1]=$fwo_help_a['0']['temp_C'];}  //aktuelle Temperatur
                if($gg_weather[0][6][0]=="") {$gg_weather[0][6][0]=$fwo_help_a['0']['temp_F'];} //aktuelle Temperatur
                if($gg_weather[0][6][1]=="") {$gg_weather[0][6][1]=$fwo_help_a['0']['temp_C'];}
                if($gg_weather[0][10]==""){$gg_weather[0][10]=$fwo_help_a['0']['humidity'];}
                if($gg_weather[0][11][1]==""){$gg_weather[0][11][0]=$fwo_help_a['0']['winddir16Point'];}    //Kurz
                if($gg_weather[0][11][1]==""){$gg_weather[0][11][1]=$fwo_help_a['0']['winddir16Point'];}    //Kurz
                if($gg_weather[0][11][2]==""){$gg_weather[0][11][2]=$fwo_help_a['0']['winddirDegree'];}    //Degrees
                if($gg_weather[0][11][3]==""){$gg_weather[0][11][3]=$fwo_help_a['0']['windspeedMiles'];}    //Speed  mph
                if($gg_weather[0][11][4]==""){$gg_weather[0][11][4]=$fwo_help_a['0']['windspeedKmph'];}    //speed  kmmh
                if($gg_weather[0][12][1]==""){$gg_weather[0][12][1]=$fwo_help_a['0']['pressure'];}    //Pressure_MB
                if($gg_weather[0][12][0]==""){$gg_weather[0][12][0]=GG_funx_translate_pressure($gg_weather[0][12][0],"in","xx");}    //Presssure_IN
                if($gg_weather[0][15][1]==""){$gg_weather[0][15][1]=$fwo_help_a['0']['visibility'];}
                if($gg_weather[0][17][1]==""){$gg_weather[0][17][1]=$fwo_help_a['0']['precipMM'];            //amount of precipitation 
                    $gg_weather[0][17][0]=GG_funx_translate_inch($gg_weather[0][17][1],"->in");}
                if($gg_weather[0][18]==""){$gg_weather[0][18]=$fwo_help_a['0']['cloudcover'];}           //Cloadcover 
                for($i=0;$i<count($fwo_parsed['data']['weather']);$i++)
                {
                  $fwo_help_a=$fwo_parsed['data']['weather'][$i];
                  $gg_weather[$i+1][0][0]= $fwo_help_a['date'];      
                  $date=mktime(0,0,0,substr($gg_weather[$i+1][0][0],5,2),substr($gg_weather[$i+1][0][0],8,2),substr($gg_weather[$i+1][0][0],0,4));
                  if($gg_weather[$i+1][0][1]==""){$gg_weather[$i+1][0][1]=substr($gg_weather[$i+1][0][0],5,2); }
                  if($gg_weather[$i+1][0][2]==""){$gg_weather[$i+1][0][2]= substr($gg_weather[$i+1][0][0],8,2);}
                  if($gg_weather[$i+1][0][3]==""){$gg_weather[$i+1][0][3]=date("l",$date);}
                  if($gg_weather[$i+1][7][0]==""){$gg_weather[$i+1][7][0]= $fwo_help_a['tempMaxF'];}
                  if($gg_weather[$i+1][7][1]==""){$gg_weather[$i+1][7][1]= $fwo_help_a['tempMaxC'];}
                  if($gg_weather[$i+1][8][0]==""){$gg_weather[$i+1][8][0]= $fwo_help_a['tempMinF'];}
                  if($gg_weather[$i+1][8][1]==""){$gg_weather[$i+1][8][1]= $fwo_help_a['tempMinC'];}
                  if($gg_weather[$i+1][2][0]==""){$gg_weather[$i+1][2][0]= $fwo_help_a['weatherCode'];}
                  if($gg_weather[$i+1][2][1]==""){$gg_weather[$i+1][2][1]= $fwo_help_a['weatherIconUrl']['0']['value'];}
                  if($gg_weather[$i+1][2][2]==""){$gg_weather[$i+1][2][2]=$fwo_help_a['weatherDesc']['0']['value'];}
                  if($gg_weather[$i+1][11][1]==""){$gg_weather[$i+1][11][1]=$fwo_help_a['winddir16Point'];}    //Kurz
                  if($gg_weather[$i+1][11][2]==""){$gg_weather[$i+1][11][2]=$fwo_help_a['winddirDegree'];}    //Degrees
                  if($gg_weather[$i+1][11][3]==""){$gg_weather[$i+1][11][3]=$fwo_help_a['windspeedMiles'];}    //Speed  mph
                  if($gg_weather[$i+1][11][4]==""){$gg_weather[$i+1][11][4]=$fwo_help_a['windspeedKmph'];}
                  if($gg_weather[$i+1][17][1]==""){$gg_weather[$i+1][17][1]=$fwo_help_a['precipMM'];
                    $gg_weather[$i+1][17][0]=GG_funx_translate_inch($gg_weather[$i+1][17][1],"->in");}
                  if($gg_weather[0][18]==""){$gg_weather[0][18]=$fwo_help_a[0]['cloudcover'];}        
                }   
              if($gg_weather[0][0][1]==""){$gg_weather[0][0][1]=$gg_weather[1][0][1];}
              if($gg_weather[0][0][2]==""){$gg_weather[0][0][2]=$gg_weather[1][0][2];} 
              if($gg_weather[0][0][3]==""){$gg_weather[0][0][3]=$gg_weather[1][0][3];}         
            } //end fwo get better
            if($opt_provider_preference=="fwo"){     
                  $url="http://api.wunderground.com/api/".$key_wun."/astronomy/conditions/forecast7day/q/".$location_string_wun.".json";
                  $wun_string = GG_funx_get_content($url,$timeout); 
                  $wun_parsed = json_decode($wun_string,true);
                  $wun_help_a=$wun_parsed['current_observation'];
                  if($gg_weather[0][1][0]==""){$gg_weather[0][1]=$wun_help_a['display_location']['full'];}
                  if($gg_weather[0][1][1]==""){$gg_weather[0][1][1]=$wun_help_a['observation_time'];}
                  if($gg_weather[0][2][0]==""){$gg_weather[0][2][0]=$wun_help_a['icon'];}      //zB chancerain!!!!
                  if($gg_weather[0][2][1]==""){$gg_weather[0][2][1]=$wun_help_a['icon_url'];}
                  if($gg_weather[0][2][2]==""){$gg_weather[0][2][2]=$wun_help_a['condition'];}    //"Chance of rain" // "Rain Showers"
                  if($gg_weather[0][5][0]==""){$gg_weather[0][5][0]=$wun_help_a['temp_f'];} //aktuelle Temperatur
                  if($gg_weather[0][5][1]==""){$gg_weather[0][5][1]=$wun_help_a['temp_c'];}   //aktuelle Temperatur
                  if($gg_weather[0][6][0]==""){$gg_weather[0][6][0]=$wun_help_a['windchill_c'];}    //Windchill
                  if($gg_weather[0][6][1]==""){$gg_weather[0][6][1]=$wun_help_a['windchill_f'];}  
                  if($gg_weather[0][10]==""){  if(substr_count($wun_help_a['relative_humidity'],'%')){
                      $gg_weather[0][10]=substr($wun_help_a['relative_humidity'],0,strlen($wun_help_a['relative_humidity'])-1);}
                    else{$gg_weather[0][10]=$wun_help_a['relative_humidity'];}}
                  if($gg_weather[0][11][0]==""){$gg_weather[0][11][0]=$wun_help_a['wind_dir'];}    //Text??? West statt W???
                  if($gg_weather[0][11][2]==""){$gg_weather[0][11][2]=$wun_help_a['wind_degrees'];}    //Degrees
                  if($gg_weather[0][11][1]==""){$gg_weather[0][11][1]=GG_funx_translate_winddirections_degrees($gg_weather[0][11][2]);}
                  if($gg_weather[0][11][3]==""){$gg_weather[0][11][3]=$wun_help_a['wind_mph'];}    //speed mph
                  if($gg_weather[0][11][4]==""){$gg_weather[0][11][4]=gg_funx_translate_speed($gg_weather[0][11][3],"kmph");}    //speed kmh
                  if($gg_weather[0][11][5]==""){$gg_weather[0][11][5]=$wun_help_a['wind_gusts_mph'];}    //Gusts  mph
                  if($gg_weather[0][11][6]==""){$gg_weather[0][11][6]=gg_funx_translate_speed($gg_weather[0][11][5],"kmph");}    //Gusts  kmh
                  if($gg_weather[0][12][1]==""){$gg_weather[0][12][1]=$wun_help_a['pressure_mb'];}    //Pressure_MB
                  if($gg_weather[0][12][0]==""){$gg_weather[0][12][0]=$wun_help_a['pressure_in'];}    //Presssure_IN
                  if($gg_weather[0][13]==""){$gg_weather[0][13]=$wun_help_a['pressure_trend'];}    //Presssure_trend
                  if($gg_weather[0][14][0]==""){$gg_weather[0][14][0]=$wun_help_a['dewpoint_f'];}    //dewpoint f
                  if($gg_weather[0][14][1]==""){$gg_weather[0][14][1]=$wun_help_a['dewpoint_c'];}    //dewpoint c
                  if($gg_weather[0][15][0]==""){$gg_weather[0][15][0]=$wun_help_a['visibility_mi'];} //visibility
                  if($gg_weather[0][15][1]==""){$gg_weather[0][15][1]=$wun_help_a['visibility_km'];}
                  if($gg_weather[0][16]==""){$gg_weather[0][16]=$wun_help_a['pop'];}           //probABILITY of precipitation 
                  $wun_help_a=$wun_parsed['moon_phase'];
                  if($gg_weather[0][19][0]==""){$gg_weather[0][19][0]=$wun_help_a['percentIlluminated'];}
                  if($gg_weather[0][19][1]==""){$gg_weather[0][19][1]=$wun_help_a['ageOfMoon'];}
                  if($gg_weather[0][19][2]==""){$gg_weather[0][19][2]=GG_funx_calculate_moonphase($gg_weather[0][19][1]);}
                  if($gg_weather[0][19][3]==""){$gg_weather[0][19][3]=$wun_help_a['sunset']['hour'];}
                  if($gg_weather[0][19][4]==""){$gg_weather[0][19][4]=$wun_help_a['sunset']['minute'];}
                  if($gg_weather[0][19][5]==""){$gg_weather[0][19][5]=$wun_help_a['sunrise']['hour'];}
                  if($gg_weather[0][19][6]==""){$gg_weather[0][19][6]=$wun_help_a['sunrise']['minute'];}           
                  $wun_help_a=$wun_parsed['forecast']['simpleforecast']['forecastday'][0];
                  if($gg_weather[0][0][0]==""){$gg_weather[0][0][0]= $wun_help_a['date']['month'];}
                  if($gg_weather[0][0][1]==""){$gg_weather[0][0][1]= $wun_help_a['date']['day'];}
                  if($gg_weather[0][0][2]==""){$gg_weather[0][0][2]= $wun_help_a['date']['weekday'];}   
                  $wun_counter=count($wun_parsed['forecast']['simpleforecast']['forecastday']);
                  for($i=0;$i<count($wun_parsed['forecast']['simpleforecast']['forecastday']);$i++)
                  {
                      $wun_help_a=$wun_parsed['forecast']['simpleforecast']['forecastday'][$i];
                      if($gg_weather[$i+1][0][1]==""){$gg_weather[$i+1][0][1]= $wun_help_a['date']['month'];}
                      if($gg_weather[$i+1][0][2]==""){$gg_weather[$i+1][0][2]= $wun_help_a['date']['day'];}
                      if($gg_weather[$i+1][0][3]==""){$gg_weather[$i+1][0][3]= $wun_help_a['date']['weekday'];}
                      if($gg_weather[$i+1][7][0]==""){$gg_weather[$i+1][7][0]= $wun_help_a['high']['fahrenheit'];}
                      if($gg_weather[$i+1][7][1]==""){$gg_weather[$i+1][7][1]= $wun_help_a['high']['celsius'];}
                      if($gg_weather[$i+1][8][0]==""){$gg_weather[$i+1][8][0]= $wun_help_a['low']['fahrenheit'];}
                      if($gg_weather[$i+1][8][1]==""){$gg_weather[$i+1][8][1]= $wun_help_a['low']['celsius'];}
                      if($gg_weather[$i+1][2][0]==""){$gg_weather[$i+1][2][0]= $wun_help_a['icon'];}
                      if($gg_weather[$i+1][2][1]==""){$gg_weather[$i+1][2][1]= $wun_help_a['icon_url'];}
                      if($gg_weather[$i+1][16]==""){$gg_weather[$i+1][16]=$wun_help_a['pop'];}
                      if($gg_weather[$i+1][10]==""){$gg_weather[$i+1][10]=$wun_help_a['avehumidity'];}
                      if($gg_weather[$i+1][11][0]==""){$gg_weather[$i+1][11][0]=$wun_help_a['avewind']['dir'];}    //Text??? West statt W???
                      if($gg_weather[$i+1][11][1]==""){$gg_weather[$i+1][11][1]=GG_funx_translate_winddirections_degrees($wun_help_a['avewind']['degrees']); } 
                      if($gg_weather[$i+1][11][2]==""){$gg_weather[$i+1][11][2]=$wun_help_a['avewind']['degrees']; }
                      if($gg_weather[$i+1][11][3]==""){$gg_weather[$i+1][11][3]=$wun_help_a['avewind']['mph'];""; }
                      if($gg_weather[$i+1][11][4]==""){$gg_weather[$i+1][11][4]=$wun_help_a['avewind']['kph'];}
                      if($gg_weather[$i+1][11][5]==""){$gg_weather[$i+1][11][5]=$wun_help_a['maxwind']['mph'];}
                      if($gg_weather[$i+1][11][6]==""){$gg_weather[$i+1][11][6]=$wun_help_a['maxwind']['kph'];}
                      if($gg_weather[$i+1][17][0]==""){$gg_weather[$i+1][17][0]=$wun_help_a['qpf_allday']['in'];}
                      if($gg_weather[$i+1][17][1]==""){$gg_weather[$i+1][17][1]=$wun_help_a['qpf_allday']['mm'];} 
                  }   
                  $j=0;
                  for($i=0;$i<count($wun_parsed['forecast']['txt_forecast']['forecastday'])-1;$i++)
                  {             
                      $wun_help_a=$wun_parsed['forecast']['txt_forecast']['forecastday'][$i];
                      $pos=strpos($wun_help_a['title'],"ight",0);
                        if($pos>0){
                        $gg_weather[-$j+$i][4][0]= $wun_help_a['fcttext'];
                        $gg_weather[-$j+$i][4][1]= $wun_help_a['fcttext_metric'];
                        $j=$j+1;
                        }
                        else
                        {
                        $gg_weather[-$j+$i+1][3][0]= $wun_help_a['fcttext'];
                        $gg_weather[-$j+$i+1][3][1]= $wun_help_a['fcttext_metric'];
                        }
                }
            }  //end wu get better
        } //end get better
        $gg_weather[0][19][7]="day";
        $pos1=strpos($gg_weather[0][2][1],"night");
        $pos2=strpos($gg_weather[0][2][1],"nt_");
        $pos=$pos1+$pos2;       
        if($pos>0){
          $gg_weather[0][19][7]="night";
        }    
        $term_out=GG_funx_translate_weather_code_into_icon($gg_weather[0][2][0],$gg_weather[0][19][7]);        
        $gg_weather[0][2][3] = $term_out[0];    
        $gg_weather[0][2][4] = $term_out[1];
        if($gg_weather[0][2][2]==""){$gg_weather[0][2][2]=$gg_weather[0][2][4];}
        if($imagefolder_check=="WeatherCom"){    //Image for actual
          $gg_weather[0][2][5] = $imageloc.$imagefolder."93/".$gg_weather[0][2][3].'.png';}
        else{
          $gg_weather[0][2][5] = $imageloc.$imagefolder.$gg_weather[0][2][3].'.png';}
        for($i=1;$i<=count($gg_weather)-1;$i++){
          $term_out=GG_funx_translate_weather_code_into_icon($gg_weather[$i][2][0],"day");
          $gg_weather[$i][2][3] = $term_out[0];
          $gg_weather[$i][2][4] = $term_out[1]; 
          if($imagefolder_check=="WeatherCom"){  //Images for forecast
              $gg_weather[$i][2][5] = $imageloc.$imagefolder."61/".$gg_weather[$i][2][3].'.png';
              $gg_weather[$i][2][6] = $imageloc.$imagefolder."31/".$gg_weather[$i][2][3].'.png';}
          else{
              $gg_weather[$i][2][5] = $imageloc.$imagefolder.$gg_weather[$i][2][3].'.png';
              $gg_weather[$i][2][6] = $imageloc.$imagefolder.$gg_weather[$i][2][3].'.png';}
        
          }
        }
        //print_r($gg_weather);
        return $gg_weather;
}

function GG_funx_get_sun_moon_text($gg_weather,$opt_language,$opt_language_index,$opt_auto_location_select,$time_corr,$args){
      $now = time();    
      $sr_hour=$gg_weather[0][19][5];
      $sr_minute=$gg_weather[0][19][6];
      $sunrise = mktime($sr_hour,$sr_minute,0,strftime("%m",$now),strftime("%d",$now),strftime("%y",$now));     
      if (str_replace("-","",$time_corr) != $time_corr)
      {
        $time_corr_sign="neg";
        $time_corr_flag=-1;
        $time_corr_alt= str_replace("-","",$time_corr);
      }
      else
      {
        $time_corr_sign="pos";
        $time_corr_flag=1;
        $time_corr_alt=$time_corr;
      }
      $check=strpos($time_corr_alt,'.');
      $time_corr_alt = str_replace(".","",$time_corr_alt);
      if ($check===false){
        $time_corr_hrs=$time_corr_alt;
        $time_corr_min=0;
      }
      else
      {
        $time_corr_hrs = substr($time_corr_alt,0,$check);
        $time_corr_min = round((substr($time_corr_alt,$check,strlen($time_corr_alt)-$check))*60/pow(10,strlen($time_corr_alt)-$check),2);
      }      
      $ss_hour=$gg_weather[0][19][3];
			$ss_hour=$ss_hour;
			$ss_minute=$gg_weather[0][19][4];
      $sunset = mktime($ss_hour,$ss_minute,0,strftime("%m",$now),strftime("%d",$now),strftime("%y",$now));
			$daylight_time=date("H:i",$sunset-$sunrise);
      $daylight_left=date("H:i",$sunset-$now-mktime($time_corr_hrs*$time_corr_flag,$time_corr_min*$time_corr_flag,0,0,0,0));
      $flag_day_night="night";
      $now_min=60*(int)substr(date("H:i",$now),0,2)+$time_corr_hrs*60+$time_corr_min+(int)substr(date("H:i",$now),3,2);
      $ss_min=$ss_hour*60+$ss_minute;
      $sr_min=$sr_hour*60+$sr_minute;
      if($now_min>=$sr_min and $now_min<=$ss_min){$flag_day_night="day";}   
      $night_left=date("H:i",$sunrise-$now-mktime($time_corr_hrs*$time_corr_flag,$time_corr_min*$time_corr_flag,0,0,0,0));
      $sunset =date("H:i",$sunset);
      $sunrise =date("H:i",$sunrise);
      $now =date("H:i",$now);
      if($gg_weather[0][19][3]==""){$flag_day_night="unknown";}
      if($gg_weather[0][19][3]<>""){
        $term_out=GG_funx_translate_array("Sunrise at",$opt_language)." ".$sunrise." ".GG_funx_translate_array("hrs",$opt_language)." ";
        if($opt_auto_location_select<>"checked" and !$args['flag']){
          if ($flag_day_night == "night"){
            $term_out=$term_out." (".GG_funx_translate_array("in",$opt_language)." ".$night_left."h) ";}
        }
        $term_out=$term_out." - ".GG_funx_translate_array("Sunset at",$opt_language)." ".$sunset." ".GG_funx_translate_array("hrs",$opt_language); 
        if($opt_auto_location_select<>"checked" and !$args['flag']){
          if ($flag_day_night == "day"){$term_out=$term_out." (".GG_funx_translate_array("in",$opt_language)." ".$daylight_left."h) ";}
        }
        $term_out=$term_out." - ".GG_funx_translate_array("Length of day",$opt_language).": ".$daylight_time."h - ";
      }
       if($gg_weather[0][19][2]<>""){
        $term_out=$term_out.GG_funx_translate_array("Moonphase",$opt_language).": ".GG_funx_translate_moonphase($gg_weather[0][19][2],$opt_language_index);
      }
      return array($term_out,$flag_day_night);     
}

function GG_funx_initialize_GG_arrays()

{
        $wun_parsed=array();
        $wun_help_a=array();
        $fwo_parsed=array();
        $fwo_help_a=array();
        $wun_parsed['current_observation']="";
        $wun_parsed['moon_phase']="";
        $wun_help_a['display_location']['full']="";
        $wun_help_a['observation_time']="";
        $wun_help_a['icon_url']="";
        $wun_help_a['condition']="";
        $wun_help_a['temp_f']=""; 
        $wun_help_a['temp_c']="";
        $wun_help_a['windchill_f']="";
        $wun_help_a['windchill_c']="";
        $wun_help_a['relative_humidity']="";
        $wun_help_a['wind_dir']="";
        $wun_help_a['wind_mph']="";
        $wun_help_a['wind_gusts_mph']="";
        $wun_help_a['pressure_mb']="";  
        $wun_help_a['pressure_in']="";   
        $wun_help_a['pressure_trend']="";  
        $wun_help_a['dewpoint_f']="";    
        $wun_help_a['dewpoint_c']="";   
        $wun_help_a['visibility_mi']=""; 
        $wun_help_a['visibility_km']="";
        $wun_help_a['pop']="";           
        $wun_help_a['precip_today_inch']="";           
        $wun_help_a['precip_today_metric']="";  
        $wun_help_a['percentIlluminated']="";
        $wun_help_a['ageOfMoon']="";
        $wun_help_a['sunset']="";
        $wun_help_a['sunset']['hour']="";
        $wun_help_a['sunset']['minute']="";
        $wun_help_a['sunrise']['hour']="";
        $wun_help_a['sunrise']['minute']="";
        $wun_parsed['forecast']="";
        $wun_parsed['forecast']['simpleforecast']="";
        $wun_parsed['forecast']['simpleforecast']['forecastday']="";
        $wun_parsed['forecast']['simpleforecast']['forecastday']['0']="";
        $wun_help_a['date']="";
        $wun_help_a['date']['month']="";
        $wun_help_a['date']['day']="";
        $wun_help_a['date']['weekday']="";
        $wun_help_a['high']="";   
        $wun_help_a['high']['fahrenheit']="";
        $wun_help_a['high']['celsius']="";
        $wun_help_a['low']="";
        $wun_help_a['low']['fahrenheit']="";
        $wun_help_a['low']['celsius']="";
        $wun_help_a['pop']="";
        $wun_help_a['precip_today_inch']="";          
        $wun_help_a['precip_today_metric']=""; 
        $wun_parsed['forecast']['txt_forecast']="";
        $wun_parsed['forecast']['txt_forecast']['forecastday']=""; 
        $wun_parsed['forecast']['txt_forecast']['forecastday'][1]="";
        $wun_help_a['fcttext']="";
        $wun_help_a['title']="";
        $wun_help_a['fcttext_metric']="";
        $fwo_parsed['data']="";
        $fwo_parsed['data']['request']="";
        $fwo_help_a['0']="";
        $fwo_help_a['0']['query']="";
        $fwo_parsed['data']['current_condition']="";
        $fwo_help_a['0']['observation_time']="";
        $fwo_help_a['0']['weatherCode']="";
        $fwo_help_a['0']['weatherIconUrl']="";
        $fwo_help_a['0']['weatherIconUrl']['0']="";
        $fwo_help_a['0']['weatherIconUrl']['0']['value']="";
        $fwo_help_a['0']['weatherDesc']="";
        $fwo_help_a['0']['weatherDesc']['0']="";
        $fwo_help_a['0']['weatherDesc']['0']['value']="";
        $fwo_help_a['0']['temp_F']="";
        $fwo_help_a['0']['temp_C']="";
        $fwo_help_a['0']['temp_F']="";
        $fwo_help_a['0']['temp_C']=""; 
        $fwo_help_a['0']['humidity']="";
        $fwo_help_a['0']['winddir16Point']="";
        $fwo_help_a['0']['winddirDegree']="";
        $fwo_help_a['0']['windspeedMiles']="";
        $fwo_help_a['0']['windspeedKmph']="";
        $fwo_help_a['0']['pressure']="";
        $fwo_help_a['0']['visibility']="";
        $fwo_help_a['0']['precipMM']="";
        $fwo_help_a['0']['cloudcover']="";
        $fwo_parsed['data']['weather']="";
        $fwo_help_a['date']="";
        $fwo_help_a['weatherCode']="";
        $fwo_help_a['weatherIconUrl']="";
        $fwo_help_a['weatherIconUrl']['0']="";
        $fwo_help_a['weatherIconUrl']['0']['value']="";
        $fwo_help_a['weatherDesc']="";
        $fwo_help_a['weatherDesc']['0']="";
        $fwo_help_a['weatherDesc']['0']['value']="";
        $fwo_help_a['tempMaxF']="";
        $fwo_help_a['tempMaxC']="";
        $fwo_help_a['tempMinF']="";
        $fwo_help_a['tempMinC']="";
        $fwo_help_a['winddir16Point']="";   
        $fwo_help_a['winddirDegree']="";   
        $fwo_help_a['windspeedMiles']="";    
        $fwo_help_a['windspeedKmph']="";
        $fwo_help_a['precipMM']="";
        return array($wun_help_a,$wun_parsed,$fwo_help_a,$fwo_parsed);
}

function GG_funx_initialize_GG_weather()
{
              $gg_weather[0][0][0]="";   //Date_mon_weekday
              $gg_weather[0][0][1]="";//month'];}
              $gg_weather[0][0][2]=""; //day'];}
              $gg_weather[0][0][3]=""; //weekday'];} 
              $gg_weather[0][1][0]=""; //Location
              $gg_weather[0][1][1]=""; //observation_time'];  //Ort und Land
              $gg_weather[0][2][0]=""; //icon'];      //zB chancerain!!!!  CODE
              $gg_weather[0][2][1]=""; //'icon_url'];
              $gg_weather[0][2][2]=""; //'condition'];}    //"Chance of rain" // "Rain Showers"
              $gg_weather[0][2][3]="";    //GG_weather_icon
              $gg_weather[0][2][4]="";    //GG_weather_icon_text_precode
              $gg_weather[0][2][5]="";    //GG_weather_icon_url _ Large/Middle 
              $gg_weather[0][2][6]="";    //GG_weather_icon_url _ SmALL     
              $gg_weather[0][3][0]="";        //txt_day_forecast_imp
              $gg_weather[0][3][1]="";        //txt_forecast_metric
              $gg_weather[0][4][0]="";        //txt_night_forecast_imp
              $gg_weather[0][4][1]="";        //txt_forecast_metric
              $gg_weather[0][5][0]=""; //temp_f'];} //aktuelle Temperatur
              $gg_weather[0][5][1]=""; //temp_c'];}   //aktuelle Temperatur
              $gg_weather[0][6][0]=""; //windchill_f'];}    //Windchill
              $gg_weather[0][6][1]=""; //windchill_c'];}
              $gg_weather[0][7][0]="";    //hi f
              $gg_weather[0][7][1]="";    //hi c
              $gg_weather[0][8][0]="";    //lo f
              $gg_weather[0][8][1]="";    //lo c
              $gg_weather[0][10]=""; //'relative_humidity'];}}
              $gg_weather[0][11][0]=""; //'wind_dir'];}    //Text??? West statt W???
              $gg_weather[0][11][1]="";    //Kurz   WNW for example
              $gg_weather[0][11][2]="";   //Degrees
              $gg_weather[0][11][3]="";   //speed mph
              $gg_weather[0][11][4]="";  //speed kmh
              $gg_weather[0][11][5]="";  //Gusts  mph
              $gg_weather[0][11][6]=""; //Gusts  kmh
              $gg_weather[0][12][1]=""; //Pressure_MB
              $gg_weather[0][12][0]="";   //Presssure_IN
              $gg_weather[0][13]="";    //Presssure_trend
              $gg_weather[0][14][0]="";   //dewpoint f
              $gg_weather[0][14][1]="";   //dewpoint c
              $gg_weather[0][15][0]=""; //visibility
              $gg_weather[0][15][1]="";//visibility_km'];}
              $gg_weather[0][16]="";//pop'];}           //probABILITY of precipitation 
              $gg_weather[0][17][0]="";        //amount of precipitation 
              $gg_weather[0][17][1]=""; //precip_today_metric'];}    
              $gg_weather[0][18]="";           //Cloadcover
              $gg_weather[0][19][0]=""; //percentIlluminated'];}
              $gg_weather[0][19][1]=""; //'ageOfMoon'];}
              $gg_weather[0][19][2]=""; //Moonphase GG_funx_calculate_moonphase($gg_weather[0][19][1]);}
              $gg_weather[0][19][3]=""; //'sunset']['hour'];}
              $gg_weather[0][19][4]=""; //'sunset']['minute'];}
              $gg_weather[0][19][5]=""; //'sunrise']['hour']; }
              $gg_weather[0][19][6]=""; //'sunrise']['minute']; }
              $gg_weather[0][19][7]="";        
              $gg_weather[0][99][0]="";  //error wun
              $gg_weather[0][99][1]="";  //error fwo
              $gg_weather[0][99][2]="";  //reserved
              $gg_weather[0][99][3]="";  //error result from above
              
          return $gg_weather;
}

function GG_funx_translate_moonphase($term_in,$opt_language_index){
	  //updated 24-04
    $moonphase_lang = array(
                      
                      'First Quarter'=>array('Halbmond (erstes Viertel)','Premier quartier','Cuarto Creciente','Primo quarto','pierwsza kwadra','f&eacute;lhold','Quarto Crescente','نصف القمر (الربع الأول)','F&oslash;rste kvarter','An ch&eacute;ad cheathr&uacute;','Eerste kwartier','halvm&aring;ne - f&oslash;rste kvartal','&#1055;&#1077;&#1088;&#1074;&#1072;&#1103; &#1095;&#1077;&#1090;&#1074;&#1077;&#1088;&#1090;&#1100;','Prvi kvartal','上弦月'),
                      'Full'=>array('Vollmond','Pleine lune','Luna Llena','Luna piena','pe&#322;nia','telihold','Lua cheia','بدر كامل','Fuldm&aring;ne','Gealach l&aacute;n','Volle maan','fullm&aring;ne','&#1055;&#1086;&#1083;&#1085;&#1086;&#1083;&#1091;&#1085;&#1080;&#1077;','Pun','滿'),
                      'Last Quarter'=>array('Abnehmend nach Halbmond (viertes Viertel)','Dernier Croissant','Luna Menguante','Luna calante','malej&#261;cy sierp','cs&ouml;kken&#337; f&eacute;lhold','Nova','تراجع القمر (الربع الأخير)','Sidste kvarter','An cheathr&uacute; dheiridh','Laatste kwartier','avtagende halvm&aring;ne - fjerde kvartal','&#1055;&#1086;&#1089;&#1083;&#1077;&#1076;&#1085;&#1103;&#1103; &#1095;&#1077;&#1090;&#1074;&#1077;&#1088;&#1090;&#1100;','Poslednji kvartal','下弦月'),
                      'New'=>array('Neumond','Nouvelle Lune','Luna Nueva','Luna nuova','n&oacute;w','&uacute;jhold','Lua Nova','هلال','Nym&aring;ne','Gealach nua','Nieuwe maan','nym&aring;ne','&#1053;&#1086;&#1074;&#1086;&#1083;&#1091;&#1085;&#1080;&#1077;','Nov','新'),
                      'Third Quarter'=>array('Halbmond (drittes Viertel)','Dernier quartier','Cuarto Menguante','Ultimo quarto','ostatnia kwadra','cs&ouml;kken&#337; telihold','Quarto Minguante','نصف القمر (الربع الثالث)','Tredje kvarter','An tr&iacute;&uacute; ceathr&uacute;','Derde kwartier','halvm&aring;ne - tredje kvartal','&#1058;&#1088;&#1077;&#1090;&#1100;&#1103; &#1095;&#1077;&#1090;&#1074;&#1077;&#1088;&#1090;&#1100;','Tre&#263;i kvartal','下弦月'),
                      'Waning Crescent'=>array('Abnehmend nach Halbmond (viertes Viertel)','Dernier Croissant','Luna Menguante','Luna calante','malej&#261;cy sierp','cs&ouml;kken&#337; f&eacute;lhold','Nova','تراجع القمر (الربع الرابع)','Aftagende m&aring;ne (sidste kvarter)','Gealach ag dul ar gc&uacute;l (corr&aacute;n)','Verlagende laatste kwartier','avtagende halvm&aring;ne - fjerde kvartal','&#1059;&#1073;&#1099;&#1074;&#1072;&#1102;&#1097;&#1080;&#1081; &#1052;&#1077;&#1089;&#1103;&#1094;','Opadanje polumeseca','殘 月'),
                      'Waning Gibbous'=>array('Abnehmend nach Vollmond (drittes Viertel)','Lune gibbeuse','Luna Gibosa Menguante','Gibbosa calante','malej&#261;cy po pe&#322;ni','cs&ouml;kken&#337; telihold','Minguante','تناقص بعد اكتمال القمر (الربع الثالث)','Aftagende m&aring;ne (tredje kvarter)','Gealach scothl&aacute;n ag dul ar gc&uacute;l','Verlagende derde kwartier','minkende','&#1059;&#1073;&#1099;&#1074;&#1072;&#1102;&#1097;&#1072;&#1103; &#1051;&#1091;&#1085;&#1072;','Opadanje punog meseca','虧凸月'),
                      'Waxing Crescent'=>array('Zunehmend nach Neumond (erstes Viertel)','Premier Croissant','Luna Nueva Visible','Luna crescente','rosn&#261;cy sierp','n&ouml;vekv&#337; &uacute;jhold','Lua Nova','بعد نحو متزايد القمر الجديد (الربع الأول)','Tiltagende m&aring;ne (f&oslash;rste kvarter)','Gealach dheirceach (corr&aacute;n)','Verlagende derde kwartier','&oslash;kende halvm&aring;ne - f&oslash;rste kvartal','&#1056;&#1072;&#1089;&#1090;&#1091;&#1097;&#1080;&#1081; &#1052;&#1077;&#1089;&#1103;&#1094;','Rast polumeseca','眉 月'),
                      'Waxing Gibbous'=>array('Zunehmend nach Halbmond (zweites Viertel)','Lune gibbeuse','Luna Gibosa Crecientee','Gibbosa crescente','rosn&#261;cy przed pe&#322;ni&#261;','n&ouml;vekv&#337; f&eacute;lhold','Crescente','على نحو متزايد بعد نصف القمر (الربع الثاني)','Tiltagende m&aring;ne (andet kvarter)','Gealach dheirceach (scothl&aacute;n)','Verlagende eerste kwartier','&oslash;kende halvm&aring;ne - andre kvartal','&#1056;&#1072;&#1089;&#1090;&#1091;&#1097;&#1072;&#1103; &#1051;&#1091;&#1085;&#1072;','Rast punog meseca','盈凸月'),
                      
                      );
    if(!isset($moonphase_lang[$term_in][$opt_language_index]))
      {$term_out=$term_in;}
    else
      {$term_out=$moonphase_lang[$term_in][$opt_language_index];}
    return $term_out;
    
}

function GG_funx_translate_weather_code_into_icon($term_in,$flag_day_night){

   $step=0;
   if($flag_day_night=="night"){$step=1;}
   
   $decode_array= array('395'=> array('41','46','Moderate or heavy snow in area with thunder'),
                        '392'=> array('41','46','Patchy light snow in area with thunder'),
                        '389'=> array('38','47','Moderate or heavy rain in area with thunder'),
                        '386'=> array('37','47','Patchy light rain in area with thunder'),
                        '377'=> array('6','6','Moderate or heavy showers of ice pellets'),
                        '374'=> array('6','6','Light showers of ice pellets'),
                        '371'=> array('14','14','Moderate or heavy snow showers'),
                        '368'=> array('13','13','Light snow showers'),
                        '365'=> array('6','6','Moderate or heavy sleet showers'),
                        '362'=> array('6','6','Light sleet showers'),
                        '359'=> array('11','11','Torrential rain shower'),
                        '356'=> array('11','11','Moderate or heavy rain shower'),
                        '353'=> array('9','9','Light rain shower'),
                        '350'=> array('18','18','Ice pellets'),
                        '338'=> array('16','16','Heavy snow'),
                        '335'=> array('16','16','Patchy heavy snow'),
                        '332'=> array('14','14','Moderate snow'),
                        '329'=> array('14','14','Patchy moderate snow'),
                        '326'=> array('13','13','Light snow'),
                        '323'=> array('13','13','Patchy light snow'),
                        '320'=> array('18','18','Moderate or heavy sleet'),
                        '317'=> array('18','18','Light sleet'),
                        '314'=> array('8','8','Moderate or Heavy freezing rain'),
                        '311'=> array('8','8','Light freezing rain'),
                        '308'=> array('40','40','Heavy rain'),
                        '305'=> array('39','45','Heavy rain at times'),
                        '302'=> array('11','11','Moderate rain'),
                        '299'=> array('39','45','Moderate rain at times'),
                        '296'=> array('9','9','Light rain'),
                        '293'=> array('9','9','Patchy light rain'),
                        '284'=> array('10','10','Heavy freezing drizzle'),
                        '281'=> array('9','9','Freezing drizzle'),
                        '266'=> array('9','9','Light drizzle'),
                        '263'=> array('9','9','Patchy light drizzle'),
                        '260'=> array('20','20','Freezing fog'),
                        '248'=> array('20','20','Fog'),
                        '230'=> array('16','16','Blizzard'),
                        '227'=> array('15','15','Blowing snow'),
                        '200'=> array('38','47','Thundery outbreaks in nearby'),
                        '185'=> array('10','10','Patchy freezing drizzle nearby'),
                        '182'=> array('18','18','Patchy sleet nearby'),
                        '179'=> array('16','16','Patchy snow nearby'),
                        '176'=> array('40','49','Patchy rain nearby'),
                        '143'=> array('20','20','Mist'),
                        '122'=> array('26','26','Overcast'),
                        '119'=> array('28','27','Cloudy'),
                        '116'=> array('30','29','Partly Cloudy'),
                        '113'=> array('32','31','Clear/Sunny'),
                        'chanceflurries'=> array('41','46','Chance of Flurries'),
                        'chancerain'=> array('39','45','Chance of Rain'),
                        'chancesleet'=> array('39','45','Chance of Freezing Rain'),
                        'chancesleet'=> array('41','46','Chance of Sleet'),
                        'chancesnow'=> array('41','46','Chance of Snow'),
                        'chancetstorms'=> array('38','47','Chance of Thunderstorms'),
                        'chancetstorms'=> array('38','47','Chance of a Thunderstorm'),
                        'clear'=> array('32','31','Clear'),
                        'cloudy'=> array('26','26','Cloudy'),
                        'flurries'=> array('15','15','Flurries'),
                        'fog'=> array('20','20','Fog'),
                        'hazy'=> array('21','21','Haze'),
                        'mostlycloudy'=> array('28','27','Mostly Cloudy'),
                        'mostlysunny'=> array('34','33','Mostly Sunny'),
                        'partlycloudy'=> array('30','29','Partly Cloudy'),
                        'partlysunny'=> array('28','27','Partly Sunny'),
                        'sleet'=> array('5','5','Freezing Rain'),
                        'rain'=> array('11','11','Rain'),
                        'sleet'=> array('5','5','Sleet'),
                        'snow'=> array('16','16','Snow'),
                        'sunny'=> array('32','31','Sunny'),
                        'tstorms'=> array('4','4','Thunderstorms'),
                        'tstorms'=> array('4','4','Thunderstorm'),
                        'thunderstorms'=> array('4','4','Thunderstorms'),
                        'thunderstorm'=> array('4','4','Thunderstorm'),
                        'Thunderstorms'=> array('4','4','Thunderstorms'),
                        'Thunderstorm'=> array('4','4','Thunderstorm'),
                        'unknown'=> array('4','4','Unknown'),
                        'cloudy'=> array('26','26','Overcast'),
                        'scatteredclouds'=> array('30','29','Scattered Clouds'),
                        'overcast'=> array('26','26','Overcast'),
  
   );
     if(!isset( $decode_array[$term_in][$step]))
        {$term_out[0]=$term_in;}
     else
        {$term_out[0]= $decode_array[$term_in][$step];}
        
    if(!isset( $decode_array[$term_in][2]))
      {$term_out[1]=$term_in;}
    else
      {$term_out[1]= $decode_array[$term_in][2];}
     return $term_out; 
}

function GG_funx_translate_weekdays($term_in,$opt_language_index){
	  //updated 24-04
    $weekday_short_lang = array(
                      'Friday'=>array('Fre','Ven','Vie','Ven','pi&#261;','p&eacute;n','Sex','الج','fre','Ao','vri','fre','&#1087;&#1103;&#1090;','pet','星期五'),
                      'Monday'=>array('Mon','Lun','Lun','Lun','pon','h&eacute;t','Seg','الأ','man','Lu','maa','man','&#1087;&#1086;&#1085;','pon','星期一'),
                      'Sunday'=>array('Son','Dim','Dom','Dom','nie','vas','Dom','قلي','s&oslash;n','Do','zon','s&oslash;n','&#1074;&#1086;&#1089;','ned','星期日'),
                      'Thursday'=>array('Don','Jeu','Jue','Gio','czw','cs&uuml;','Qui','الإ','tor','D&eacute;','don','tor','&#1095;&#1077;&#1090;','&#269;et','星期四'),
                      'Tuesday'=>array('Die','Mar','Mar','Mar','wto','ked','Ter','الأ','tir','M&aacute;','din','tir','&#1074;&#1090;&#1086;','uto','星期二'),
                      'Wednesday'=>array('Mit','Mer','Mie','Mer','&#347;ro','sze','Qua','الث','ons','C&eacute;','woe','ons','&#1089;&#1088;&#1077;','sre','星期三'),
                      'Saturday'=>array('Sam','sam','s&aacute;b','sab','sob','szo','S&aacute;b','الس','l&oslash;r','Sa','zat','l&oslash;r','&#1089;&#1091;&#1073;','sub','星期六'),
                       );
    if(!isset($weekday_short_lang[$term_in][$opt_language_index]))
      {$term_out=$term_in;}
    else
      {$term_out=$weekday_short_lang[$term_in][$opt_language_index];}
    return $term_out;
    
}


function GG_funx_translate_wetter_lang($term_in,$opt_language_index){
           //updated 24-04
  $wetter_lang = array(
                       
                       '0'=>array('Gewitter','Orage','Tempestad','Tempesta','Burze','vihar','Tempestade','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweer','Lyn, torden og regn','&#1075;&#1088;&#1086;&#1079;&#1072;','Oluja','雷陣雨'),
                        '1'=>array('Gewitter','Orage','Tempestad','Tempesta','Gwa&#322;towne burze','vihar','Tempestade','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweer','Lyn, torden og regn','&#1075;&#1088;&#1086;&#1079;&#1072;','Oluja','雷陣雨'),
                        '2'=>array('Gewitter','Orage','Tempestad','Tempesta','Gwa&#322;towne burze','vihar','Tempestade','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweer','Lyn, torden og regn','&#1075;&#1088;&#1086;&#1079;&#1072;','Oluja','雷陣雨'),
                        '3'=>array('Gewitter','Orage','Tempestad','Tempesta','Burze','vihar','Tempestade','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweer','Lyn, torden og regn','&#1075;&#1088;&#1086;&#1079;&#1072;','Oluja','雷陣雨'),
                        '4'=>array('Gewitter','Orage','Tempestad','Tempesta','Burze','vihar','Tempestade','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweer','Lyn, torden og regn','&#1075;&#1088;&#1086;&#1079;&#1072;','Oluja','雷陣雨'),
                        '5'=>array('Schneeregen','Neige Fondue','Aguanieve','Nevischio','Deszcz ze &#347;niegiem','havaz&aacute;s','Granizo','مطر متجمد','Slud','Flichshneachta','Natte Sneeuw','Sludd','&#1076;&#1086;&#1078;&#1076;&#1100; &#1089;&#1086; &#1089;&#1085;&#1077;&#1075;&#1086;&#1084;','Susne&#382;ica','陰雨夾雪'),
                        '6'=>array('Regen und Hagel','Pluie &eacute; Gr&ecirc;le ','lluvia y granizada','Pioggia e Grandine','Deszcz i grad lub krupy &#347;nie&#380;ne','&oacute;nos es&#337;','Chuva forte','مطر وبرد','Regn og hagl','B&aacute;isteach agus clocha sneachta','Regen en hagel','Regn og hagel','&#1076;&#1086;&#1078;&#1076;&#1100; &#1089; &#1075;&#1088;&#1072;&#1076;&#1086;&#1084;','Ki&scaron;a i grad','陰雨夾帶冰雹'),
                        '7'=>array('Starker Schneeregen','Neige Fondue Forte','Aguanieve Fuerte','Nevischio violento','Obfite opady deszczu ze &#347;niegiem','havaz&aacute;s, id&#337;nk&eacute;nt &oacute;nos es&#337;','Neve Forte','برد ثقيل','St&aelig;rk slud','Flichshneachta trom','Veel natte sneeuw','hagl og sn&oslash;','&#1084;&#1086;&#1082;&#1088;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;','Magla, ledena ki&scaron;a!','陰雨夾帶雪和冰雹'),
                        '8'=>array('Leichter Regen, Glatteisgefahr!','Pluie l&eacute;g&ecirc;r, verglas!','Lluvia d&eacute;bil, hielo liso!','Pioggia debole, Gelicidio!','Marzn&#261;ca m&#380;awka, &#347;lisko!','szit&aacute;l&oacute; es&#337;','Garoa','الضباب والمخاطر الثلجية!','Let regn, risiko for isslag!','B&aacute;isteach &eacute;adrom','Lichte regen met kans op ijsel','Lett regn','&#1090;&#1091;&#1084;&#1072;&#1085;, &#1075;&#1086;&#1083;&#1086;&#1083;&#1077;&#1076;&#1083;&#1077;&#1076;','Blaga ki&scaron;a','陰雨(小)'),
                        '9'=>array('Leichter Regen','Pluie l&eacute;g&ecirc;r','Lluvia d&eacute;bil','Pioggia debole','M&#380;awka','sz&eacute;l','Chuva','مطر خفيف','Let regn','An-ghaofar','Lichte regen','Vind','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Vetrovito','有風'),
                        '10'=>array('Regen, Glatteisgefahr!','Pluie verglas!','Lluvia, hielo liso!','Pioggia, Gelicidio!','Marzn&#261;cy deszcz, go&#322;oled&#378;!','es&#337;','Chuva','مطر','Regn, risiko for isslag!','Gleadhradh b&aacute;ist&iacute;','Regen met kans op ijsel','Regn','&#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;!','Jaka ki&scaron;a','陰雨(大)'),
                        '11'=>array('Regen','Pluie','Lluvia','Pioggia','Umiarkowane opady deszczu','es&#337;','Chuva','مطر','Regn','B&aacute;isteach mheasartha','Regen','Regn','&#1076;&#1086;&#1078;&#1076;&#1100;','Ki&scaron;a','陰雨(中)'),
                        '12'=>array('St&auml;rkerer Regen','Pluie mod&eacute;r&eacute;e','Lluvia moderada','Pioggia moderata','Obfite opady deszczu','es&#337;','Chuva moderada','مطر معتدل','Kraftig regn','Gleadhradh b&aacute;ist&iacute;','Zware buien','Regn','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Jaka ki&scaron;a','陰雨(大)'),
                        '13'=>array('Leichter Schneefall','Neige l&eacute;g&ecirc;r','Nevada d&eacute;bil','Nevicata debole','Lekkie opady &#347;niegu','h&oacute;sz&aacute;lling&oacute;z&aacute;s','Neve Fraca','تصاقط الثلوج ضعيف','Let snefald','Sneachta &eacute;adrom','Lichte sneeuwval','Muligheter for sn&oslash;','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1089;&#1085;&#1077;&#1075;','Slab sneg','小雪有雲'),
                        '14'=>array('Schneefall','Chute de neige','Nevada','Nevicata','Opady &#347;niegu','enyhe havaz&aacute;s','Neve','تصاقط الثلوج','Snefald','Sneachta','Sneeuw','Lett sn&oslash;','&#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;','Sneg','中雪有雲'),
                        '15'=>array('Rauhreif','Givre','Cencellada blanca sin','Calaverna','Szron','havaz&aacute;s','Neve','صقيع','Sne og slud','Sioc','Rijp','Sn&oslash;','&#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100;','Mraz, inje','下雪有霧'),
                        '16'=>array('Starker Schneefall','Neige lourde','Nieve pesada','Nevicata violenta','Obfite opady &#347;niegu','h&oacute;vihar','Neve pesada','قوي','Kraftig snefald','Sneachta trom','Zware sneeuwbuien','Kraftig sn&oslash;fall','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;','Jak sneg','大雪有雲'),
                        '17'=>array('Gewitter','Orage','Tempestad','Tempesta','Burze','vihar','Tempestade','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweer','Lyn, torden og regn','&#1073;&#1091;&#1088;&#1103;','Oluja','雷陣雨'),
                        '18'=>array('Hagel','Gr&ecirc;le','Granizada','Grandine','Grad','&oacute;nos es&#337;','Dil&uacute;vio','عاصفة برد','Hagl','Clocha sneachta','Hagelbuien','Hagl','&#1075;&#1088;&#1072;&#1076;','Grad','下冰雹'),
                        '19'=>array('Smog','Smog','Smog','Smog','Py&#322;','szmog','Polui&ccedil;&atilde;o','ضباب ودخان','Smog','Toitcheo','Smog','Disig','&#1089;&#1084;&#1086;&#1075;','Smog','晴天有霧'),
                        '20'=>array('Nebel','Brouillard','Neblina','Nebbia','Mg&#322;y','enyhe felh&#337;','Sem neblina','ضباب','T&aring;ge','Ceo','Mist','Disig','&#1090;&#1091;&#1084;&#1072;&#1085;','Magla','有霧'),
                        '21'=>array('Dunst','Brume s&egrave;che','Parcialmente nebulado','Foschia','Zamglenia','k&ouml;d','Neblina','ضباب','Dis','R&oacute; samh','Nevel','Disig','&#1076;&#1099;&#1084;&#1082;&#1072;','Izmaglica','晴天有薄霧'),
                        '22'=>array('Smog','Smog','Smog','Smog','Dym','szmog','Polui&ccedil;&atilde;o','ضباب','Smog','Toitcheo','Smog','Disig','&#1089;&#1084;&#1086;&#1075;','Smog','晴天有霧'),
                        '23'=>array('Windig','Venteux','Ventoso','Ventoso','Wietrznie','enyhe sz&eacute;l','Ventos','عاصف','Bl&aelig;sende','Gaofar','Winderig','Vind','&#1074;&#1077;&#1090;&#1088;&#1077;&#1085;&#1085;&#1086;','Vetrovito','有風'),
                        '24'=>array('Windig','Venteux','Ventoso','Ventoso','Wietrznie','sz&eacute;l','Ventania','عاصف','Bl&aelig;sende','An-ghaofar','Winderig','Lett vind','&#1074;&#1077;&#1090;&#1088;&#1077;&#1085;&#1085;&#1086;','Vetrovito','有風'),
                        '25'=>array('n/a','n/a','n/a','n/a','brak danych','n/a','n/a','n/a','n/a','n/a','n.b','n/a','&#1085;/&#1076;','n/a','/'),
                        '26'=>array('Bedeckt','Couvert','Cubierto','Nuvolosita compatta','Zachmurzenie ca&#322;kowite','bor&uacute;s','Coberto','مغطى','Overskyet','Brat scamall','Bewolkt','Overskyet','&#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;','gusti oblaci',' 多雲'),
                        '27'=>array('Bew&ouml;lkt','Nuageux','Nublado','Nuvolosita diffuse','Pochmurno','&eacute;jszaka bor&uacute;s','Nublado','غائم','Skyet','Scamallach san o&iacute;che','Bewolkt','Overskyet','&#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;','Obla&#269;no','夜晚多雲'),
                        '28'=>array('Bew&ouml;lkt','Nuageux','Nublado','Nuvolosita diffuse','Pochmurno','nappal bor&uacute;s','Nublado','غائم','Skyet','Scamallach i rith an lae','Bewolkt','Overskyet','&#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;','Obla&#269;no','晴時多雲'),
                        '29'=>array('Teilweise Bew&ouml;lkt','Partiellement nuageux','Parcialmente nublado','Nubi sparse','Zachmurzenie umiarkowane','&eacute;jszaka felh&#337;k','Parcialmente nublado','جزئي','Halvskyet','Roinnt scamallach san o&iacute;che','Half bewolkt','Lettskyet p&aring; natten','&#1087;&#1077;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1072;&#1103; &#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;&#1089;&#1090;&#1100;','Delimi&#269;no obla&#269;no','多雲，時有月光'),
                        '30'=>array('Teilweise Bew&ouml;lkt','Partiellement nuageux','Parcialmente nublado','Nubi sparse','Zachmurzenie umiarkowane','nappal felh&#337;k','Parcialmente nublado','جزئي','Halvskyet','Roinnt scamallach i rith an lae','Half bewolkt','Lettskyet','&#1087;&#1077;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1072;&#1103; &#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;&#1089;&#1090;&#1100;','Delimi&#269;no obla&#269;no','多雲時晴'),
                        '31'=>array('Klar','Serein','Despejado','Sereno','Bezchmurne niebo','&Eacute;jjel','Claro','واضح','Klar','Sp&eacute;ir ghlan','Helder','Kart p&aring; natten','&#1103;&#1089;&#1085;&#1086;','Bez oblaka','夜晚'),
                        '32'=>array('Sonnig','Ensoleill&eacute;','Soleado','Sereno','S&#322;onecznie','der&#369;s','Ensolarado','مشمس','solrig','Grianmhar','Zonnig','Solskinn','&#1089;&#1086;&#1083;&#1085;&#1077;&#1095;&#1085;&#1086;','Sun&#269;ano','晴朗'),
                        '33'=>array('&Uuml;berwiegend klar','Pour la plupart serein','Mayormente despejado','Poco Nuvoloso','Zachmurzenie ma&#322;e','&eacute;jszaka enyhe felh&#337;k','Parcialmente nublado','غائم جزئي','Overvejende klar','Sp&eacute;ir ghlan den chuid is m&oacute; san o&iacute;che','Licht bewolkt','Lettskyet','&#1087;&#1088;&#1077;&#1080;&#1084;&#1091;&#1097;&#1077;&#1089;&#1090;&#1074;&#1077;&#1085;&#1085;&#1086; &#1103;&#1089;&#1085;&#1086;','Delimi&#269;no obla&#269;no','夜晚有雲'),
                        '34'=>array('Heiter','Serein','Mayormente soleado','Poco Nuvoloso','Zachmurzenie ma&#322;e','nappal enyhe felh&#337;k','Principalmente ensolarado','مشمس في الغالب','Sol og f&aring; skyer','Sp&eacute;ir ghlan den chuid is m&oacute; i rith an lae','Overwegend zonnig','Lettskyet','&#1089;&#1086;&#1083;&#1085;&#1077;&#1095;&#1085;&#1086;','Delimi&#269;no obla&#269;no','晴朗有雲'),
                        '35'=>array('Gewitter','Orage','Tempestad','Tempesta','Burze','vihar','Tempestade','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweer','Lyn, torden og regn','&#1073;&#1091;&#1088;&#1103;','Oluja','雷陣雨'),
                        '36'=>array('Sonnig','Ensoleill&eacute;','Soleado','Sereno','Upalnie','der&#369;s','Ensolarado','مشمس','Solrig','Grianmhar','Zonnig','Solskinn','&#1103;&#1089;&#1085;&#1086;','Sun&#269;ano','晴朗'),
                        '37'=>array('Gewitterneigung','Probabilit&eacute; d&acute;Orage','Riesgo de Tempesta','Tendeza di Temporale','Lokalne burze','v&aacute;ltoz&oacute; (t&ouml;bbnyire es&#337;s)','Probabilidade de tempestade','عواصف رعدية','Tordenvejr med mulighed for sol','Baol ceathanna toirn&iacute;','Kans op onweer','Regn, men perioder med sol','&#1075;&#1088;&#1086;&#1079;&#1099;','Sun&#269;ano uz pljuskove i oluju','晴時多雲有雷陣雨'),
                        '38'=>array('Gewitterneigung','Probabilit&eacute; d&acute;Orage','Riesgo de Tempesta','Tendeza di Temporale','Rozproszone burze','v&aacute;ltoz&oacute; (t&ouml;bbnyire es&#337;s)','Probabilidade de tempestade','عواصف رعدية','Tordenvejr med mulighed for sol','Baol ceathanna toirn&iacute;','Kans op onweer','Regn, men perioder med sol','&#1075;&#1088;&#1086;&#1079;&#1099;','Sun&#269;ano uz pljuskove i oluju','晴時多雲有雷陣雨'),
                        '39'=>array('Sonnig mit Schauerneigung','Ensoleill&eacute; - probabilit&eacute d&acute;averse; ','Soleado con probabilidades de lluvia','Pioggia e Schiarite','Przelotne opady deszczu','v&aacute;ltoz&oacute; (t&ouml;bbnyire napos)','C&eacute;u nublado com chuva prov&aacute;vel','غائم مع هطول أمطار على الأرجح','Sol med spredte skyer','Grianmhar agus roinnt ceathanna b&aacute;ist&iacute;','Zonnig, af en toe buien','Lettere regn og noe sol','&#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;, &#1074;&#1086;&#1079;&#1084;&#1086;&#1078;&#1077;&#1085; &#1076;&#1086;&#1078;&#1076;&#1100;','Sun&#269;ano uz mogu&#263;e pljuskove','晴時多雲偶陣雨'),
                        '40'=>array('Starker Regen','Pluie forte','lluvia Fuerte','Pioggia violenta','Mocne opady deszczu','es&#337;','Chuva forte','مطر غزير','Kraftig regn','Gleadhradh b&aacute;ist&iacute;','Stevige buien','Kraftig regn','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Jaka ki&scaron;a','大雨'),
                        '41'=>array('Sonnig mit Schauerneigung','Ensoleill&eacute; - probabilit&eacute d&acute;averse; ','Soleado con probabilidades de lluvia','Pioggia e Schiarite','Przelotne opady &#347;niegu','h&oacute;sz&aacute;lling&oacute;z&aacute;s','C&eacute;u nublado com chuva prov&aacute;vel','غائم مع هطول أمطار على الأرجح','Sol med spredte skyer','Grianmhar agus roinnt ceathanna sneachta','Zon met stevige buien','Som, men noen regnbyger','&#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;, &#1074;&#1086;&#1079;&#1084;&#1086;&#1078;&#1077;&#1085; &#1089;&#1085;&#1077;&#1075;','Sun&#269;ano uz mogu&#263; sneg','晴天多雲有雪'),
                        '42'=>array('Starker Schneefall','Neige lourde','Nieve pesada','Nevicata violenta','Obfite opady &#347;niegu','havaz&aacute;s','Neve pesada','ثلوج غزيرة','Kraftig snefald','Sneachta trom','Zware sneeuwval','Kraftig sn&oslash;fall','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;','Jak sneg','大雪有雲'),
                        '43'=>array('Schneefall bei starkem Wind','Neige et Vent','Nieve y ventoso','Nevicata e Vento','Zawieje i zamiecie &#347;nie&#380;ne','h&oacute;vihar','Neve e vento','ثلوج ورياح','Snefald og kraftig vind','Sneachta agus &eacute; gaofar','Sneeuw met veel wind','Sn&oslash; og kraftig vind','&#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076; &#1087;&#1088;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1086;&#1084; &#1074;&#1077;&#1090;&#1088;&#1077;','Vetrovito uz sneg','多雲下雪有風'),
                        '44'=>array('n/a','n/a','n/a','n/a','brak danych','n/a','n/a','n/a','n/a','n/a','n.b','n/a','&#1085;/&#1076;','n/a',' /'),
                        '45'=>array('Regenschauer in der Nacht','Averse la nuit','Chubasco de noche','Rovescio della notte','Nocne opady deszczu','&eacute;jszaka es&#337;','Chuva &agrave; noite','زخات مطر في الليل','Regnbyger i l&oslash;bet af natten','Ceathanna b&aacute;ist&iacute; san o&iacute;che','Vannacht regen','Enkelte regnbyer p&aring; natten','&#1076;&#1086;&#1078;&#1076;&#1100; &#1085;&#1086;&#1095;&#1100;&#1102;','Ki&scaron;a no&#263;u','夜晚多雲有雨'),
                        '46'=>array('Schneeschauer in der Nacht','Averse de neige la nuit','Nevada de noche','Breve e intensa nevicata della notte','Nocne opady &#347;niegu','&eacute;jszaka havaz&aacute;s','Nevada &agrave; noite','زخات ثلوج في الليل','Snebyger i l&oslash;bet af natten','Ceathanna sneachta san o&iacute;che','Vannacht natte sneeuw','Enkelte sn&oslash;byger p&aring; natten','&#1089;&#1085;&#1077;&#1075; &#1085;&#1086;&#1095;&#1100;&#1102;','Sneg no&#263;u','夜晚多雲有雪'),
                        '47'=>array('N&auml;chtliche Gewitter','Orage en nuit','Tempestad de noche','Tempesta della notte','Nocne burze','&eacute;jszaka vihar','Tempestade da noite','ليلة عاصفة','Tordenvejr i l&oslash;bet af natten','Ceathanna toirn&iacute; san o&iacute;che','Vannacht onweer','Regn p&aring; natten','&#1075;&#1088;&#1086;&#1079;&#1072; &#1085;&#1086;&#1095;&#1100;&#1102;','Oluje no&#263;u','夜間雷陣雨'),
                        
                                                 
                       );                       
                       if(!isset($wetter_lang[$term_in][$opt_language_index]))
                        {$term_out=$term_in;}
                      else
                        {$term_out=$wetter_lang[$term_in][$opt_language_index];}
                      //echo "IN:".$term_in."OUT:".$term_out;  
                      return $term_out;
}

function GG_funx_translate_weather_detail_lang($term_in,$opt_language_index){
           //updated 25-04
  $weather_detail_lang = array(
                       
                        'Blowing Sand'=>array('Treibender Sand','vent de sable','','','Zamiecie piaskowe','homokf&uacute;v&aacute;s','','رياح رمل','Sandfygning','Gaineamh &aacute; sh&eacute;ideadh','Zandstorm','sandstorm','&#1087;&#1077;&#1089;&#1095;&#1072;&#1085;&#1099;&#1077; &#1073;&#1091;&#1088;&#1080;','Nanosi peska','吹沙'),
                        'Blowing Snow'=>array('Schneetreiben','vent de neige','','','Zamiecie &#347;nie&#380;ne','h&oacute;f&uacute;v&aacute;s','','هبوب عاصفة ثلجية','Snefygning','Sneachta &aacute; sh&eacute;ideadh','Sneeuwstorm','sn&oslash;storm','&#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;','Sne&#382;ni nanosi','吹雪'),
                        'Blowing Widespread Dust'=>array('Treibender Dunst','vent de poussi&egrave;re','','','Zamiecie py&#322;owe','sz&aacute;ll&oacute; por','','هبوب عاصفة ترابية','St&oslash;vfygning','Deannach &aacute; sh&eacute;ideadh go fairsing','Wind met opstuivend zand','drivende dis','&#1079;&#1072;&#1087;&#1099;&#1083;&#1077;&#1085;&#1085;&#1086;&#1089;&#1090;&#1100;','Nanosi pra&scaron;ine','吹沙塵'),
                        'Drizzle'=>array('Nieselregen','bruine','','','M&#380;awka','szit&aacute;l&oacute; es&#337;','','رذاذ','St&oslash;vregn','Br&aacute;d&aacute;n','Miezer','yr','&#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100;','rominjati','毛毛雨'),
                        'Dust Whirls'=>array('Staub','rafales de poussi&egrave;re','','','Wiry py&#322;owe','porforgatag','','زوبعة غبار','St&oslash;v','Guairne&aacute;in deannaigh','opstuivend zand','st&oslash;vvirvler','&#1087;&#1099;&#1083;&#1077;&#1074;&#1099;&#1077; &#1074;&#1080;&#1093;&#1088;&#1080;','kovitlaci pra&scaron;ine','捲沙塵'),
                        'Freezing Drizzle'=>array('&Uuml;berfrierender Nieselregen','brume givrante','','','Marzn&#261;ca m&#380;awka','j&eacute;gszit&aacute;l&aacute;s','','رذاذ متجمد','Frysende st&oslash;vregn','Br&aacute;d&aacute;n seaca','Bevroren miezer regen','underkj&oslash;lt yr','&#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100;','Ledeno rominjanje','凍毛雨'),
                        'Freezing Fog'=>array('&Uuml;berfrierender Nebel','brouillard givrant','','','Marzn&#261;ce mg&#322;y','jeges k&ouml;d','','ضباب متجمد','Frysende t&aring;ge','Ceo seaca','Aanvriezende mist','underkj&oslash;lt t&aring;ke','&#1084;&#1086;&#1088;&#1086;&#1079;&#1085;&#1099;&#1081; &#1090;&#1091;&#1084;&#1072;&#1085;','Ledena magla','冰霧'),
                        'Freezing Rain'=>array('&Uuml;berfrierender Regen','pluie glac&eacute;e','','','Marzn&#261;cy deszcz','fagyos es&#337;','','أمطار متجمدة','Frysende regn','B&aacute;isteach sheaca','Ijsel','underkj&oslash;lt regn','&#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Ledena ki&scaron;a','冰雨'),
                        'Hail Showers'=>array('Hagelschauer','giboul&eacute;es de gr&ecirc;le','','','Przelotny grad','z&aacute;por j&eacute;ges&#337;vel','','زخات برد','Haglbyger','Ceathanna clocha sneachta','Hagelbuien','haglbyger','&#1075;&#1088;&#1072;&#1076;','Pljuskovi sa gradom','冰雹陣'),
                        'Heavy Blowing Sand'=>array('Starker treibender Sand','vent de sable violent','','','Silne zamiecie piaskowe','heves homokf&uacute;v&aacute;s','','رياح ثقيله من الرمال','Kraftig sandfygning','Gaineamh &aacute; sh&eacute;ideadh le teannadh','Zware zandstorm','Sterk sandstorm','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1087;&#1077;&#1089;&#1095;&#1072;&#1085;&#1099;&#1077; &#1073;&#1091;&#1088;&#1080;','Jaki nanosi peska','吹強沙'),
                        'Heavy Blowing Snow'=>array('Starkes Schneetreiben','temp&ecirc;te de neige','','','Silne zamiecie &#347;nie&#380;ne','heves h&oacute;f&uacute;v&aacute;s','','رياح ثقيلة من الثلوج','Kraftig snefygning','Sneachta &aacute; sh&eacute;ideadh le teannadh','Zware sneeuwstorm','sterk sn&oslash;storm','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;','Jaki nanosi snega','吹強雪'),
                        'Heavy Blowing Widespread Dust'=>array('Starker treibender Dunst','Atmosph&egrave;re tr&egrave;s poussiereuse','','','Silne zamiecie py&#322;owe','hevesen sz&aacute;ll&oacute; kiterjedt por','','رياح عاصفة ترابية ثقيلة','Kraftig st&oslash;vfygning','Deannach &aacute; sh&eacute;ideadh go fairsing le teannadh','Zware stofstorm','drivende dis - sterk vind','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1079;&#1072;&#1087;&#1099;&#1083;&#1077;&#1085;&#1085;&#1086;&#1089;&#1090;&#1100;','Jaki nanosi pra&scaron;ine','吹強沙塵'),
                        'Heavy Drizzle'=>array('Starker Nieselregen','bruine &eacute;paisse','','','Mocna m&#380;awka','hevesen szit&aacute;l&oacute; es&#337;','','رذاذ كثيف','Finregn','Ceobh&aacute;isteach','Hevige miezer','tett yr','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100;','Jako rominjanje','綿密的毛毛雨'),
                        'Heavy Dust Whirls'=>array('Stark Staub','fortes rafales de poussi&egrave;re','','','Silne wiry py&#322;owe','heves porforgatag','','زوبعة ترابية ثقيلة','T&aelig;t st&oslash;v','Guairne&aacute;in deannaigh agus teannadh leo','Zware stofwervels','st&oslash;vvirvler - sterk vind','&#1087;&#1099;&#1083;&#1077;&#1074;&#1099;&#1077; &#1073;&#1091;&#1088;&#1080;','Veliki kovitlaci pra&scaron;ine','強捲沙塵'),
                        'Heavy Fog'=>array('Starker Nebel','brouillard dense','','','G&#281;ste mg&#322;y','er&#337;s k&ouml;d','','ضباب كثيف','T&aelig;t t&aring;ge','Ceo dl&uacute;th','Hevige mist','tung t&aring;ke','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1090;&#1091;&#1084;&#1072;&#1085;','Gusta magla','濃霧'),
                        'Heavy Freezing Drizzle'=>array('Starker &uuml;berfrierender Nieselregen','brume givrante &eacute;paisse','','','Silna marzn&#261;ca m&#380;awka','er&#337;s j&eacute;gszit&aacute;l&aacute;s','','رذاذ متجمد كثيف','Rimfrost / regn','Br&aacute;d&aacute;n seaca dl&uacute;th','Hevig bevroren miezer','sterkt underkj&oslash;lt yr','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100; &#1089; &#1085;&#1072;&#1083;&#1077;&#1076;&#1100;&#1102;','Jaka ledena sitna ki&scaron;a','綿密的凍毛雨'),
                        'Heavy Freezing Fog'=>array('Starker &uuml;berfrierender Nebel','&eacute;pais brouillard givrant ','','','G&#281;ste marzn&#261;ce mg&#322;y','er&#337;s jeges k&ouml;d','','ضباب متجمد كثيف','Rimfrost','Ceo seaca dl&uacute;th','Hevig bevroren mist','sterkt underkj&oslash;lt t&aring;ke','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1084;&#1086;&#1088;&#1086;&#1079;&#1085;&#1099;&#1081; &#1090;&#1091;&#1084;&#1072;&#1085;','Gusta ledena magla','濃冰霧'),
                        'Heavy Freezing Rain'=>array('Starker &uuml;berfrierender Regen','forte pluies glac&eacute;es','','','Silny marzn&#261;cy deszcz','er&#337;s fagyos es&#337;','','امطار غزيرة متجمده','Kraftig slud','B&aacute;isteach sheaca dhl&uacute;th','Hevig bevroren regen','tungt underkj&oslash;lt regn','&#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1083;&#1080;&#1074;&#1077;&#1085;&#1100;','Jaka ledena ki&scaron;a','強烈冰雨'),
                        'Heavy Hail'=>array('Kr&auml;ftiger Hagel','forte gr&ecirc;le','','','Silny grad','heves j&eacute;ges&#337;','','برد ثقيل','Kraftig hagl','Clocha sneachta agus teannadh leo','Zware hagelbuien','kraftig hagl','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1075;&#1088;&#1072;&#1076;','Jak grad','強烈冰雹'),
                        'Heavy Hail Showers'=>array('Kr&auml;ftige Hagelschauer','fortes giboul&eacute;es de gr&ecirc;le','','','Silny przelotny grad','er&#337;s z&aacute;por j&eacute;ges&#337;vel','','برد من زخات المطر الغزيرة','Kraftige haglbyger','Ceathanna clocha sneachta agus teannadh leo','Zware regenbuien','kraftige haglbyger','&#1083;&#1080;&#1074;&#1085;&#1080; &#1089; &#1075;&#1088;&#1072;&#1076;&#1086;&#1084;','Jaka oluja sa gradom','強烈冰雹陣'),
                        'Heavy Haze'=>array('Kr&auml;ftiger Hagel','brume &eacute;paisse','','','Silne zamglenia','er&#337;s p&aacute;ra','','ضباب كثيف','T&aelig;t dis','R&oacute; samh dl&uacute;th','Zware hagelbuien','tett dis','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1076;&#1099;&#1084;&#1082;&#1072;','Jaka izmaglica','濃霧'),
                        'Heavy Ice Crystals'=>array('Kr&auml;ftiger Eisregen','forte averses de gr&ecirc;lons','','','Silne opady marzn&#261;cego deszczu','er&#337;s j&eacute;gkrist&aacute;ly','','بلورات الثلج الثقيلة','Sv&aelig;re iskrystaller','Criostail throma oighir','Zware hagelbuien','tunge iskrystaller','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1083;&#1077;&#1076;&#1103;&#1085;&#1072;&#1103; &#1082;&#1088;&#1086;&#1096;&#1082;&#1072;','Jaka ledena ki&scaron;a','強冰晶'),
                        'Heavy Ice Pellet Showers'=>array('Kr&auml;ftige Eisregenschauer','Fortes averses de gr&ecirc;le','','','Silne przelotne opady marzn&#261;cego deszczu','heves j&eacute;gdaravihar','','زخات مطر مثلجه','Kraftige iskornbyger','Ceathanna mill&iacute;n&iacute; troma oighir','Zware hagelbuien','kraftige byger med isregn','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1083;&#1077;&#1076;&#1103;&#1085;&#1099;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080;','Jake padavine grada','強霰陣'),
                        'Heavy Ice Pellets'=>array('Kr&auml;ftiger Eisregen','Forte gr&ecirc;le','','','Silne opady marzn&#261;cego deszczu','er&#337;s j&eacute;gdara','','كرات ثلجية ثقيلة','Sv&aelig;re iskorn','Mill&iacute;n&iacute; troma oighir','Zware hagelbuien','kraftig isregn','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1083;&#1077;&#1076;&#1103;&#1085;&#1072;&#1103; &#1082;&#1088;&#1091;&#1087;&#1072;','Jak grad','強霰'),
                        'Heavy Low Drifting Sand'=>array('Kr&auml;ftiger niedrig treibender Sand','fortes rafales de vent','','','Silne niskie zamiecie piaskowe','heves, alacsonyan sz&aacute;ll&oacute; homokf&uacute;v&aacute;s','','رمال منجرفه خفيفه الثقل','Kraftig lav sandfygning','Gaineamh trom &iacute;seal &aacute; charnadh','Hevig stuifzand','tungt sanddrev','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1080; &#1089; &#1087;&#1077;&#1089;&#1082;&#1086;&#1084;','Jaki niski nanosi peska','強度高低吹沙'),
                        'Heavy Low Drifting Snow'=>array('Kr&auml;ftiger niedrig treibender Schnee','Fortes bourrasques de neige','','','Silne niskie zamiecie &#347;nie&#380;ne','heves alacsonyan sz&aacute;ll&oacute; h&oacute;f&uacute;v&aacute;s','','ثلوج منجرفه خفيفه الثقل','Kraftig lav snefygning','Sneachta trom &iacute;seal &aacute; charnadh','Hevige stuifsneeuw','kraftig sn&oslash;drev','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1089;&#1085;&#1077;&#1078;&#1085;&#1099;&#1077; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1080;','Jaki niski nanosi snega','強度高低吹雪'),
                        'Heavy Low Drifting Widespread Dust'=>array('Kr&auml;ftiger niedrig treibender Staub','Fortes bourrasques de poussi&egrave;re','','','Silne niskie zamiecie py&#322;owe','heves alacsonyan sz&aacute;ll&oacute; kiterjedt por','','غبار منجرف واسع النطاق ','Kraftig lav st&oslash;vfygning','Deannach fairsing trom &iacute;seal &aacute; charnadh','Hevige opstuivend stof','kraftig st&oslash;vdriv','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1080; &#1089; &#1087;&#1099;&#1083;&#1100;&#1102;','Jaki niski nanosi pra&scaron;ine','強度高低吹塵'),
                        'Heavy Mist'=>array('Kr&auml;ftiger Dunst','Brouillard &eacute;pais','','','G&#281;ste mg&#322;y i zamglenia','er&#337;s k&ouml;d','','ضباب كثيف','Kraftig t&aring;gedis','Ceobhr&aacute;n dl&uacute;th','Zware mist','kraftig t&aring;ke','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1090;&#1091;&#1084;&#1072;&#1085;','Gusta magla','重靄'),
                        'Heavy Rain'=>array('Starker Regen','Pluie violente','','','Ulewy','heves zivatar','','مطر غزير','Kraftig regn','Gleadhradh b&aacute;ist&iacute;','Hevige regen','kraftig regn','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Jaka ki&scaron;a','大雨'),
                        'Heavy Rain Mist'=>array('Starker Regen und Dunst','Pluie violente &eacute; brouillard &eacute;pais','','','G&#281;ste mg&#322;y i zamglenia z deszczem','er&#337;s k&ouml;dszit&aacute;l&aacute;s','','ضباب كثيف ممطر','Kraftig regndis','Ceobhr&aacute;n dl&uacute;th b&aacute;ist&iacute;','Hevige regen met mist','kraftig regn og t&aring;ke','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100; &#1089; &#1090;&#1091;&#1084;&#1072;&#1085;&#1086;&#1084;','Jaka ki&scaron;a sa maglom','大雨含霧氣'),
                        'Heavy Rain Showers'=>array('Kr&auml;ftige Regenschauer','Fortes averses','','','Silne przelotne opady deszczu','heves vihar','','تساقط أمطار غزيرة','Kraftige regnbyger','Ceathanna troma b&aacute;ist&iacute;','Hevige regenbuien','sterke regnbyger','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1083;&#1080;&#1074;&#1085;&#1080;','Jaki pljuskovi','強陣雨'),
                        'Heavy Sand'=>array('Starker Sand','sable dense','','','Silne wichury piaskowe','heves homokf&uacute;v&aacute;s','','رمال ثقيلة','Kraftig sand','Gaineamh trom','Zwaar zand','kraftig sanddrev','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1087;&#1077;&#1089;&#1086;&#1082;','Jaki pesak','強沙'),
                        'Heavy Sandstorm'=>array('Starker Sandsturm','Forte temp&ecirc;te de sable','','','Silne burze piaskowe','heves homokvihar','','عاصفة رمليه ثقيلة','Kraftig sandstorm','Stoirm throm ghainimh','Hevige zandstorm','kraftig sandstorm','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1087;&#1077;&#1089;&#1095;&#1072;&#1085;&#1072;&#1103; &#1073;&#1091;&#1088;&#1103;','Jaka pe&scaron;&#269;ana oluja','強沙塵暴'),
                        'Heavy Small Hail Showers'=>array('Kr&auml;ftige Hagelschauer','courtes et violentes averses de gr&ecirc;le ','','','Silne przelotne opady drobnego gradu','heves j&eacute;gdaraes&#337;','','زخات مطر غزيرة وبرد','Kraftige haglbyger','Ceathanna troma de chlocha beaga sneachta','Zware korte hagelbuien','kraftige, sm&aring; haglbyger','&#1074;&#1085;&#1077;&#1079;&#1072;&#1087;&#1085;&#1099;&#1077; &#1083;&#1080;&#1074;&#1085;&#1080;','Jaka oluja sa gradom','強烈小型冰雹陣'),
                        'Heavy Smoke'=>array('Starker Rauch','Fum&eacute;es &eacute;paisses','','','G&#281;sty dym','er&#337;s f&uuml;st','','دخان كثيف','St&aelig;rk r&oslash;g','Deatach dl&uacute;th','Dichte rook','tett r&oslash;yk','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; c&#1084;&#1086;&#1075;','Jak dim','濃煙'),
                        'Heavy Snow'=>array('Starker Schnee','Neige dense','','','Silne opady &#347;niegu','er&#337;s h&oacute;','','ثلوج كثيفه','Kraftig sne','Sneachta trom','Flink pak sneeuw','kraftig sn&oslash;v&aelig;r','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;','Jak sneg','大雪'),
                        'Heavy Snow Grains'=>array('Starker Schneefall','Fortes chutes de neige gel&eacute;e','','','Silne opady drobnych granulek &#347;niegowych','er&#337;s h&oacute;dara','','حبيبات ثلوج كثيفة','Kraftig snefald','Cal&oacute;ga troma sneachta','Zware sneeuwvlokken','kraftig sn&oslash;fall','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1089;&#1085;&#1077;&#1078;&#1085;&#1072;&#1103; &#1082;&#1088;&#1091;&#1087;&#1072;','Jak ledeni sneg','粗雪'),
                        'Heavy Snow Showers'=>array('Starker Schneeschauer','Fortes averses de neige','','','Silne przelotne opady &#347;niegu','heves h&oacute;vihar','','تساقط ثلوج غزيرة','Kraftige snebyger','Ceathanna troma sneachta','Zware sneeuwbuien','tunge sn&oslash;byger','c&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076; ','Jake padavine snega','大雪陣'),
                        'Heavy Spray'=>array('Kr&auml;ftiger Spr&uuml;hregen','Embruns &eacute;pais','','','Silna przelotna m&#380;awka','er&#337;s permet','','رذاذ كثيف','Kraftig spray','C&aacute;itheadh m&oacute;r','Zeer zware hoosbuien','tett spr&oslash;ytet&aring;ke','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1084;&#1086;&#1088;&#1086;&#1089;&#1100;','Sna&#382;an sprej','大浪'),
                        'Heavy Thunderstorm'=>array('Starker Gewitter','Forte temp&ecirc;te ','','','Silne burze','heves vihar','','هطول أمطار غزيرة','Kraftigt tordenvejr','Stoirm thoirn&iacute; throm','Zware onweer storm','kraftig tordenbyger','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099;','Jaka grmljavina','強大雷雨'),
                        'Heavy Thunderstorms and Ice Pellets'=>array('Starke Gewitter mit Eisregen','Forte temp&ecirc;te et chute de neige gel&eacute;e','','','Silne burze z marzn&#261;cym deszczem','heves vihar &eacute;s j&eacute;gdara','','عواصف رعدية شديدة وكريات ثلوج','Kraftigt tordenvejr med iskorn','Stoirmeacha toirn&iacute; troma agus mill&iacute;n&iacute; oighir','Zware onweer storm met hagel','kraftig torden og isregn','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1089; &#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1082;&#1088;&#1091;&#1087;&#1086;&#1081;','Jaka grmljavina sa gradom','強大雷雨含冰珠'),
                        'Heavy Thunderstorms and Rain'=>array('Starke Gewitter und Regen','Forte temp&ecirc;te, pluie et tonnerre','','','Silne burze i deszcze','heves vihar &eacute;s es&#337;','','عواصف رعدية وأمطار غزيرة','Kraftig tordenvejr og regn','Stoirmeacha toirn&iacute; troma agus b&aacute;isteach','Zware onweer storm met regen','kraftig torden og regn','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080; &#1089; &#1075;&#1088;&#1086;&#1079;&#1072;&#1084;&#1080;','Jaka grmljavina sa ki&scaron;om','強大雷雨'),
                        'Heavy Thunderstorms and Snow'=>array('Starke Gewitter und Schnee','Forte temp&ecirc;te, tonnerre et neige','','','Silne burze i &#347;nieg','heves vihar &eacute;s h&oacute;','','عواصف رعدية غزيرة وثلوج','Kraftig tordenvejr og sne','Stoirmeacha toirn&iacute; troma agus sneachta','Zware onweer storm met sneeuw','kraftig torden og sn&oslash;','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1089;&#1086; &#1089;&#1085;&#1077;&#1075;&#1086;&#1084;','Jaka grmljavina i sneg','強大雷雨含帶雪'),
                        'Heavy Thunderstorms with Hail'=>array('Starke Gewitter mit Hagel','Forte temp&ecirc;te et  gr&ecirc;lons','','','Silne burze z gradem','heves vihar &eacute;s j&eacute;ges&#337;','','عواصف رعدية شديدة مصحوبة ببرد','Kraftig tordenvejr med hagl','Stoirmeacha toirn&iacute; troma agus clocha sneachta','Zware onweer storm met hagel','kraftig torden og hagl','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1089; &#1075;&#1088;&#1072;&#1076;&#1086;&#1084;','Jaka grmljavina sa gradom','強大雷雨含冰雹'),
                        'Heavy Thunderstorms with Small Hail'=>array('Starke Gewitter mit kleinem Hagel','Forte temp&ecirc;te et gr&ecirc;le','','','Silne burze z drobnym gradem','heves vihar &eacute;s apr&oacute; j&eacute;ges&#337;','','عواصف رعدية شديدة مصحوبة ببرد صغير','Kraftig tordenvejr med sm&aring; hagl','Stoirmeacha toirn&iacute; troma agus clocha beaga sneachta','Zware onweer storm met fijne hagel','kraftig torden og sm&aring; hagl','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1089; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1084; &#1075;&#1088;&#1072;&#1076;&#1086;&#1084;','Jaka grmljavina sa slabim gradom','強大雷雨含小型冰雹'),
                        'Heavy Volcanic Ash'=>array('Starke Vulkanische Asche','Cendres volcaniques &eacute;paisses','','','G&#281;ste py&#322;y wulkaniczne','er&#337;s vulk&aacute;nhamu','','رماد بركاني كثيف','Kraftig vulkansk aske','Luaithreach bholc&aacute;nach dhl&uacute;th','Veel vulkanisch as','mye vulkansk aske','&#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1074;&#1091;&#1083;&#1082;&#1072;&#1085;&#1080;&#1095;&#1077;&#1089;&#1082;&#1080;&#1081; &#1087;&#1077;&#1087;&#1077;&#1083;','Jak vulkanski prah','濃火山塵'),
                        'Heavy Widespread Dust'=>array('Kr&auml;ftiger verbreiteter Staub','Poussi&egrave;re &eacute;paisse','','','G&#281;ste tumany py&#322;u','er&#337;sen kiterjedt por','','الغبار الثقيل على نطاق واسع ','Kraftig st&oslash;v','Deannach fairsing dl&uacute;th','Zwaar uitgewaaid stof','kraftig sandstorm','&#1089;&#1080;&#1083;&#1100;&#1085;&#1072;&#1103; &#1086;&#1073;&#1083;&#1086;&#1078;&#1085;&#1072;&#1103; &#1087;&#1099;&#1083;&#1100;','Jaka pra&scaron;ina','濃塵'),
                        'Ice Crystals'=>array('Eisregen','Gr&ecirc;lons','','','Opady marzn&#261;cego deszczu','j&eacute;gkrist&aacute;ly','','بلورات الجليد','Iskrystaller','Criostail oighir','Ijs kristallen','isregn','&#1083;&#1077;&#1076;&#1103;&#1085;&#1072;&#1103; &#1087;&#1099;&#1083;&#1100;','Ledena ki&scaron;a','冰晶'),
                        'Ice Pellet Showers'=>array('Eisregenschauer','Averses de pluie glac&eacute;e','','','Przelotne opady marzn&#261;cego deszczu','j&eacute;gdaravihar','','زخات مطر جليديه','Iskornbyger','Ceathanna mill&iacute;n&iacute; oighir','Hagelstenenbui','byger med isregn','&#1083;&#1077;&#1076;&#1103;&#1085;&#1099;&#1077; &#1083;&#1080;&#1074;&#1085;&#1080;','Pljuskovi sa gradom','霰陣'),
                        'Ice Pellets'=>array('Eisregen','Pluie glac&eacute;e','','','Opady marzn&#261;cego deszczu','j&eacute;gdara','','كريات جليدية','Iskorn','Mill&iacute;n&iacute; oighir','Hagelstenen','isregn','&#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Grad','霰'),
                        'Light Blowing Sand'=>array('Leichter wehender Sand','Petites rafales de sable','','','S&#322;abe zamiecie piaskowe','enyhe homokf&uacute;v&aacute;s','',' هبوب عاصفة رملية خفيفه','Let sandfygning','Gaineamh &eacute;adl&uacute;th &aacute; sh&eacute;ideadh','Licht stuifzand','lett sanddrev','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1087;&#1077;&#1089;&#1095;&#1072;&#1085;&#1099;&#1077; &#1073;&#1091;&#1088;&#1080;','Slabi nanosi peska','輕吹沙'),
                        'Light Blowing Snow'=>array('Leichtes Schneetreiben','Petites rafales de neige','','','S&#322;abe zamiecie &#347;nie&#380;ne','enyhe h&oacute;f&uacute;v&aacute;s','','هبوب ثلوج خفيفة','Let snefygning','Sneachta &eacute;adrom &aacute; sh&eacute;ideadh','Lichte stuifsneeuw','lett sn&oslash;drev','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1089;&#1085;&#1077;&#1078;&#1085;&#1099;&#1077; &#1073;&#1091;&#1088;&#1080;','Slabi nanosi snega','輕吹雪'),
                        'Light Blowing Widespread Dust'=>array('Leicht wehender Staub','Atmosph&egrave;re l&eacute;g&egrave;rement poussi&eacute;reuse','','','S&#322;abe zamiecie py&#322;owe','enyhe porf&uacute;v&aacute;s','','هبوب عاصفة ترابية خفيفة','Let st&oslash;vfygning','Deannach &eacute;adl&uacute;th &aacute; sh&eacute;ideadh go fairsing','Licht opstuivend stof','lett st&oslash;vdrev','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1072;&#1103; &#1079;&#1072;&#1087;&#1099;&#1083;&#1077;&#1085;&#1085;&#1086;&#1089;&#1090;&#1100;','Slabi nanosi pra&scaron;ine','輕吹沙塵'),
                        'Light Drizzle'=>array('Leichter Nieselregen','Bruine l&eacute;g&egrave;re','','','S&#322;aba m&#380;awka','enyhe szit&aacute;l&oacute; es&#337;','','رذاذ خفيف','Let st&oslash;vregn','Br&aacute;d&aacute;n &eacute;adl&uacute;th','Lichte miezerregen','lett duskregn','&#1083;&#1077;&#1075;&#1082;&#1072;&#1103; &#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100;','Slabo rominjanje','小雨'),
                        'Light Dust Whirls'=>array('Leichte Staubwinde','Vent de poussi&egrave;re l&eacute;ger','','','S&#322;abe wiry py&#322;owe','enyhe porforgatag','','زوبعة ترابية خفيفة','Let st&oslash;v','Guairne&aacute;in &eacute;adl&uacute;tha deannaigh','Lichte stofwolken','lette st&oslash;vvirvler','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1087;&#1099;&#1083;&#1077;&#1074;&#1099;&#1077; &#1073;&#1091;&#1088;&#1080;','Slabi kovitlaci pra&scaron;ine','輕型捲塵'),
                        'Light Fog'=>array('Leichter Nebel','Brouillard l&eacute;ger','','','Rzadkie mg&#322;y','enyhe k&ouml;d','','ضباب خفيف','Let t&aring;ge','Ceo &eacute;adl&uacute;th','Lichte mist','lett t&aring;ke','&#1083;&#1077;&#1075;&#1082;&#1080;&#1081; &#1090;&#1091;&#1084;&#1072;&#1085;','Slaba magla','薄霧'),
                        'Light Freezing Drizzle'=>array('Leichter &uuml;berfrierender Nieselregen','Bruine glac&eacute;e','','','S&#322;aba marzn&#261;ca m&#380;awka','enyhe j&eacute;gszit&aacute;l&aacute;s','','رذاذ متجمد خفيف','Let frysende st&oslash;vregn','Br&aacute;d&aacute;n seaca &eacute;adl&uacute;th','Licht aanvriezende nevel','lett underkj&oslash;lt duskregn','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1075;&#1086;&#1083;&#1086;&#1083;&#1077;&#1076;','Slaba ledena sitna ki&scaron;a','薄凍雨'),
                        'Light Freezing Fog'=>array('Leichter &uuml;berfrierender Nebel','L&eacute;ger brouillard givrant','','','Rzadkie marzn&#261;ce mg&#322;y','enyh&eacute;n fagyos k&ouml;d','','تجمد ضباب خفيف','Let frysende t&aring;ge','Ceo seaca &eacute;adl&uacute;th','Licht aanvriezende mist','lett underkj&oslash;lt t&aring;ke','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081;  &#1084;&#1086;&#1088;&#1086;&#1079;&#1085;&#1099;&#1081; &#1090;&#1091;&#1084;&#1072;&#1085;','Slaba ledena magla','薄冰霧'),
                        'Light Freezing Rain'=>array('Leichter &uuml;berfrierender Regen','Pluies glac&eacute;es mod&eacute;r&eacute;es','','','S&#322;aby marzn&#261;cy deszcz','enyh&eacute;n fagyos es&#337;','','أمطار متجمدة خفيفه','Let frysende regn','B&aacute;isteach sheaca &eacute;adrom','Licht aanvriezende regen','lett frosset regn','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Slaba ledena ki&scaron;a','薄凍雨'),
                        'Light Hail'=>array('Leichter Hagel','Gr&ecirc;le l&eacute;g&egrave;re','','','Niewielki grad','enyhe j&eacute;ges&#337;','','برد خفيف','Let hagl','Clocha sneachta &eacute;adroma','Lichte hagelbuien','lett hagl','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1075;&#1088;&#1072;&#1076;','Slab grad','小陣冰雹'),
                        'Light Hail Showers'=>array('Leichte Hagelschauer','L&eacute;g&egrave;res averses de gr&ecirc;le','','','Niewielki przelotny grad','enyhe z&aacute;por j&eacute;ges&#337;vel','','زخات برد ومطر خفيفة','Lette haglbyger','Ceathanna &eacute;adroma clocha sneachta','Lichte hagelbuien','lette haglbyger','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1083;&#1080;&#1074;&#1085;&#1080; &#1089; &#1075;&#1088;&#1072;&#1076;','Slab grad','小陣冰雹'),
                        'Light Haze'=>array('Leichter Dunst','Brume l&eacute;g&egrave;re','','','S&#322;abe zamglenia','enyhe p&aacute;ra','','ضباب خفيف','Let dis','R&oacute; samh &eacute;adl&uacute;th','Lichte smog','lett dis','&#1083;&#1077;&#1075;&#1082;&#1072;&#1103; &#1084;&#1075;&#1083;&#1072;','Slaba izmaglica','薄靄'),
                        'Light Ice Crystals'=>array('Leichter Eisregen','Cristaux de glace l&eacute;gers','','','S&#322;abe opady marzn&#261;cego deszczu','enyhe j&eacute;gkrist&aacute;ly','','بلورات الثلج الخفيفة','Lette iskrystaller','Criostail oighir &eacute;adroma','Lichte Ijsel','lette iskrystaller','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1072;&#1103; &#1083;&#1077;&#1076;&#1103;&#1085;&#1072;&#1103; &#1087;&#1099;&#1083;&#1100;','Slaba ledena ki&scaron;a','小冰晶'),
                        'Light Ice Pellet Showers'=>array('Leichte Eisregenschauer','L&eacute;g&egrave;res averses de glace','','','S&#322;abe przelotne opady marzn&#261;cego deszczu','enyhe z&aacute;por j&eacute;gdar&aacute;val','','ثلج وزخات مطر خفيفه','Lette iskornbyger','Ceathanna mill&iacute;n&iacute; oighir &eacute;adroma','Lichte hagelbuien','lette byger med isregn','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1083;&#1077;&#1076;&#1103;&#1085;&#1099;&#1077; &#1083;&#1080;&#1074;&#1085;&#1080;','Slabe padavine grada','小冰珠陣'),
                        'Light Ice Pellets'=>array('Leichter Eisregen','l&eacute;gers morceaux de glace','','','S&#322;abe opady marzn&#261;cego deszczu','enyhe j&eacute;gdara','','حبيبات الثلج الخفيفة','Lette iskorn','Mill&iacute;n&iacute; oighir &eacute;adroma','Lichte hagelbuien','lett isregn','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Slab grad','小冰珠'),
                        'Light Low Drifting Sand'=>array('Leichter niedrig wehender Sand','L&eacute;gers bancs de sable','','','S&#322;abe niskie zamiecie piaskowe','k&ouml;nny&#369;, alacsonyan sz&aacute;ll&oacute; homokf&uacute;v&aacute;s','','منخفض هبوب عاصفة رملية خفيفة','Let lav sandfygning','Gaineamh &eacute;adrom &iacute;seal &aacute; charnadh','Lichte laag opstuivend zand','lett sanddrev','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1080; &#1089; &#1087;&#1077;&#1089;&#1082;&#1086;&#1084;','Slabi niski nanosi peska','小低吹沙'),
                        'Light Low Drifting Snow'=>array('Leichter niedrig treibender Schnee','L&eacute;gers nuages de neige','','','S&#322;abe niskie zamiecie &#347;nie&#380;ne','k&ouml;nny&#369;, alacsonyan sz&aacute;ll&oacute; h&oacute;f&uacute;v&aacute;s','','منخفض انحراف ثلوج خفيفه','Let lav snefygning','Sneachta &eacute;adrom &iacute;seal &aacute; charnadh','Lichte laag opstuivend sneeuw','lett sn&oslash;drev','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1089;&#1085;&#1077;&#1078;&#1085;&#1099;&#1077; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1080;','Slabi niski nanosi snega','小低吹雪'),
                        'Light Low Drifting Widespread Dust'=>array('Leichter niedrig treibender Dunst','Nuages de poussi&egrave;re l&eacute;gers','','','S&#322;abe niskie zamiecie py&#322;owe','k&ouml;nny&#369;, alacsonyan sz&aacute;ll&oacute; kiterjedt por','','قليل من هبوب عاصفة ترابية خفيفة','Let lav st&oslash;vfygning','Deannach fairsing &eacute;adrom &iacute;seal &aacute; charnadh','Lichte laag opstuivend stof','lett st&oslash;vdrev','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1080; &#1089; &#1087;&#1099;&#1083;&#1100;&#1102;','Slabi niski nanosi pra&scaron;ine','小低吹塵'),
                        'Light Mist'=>array('Leichter Dunst','L&eacute;g&egrave;re brume','','','Niewielkie mg&#322;y i zamglenia','enyhe k&ouml;d','','ضباب خفيف','Let t&aring;gedis','Ceobhr&aacute;n &eacute;adl&uacute;th','Lichte nevel','lett t&aring;ke','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1090;&#1091;&#1084;&#1072;&#1085;','Slaba magla','薄霧'),
                        'Light Rain'=>array('Leichter Regen','Pluie l&eacute;g&egrave;re','','','S&#322;abe opady deszczu','enyhe es&#337;','','مطر خفيف','Let regn','B&aacute;isteach &eacute;adrom','Lichte regen','lett regn','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Slaba ki&scaron;a','小雨'),
                        'Light Rain Mist'=>array('Leichter Regen und Dunst','L&eacute;g&egrave;re brume pluvieuse','','','Niewielkie mg&#322;y i zamglenia z deszczem','enyhe k&ouml;dszit&aacute;l&aacute;s','','مطر خفيف','Let regndis','Ceobhr&aacute;n &eacute;adl&uacute;th b&aacute;ist&iacute;','Lichte regen met lichte nevel','lett regn og t&aring;ke','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100; &#1080; &#1090;&#1091;&#1084;&#1072;&#1085;','Slaba ki&scaron;a sa maglom','小雨帶薄霧'),
                        'Light Sand'=>array('Leichter Sand','Sable l&eacute;ger','','','Lekkie wichury piaskowe','enyhe homok','','رمال خفيفة','Let sandfygning','Gaineamh &eacute;adl&uacute;th','Licht zanderig','lett sanddrev','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1087;&#1077;&#1089;&#1086;&#1082;','Slab pesak','低沙'),
                        'Light Sandstorm'=>array('Leichter Sandsturm','L&eacute;g&egrave;re temp&ecirc;te de neige','','','Lekkie burze piaskowe','k&ouml;nny&#369; homokvihar','','عاصفة رمليه خفيفة','Let sandstorm','Stoirm ghainimh &eacute;adl&uacute;th','Lichte zandstorm','lett sandstorm','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1072;&#1103; &#1087;&#1077;&#1089;&#1095;&#1072;&#1085;&#1072;&#1103; &#1073;&#1091;&#1088;&#1103;','Slaba pe&scaron;&#269;ana oluja','小沙塵暴'),
                        'Light Small Hail Showers'=>array('Leichte Hagelschauer','L&eacute;g&egrave;res averses de gr&ecirc;le','','','S&#322;abe przelotne opady drobnego gradu','enyhe z&aacute;por apr&oacute; j&eacute;gdarabk&aacute;kkal','','زخات مطر خفيفة من البرد الصغير','Lette haglbyger','Ceathanna clocha beaga sneachta','Lichte lokale hagelbuien','lette haglbyger','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1083;&#1080;&#1074;&#1085;&#1080;','Slaba oluja sa gradom','小冰雹陣'),
                        'Light Smoke'=>array('Leichter Rauch','Fum&eacute;e l&eacute;g&egrave;re','','','Rzadki dym','enyhe f&uuml;st','','دخان خفيف','Let r&oslash;g','Deatach &eacute;adl&uacute;th','Lichte rook','lett r&oslash;yk','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1089;&#1084;&#1086;&#1075;','Slab dim','薄煙'),
                        'Light Snow'=>array('Leichter Schneefall','Neige l&eacute;g&egrave;re','','','S&#322;abe opady &#347;niegu','enyhe havaz&aacute;s','','ثلوج خفيفة','Let snefygning','Sneachta &eacute;adrom','Lichte sneeuw','lett sn&oslash;fall','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1089;&#1085;&#1077;&#1075;','Slab sneg','小雪'),
                        'Light Snow Grains'=>array('Leichter Schneefall','L&eacute;g&egrave;res chutes de neige givr&eacute;e','','','S&#322;abe opady drobnych granulek &#347;niegowych','enyhe h&oacute;dara','','كرات ثلوج خفيفة','Let snefald','Gr&aacute;inn&iacute; sneachta &eacute;adrom','Lichte sneeuwvlokken','lett sn&oslash;fall','&#1089;&#1085;&#1077;&#1078;&#1085;&#1072;&#1103; &#1082;&#1088;&#1091;&#1087;&#1082;&#1072;','Slab ledeni sneg','雪粒少'),
                        'Light Snow Showers'=>array('Leichte Schneeschauer','L&eacute;g&egrave;res chutes de neige','','','S&#322;abe przelotne opady &#347;niegu','enyhe h&oacute;z&aacute;por','','تساقط ثلوج خفيفة','Let snebyger','Ceathanna sneachta &eacute;adrom','Lichte sneeuwbuien','lette sn&oslash;byger','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;','Slabe padavine snega','小型雪陣'),
                        'Light Spray'=>array('Leichter Spr&uuml;hregen','Embruns l&eacute;gers','','','S&#322;aba przelotna m&#380;awka','enyhe permet','','رذاذ خفيف','Let spray','C&aacute;itheadh &eacute;adrom','Lichte hoosbuien','lett spr&oslash;ytet&aring;ke','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1072;&#1103; &#1084;&#1086;&#1088;&#1086;&#1089;&#1100;','Slab sprej','小浪'),
                        'Light Thunderstorm'=>array('Leichtes Gewitter','Quelques orages','','','Lekkie burze','enyhe vihar','','هطول أمطار خفيفة','Let tordenvejr','Stoirm thoirn&iacute; &eacute;adrom','Lichte onweersstorm','lett torden','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1072;&#1103; &#1075;&#1088;&#1086;&#1079;&#1072;','Slaba grmljavina','小型雷陣雨'),
                        'Light Thunderstorms and Ice Pellets'=>array('Leichtes Gewitter mit Eisregen','Quelques orages et chutes de glace','','','Lekkie burze z marzn&#261;cym deszczem','enyhe vihar &eacute;s j&eacute;gdara','','العواصف الرعدية وكريات الثلج الخفيفة','Let tordenvejr med iskorn','Stoirmeacha toirn&iacute; &eacute;adroma agus mill&iacute;n&iacute; oighir','Lichte onweersstorm met hagel','lett torden og isregn','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1080; &#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100; ','Slaba grmljavina sa gradom','小型雷陣雨含冰珠'),
                        'Light Thunderstorms and Rain'=>array('Leichtes Gewitter und Regen','Quelques orgaes, et pluie','','','Lekkie burze i deszcze','enyhe vihar &eacute;s es&#337;','','عواصف رعدية وأمطار خفيفة','Let tordenvejr og regn','Stoirmeacha toirn&iacute; &eacute;adroma agus b&aacute;isteach','Lichte onweersstorm met regen','lett torden og regn','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1080; &#1076;&#1086;&#1078;&#1076;&#1080; ','Slaba grmljavina sa ki&scaron;om','小型雷陣雨'),
                        'Light Thunderstorms and Snow'=>array('Leichter Gewitter und Schnee','Quelques orages et chute de neige','','','Lekkie burze i &#347;nieg','enyhe vihar &eacute;s havaz&aacute;s','','ضوء عواصف رعدية وثلج','Let tordenvejr og sne','Stoirmeacha toirn&iacute; &eacute;adroma agus sneachta','Lichte onweersstorm met sneeuw','lett torden og sn&oslash;','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1080; &#1089;&#1085;&#1077;&#1075;','Slaba grmljavina i sneg','小型雷陣雨含雪'),
                        'Light Thunderstorms with Hail'=>array('Leichtes Gewitter mit Hagel','Quelques orages et gr&ecirc;le','','','Lekkie burze z gradem','enyhe vihar j&eacute;ges&#337;vel','','عواصف رعدية خفيفة مع البرد','Let tordenvejr med hagl','Stoirmeacha toirn&iacute; &eacute;adroma agus clocha sneachta','Lichte onweersstorm met hagelbuien','lett torden og hagl','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1089; &#1075;&#1088;&#1072;&#1076;&#1086;&#1084;','Slaba grmljavina sa gradom','小型雷陣雨含冰雹'),
                        'Light Thunderstorms with Small Hail'=>array('Leichtes Gewitter mit Hagel','Quelques orages et gr&ecirc;le','','','Lekkie burze z drobnym gradem','enyhe vihar apr&oacute; j&eacute;gdarabk&aacute;kkal','','عواصف رعدية مصحوبة ببرد صغير وخفيفه','Let tordenvejr med sm&aring; hagl','Stoirmeacha toirn&iacute; &eacute;adroma agus clocha beaga sneachta','Lichte onweersstorm met lichte hagel','lett torden og hagl','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1075;&#1088;&#1086;&#1079;&#1099; &#1089; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1084; &#1075;&#1088;&#1072;&#1076;&#1086;&#1084;','Slaba grmljavina sa slabim gradom','小型雷陣雨含小冰雹'),
                        'Light Volcanic Ash'=>array('Leichter vulkanische Asche','','','','Rzadkie py&#322;y wulkaniczne','enyhe vulk&aacute;nhamu','','رماد بركاني خفيف','Let vulkansk aske','Luaithreach bholc&aacute;nach &eacute;adl&uacute;th','Lichte vulkaan as','lett vulkansk aske','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1074;&#1091;&#1083;&#1082;&#1072;&#1085;&#1080;&#1095;&#1077;&#1089;&#1082;&#1080;&#1081; &#1087;&#1077;&#1087;&#1077;&#1083;','Slab vulkanski prah','薄火山塵'),
                        'Light Widespread Dust'=>array('Leichter verbreiteter Dunst','','','','Rzadkie tumany py&#322;u','enyh&eacute;n kiterjedt por','','غبار خفيف على نطاق واسع','Let st&oslash;v','Deannach &eacute;adl&uacute;th fairsing','Licht verspreid stof','lett st&oslash;vdrev','&#1083;&#1077;&#1075;&#1082;&#1072;&#1103; &#1079;&#1072;&#1087;&#1099;&#1083;&#1077;&#1085;&#1085;&#1086;&#1089;&#1090;&#1100;','Slaba pra&scaron;ina','薄灰塵'),
                        'Low Drifting Sand'=>array('Leichter treibender Sand','','','','Niskie zamiecie piaskowe','alacsonyan sz&aacute;ll&oacute; homokf&uacute;v&aacute;s','','هبوب عاصفة رملية منخفضة','Lav sandfygning','Gaineamh &iacute;seal &aacute; charnadh','Laag stuivend zand','sanddrev','&#1087;&#1077;&#1089;&#1095;&#1072;&#1085;&#1072;&#1103; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1072;','Niski nanosi peska','低吹沙'),
                        'Low Drifting Snow'=>array('Leichter treibender Schnee','','','','Niskie zamiecie &#347;nie&#380;ne','alacsonyan sz&aacute;ll&oacute; h&oacute;f&uacute;v&aacute;s','','ثلوج منجرفة منخفضة','Lav snefygning','Sneachta &iacute;seal &aacute; charnadh','Laag stuivend sneeuw','sn&oslash;drev','&#1089;&#1085;&#1077;&#1078;&#1085;&#1072;&#1103; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1072;','Niski nanosi snega','低吹雪'),
                        'Low Drifting Widespread Dust'=>array('Leichter niedrig treibender Dunst','','','','Niskie zamiecie py&#322;owe','alacsonyan sz&aacute;ll&oacute;, kiterjedt porf&uacute;v&aacute;s','','انخفاض هبوب عاصفة ترابية','Lav st&oslash;vfygning','Deannach fairsing &iacute;seal &aacute; charnadh','Laag stuivend stof','st&oslash;vdrev','&#1087;&#1099;&#1083;&#1077;&#1074;&#1072;&#1103; &#1087;&#1086;&#1079;&#1077;&#1084;&#1082;&#1072;','Niski nanosi pra&scaron;ine','低吹塵'),
                        'Mist'=>array('Dunst','','','','Mg&#322;y i zamglenia','k&ouml;d','','ضباب','T&aring;gedis','Ceobhr&aacute;n','Mistig','t&aring;ke','&#1090;&#1091;&#1084;&#1072;&#1085;','Magla','靄'),
                        'Mostly Cloudy'=>array('&Uuml;berwiegend wolkig','','','','Du&#380;e zachmurzenie','t&ouml;bbnyire felh&#337;s','','غائم جزئيا','Skyet','Scamallach den chuid is m&oacute;','Overwegend bewolkt','for det meste skyet','&#1087;&#1088;&#1077;&#1080;&#1084;&#1091;&#1097;&#1077;&#1089;&#1090;&#1074;&#1077;&#1085;&#1085;&#1086; &#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;','Mestimi&#269;no obla&#269;no','多雲'),
                        'Overcast'=>array('Bedeckt','','','','Pochmurno','bor&uacute;s','','عتم','Overskyet','Sp&eacute;ir faoi dhuifean','Bewolkt','overskyet','&#1089;&#1087;&#1083;&#1086;&#1096;&#1085;&#1072;&#1103; &#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;&#1089;&#1090;&#1100;','Obla&#269;no','陰天'),
                        'Partly Cloudy'=>array('Teilweise bew&ouml;lkt','','','','Zachmurzenie umiarkowane','r&eacute;szben felh&#337;s','','غائم جزئيا','Halvskyet','Roinnt scamallach','Half bewolkt','delvis skyet','&#1087;&#1077;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1072;&#1103; &#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;&#1089;&#1090;&#1100;','Delimi&#269;no sun&#269;ano','部分有雲'),
                        'Rain Mist'=>array('Regen und Dunst','','','','Mg&#322;y i zamglenia z deszczem','k&ouml;dszit&aacute;l&aacute;s','','غائم','Regndis','Ceobhr&aacute;n b&aacute;ist&iacute;','Mist en regen','regn og t&aring;ke','&#1083;&#1077;&#1075;&#1082;&#1080;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100; &#1080; &#1090;&#1091;&#1084;&#1072;&#1085;','Ki&scaron;a sa maglom','雨霧'),
                        'Rain Showers'=>array('Regenschauer','','','','Przelotne opady deszczu','z&aacute;pores&#337;','','زخات مطر','Regnbyger','Ceathanna b&aacute;ist&iacute;','Buien','regnbyger','&#1083;&#1080;&#1074;&#1085;&#1080;','Pljuskovi','陣雨'),
                        'Sandstorm'=>array('Sandsturm','','','','Burze piaskowe','homokvihar','','عاصفة رملية','Sandstorm','Stoirm ghainimh','Zandstorm','sandstorm','&#1087;&#1077;&#1089;&#1095;&#1072;&#1085;&#1072;&#1103; &#1073;&#1091;&#1088;&#1103;','Pe&scaron;&#269;ana oluja','沙暴'),
                        'Scattered Clouds'=>array('Vereinzelte Wolkenfelder','','','','Rozproszone chmury','sz&oacute;rv&aacute;nyos felh&#337;zet','','غيوم متفرقة','Spredte skyer','Scamaill scaipthe','Verspreide bewolking','spredte skyer','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;','Prete&#382;no vedro','零星有雲'),
                        'Small Hail Showers'=>array('Hagelschauer','','','','Przelotny drobny grad','kis z&aacute;por j&eacute;ges&#337;vel','','زخات من البرد الصغير','Haglbyger','Ceathanna clocha beaga sneachta','Kleine hagelbuien','sm&aring; haglbyger','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080; &#1089; &#1075;&#1088;&#1072;&#1076;&#1086;&#1084;','Slabe padavine grada','小型冰雹'),
                        'Snow Grains'=>array('Schneefall','','','','Opady drobnych granulek &#347;niegowych','h&oacute;dara','','ثلوج وبلورات','Snefald','Gr&aacute;inn&iacute; sneachta','Sneeuw buien','sn&oslash;fall','&#1089;&#1085;&#1077;&#1078;&#1085;&#1072;&#1103; &#1082;&#1088;&#1091;&#1087;&#1072;','Ledeni sneg','雪粒'),
                        'Snow Showers'=>array('Schneeschauer','','','','Przelotne opady &#347;niegu','h&oacute;vihar','','تساقط ثلوج','Snebyger','Ceathanna sneachta','Sneeuw buien','sn&oslash;byger','&#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;','Sne&#382;ni pljuskovi','雪陣'),
                        'Thunderstorm'=>array('Gewitter','','','','Burza','vihar','','عاصفة رعدية','Tordenvejr','Stoirm thoirn&iacute;','Onweersbuien','torden','&#1075;&#1088;&#1086;&#1079;&#1099;','Grmljavina','暴風雨'),
                        'Thunderstorms and Ice Pellets'=>array('Gewitter mit Eisregen','','','','Burze z marzn&#261;cym deszczem','vihar &eacute;s j&eacute;gdara','','العواصف رعدية وكريات ثلج','Tordenvejr med iskorn','Stoirmeacha toirn&iacute; agus mill&iacute;n&iacute; oighir','Onweer met hagel','torden og isregn','&#1075;&#1088;&#1086;&#1079;&#1099; &#1080; &#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100; ','Grmljavina sa gradom','暴風雨夾霰'),
                        'Thunderstorms and Rain'=>array('Gewitter und Regen','','','','Burze i deszcze','vihar &eacute;s es&#337;','','عواصف رعدية وأمطار','Tordenvejr og regn','Stoirmeacha toirn&iacute; agus b&aacute;isteach','Onweer met regen','torden og regn','&#1076;&#1086;&#1078;&#1076;&#1080; &#1080; &#1075;&#1088;&#1086;&#1079;&#1099;','Grmljavina i ki&scaron;a','暴風雨'),
                        'Thunderstorms and Snow'=>array('Gewitter und Schneefall','','','','Burze i &#347;nieg','vihar &eacute;s h&oacute;','','عواصف رعدية وثلوج','Tordenvejr og sne','Stoirmeacha toirn&iacute; agus sneachta','Onweer met sneeuw','torden og sn&oslash;','&#1075;&#1088;&#1086;&#1079;&#1099; &#1080; &#1089;&#1085;&#1077;&#1075;','Grmljavina i sneg','暴風雨夾雪'),
                        'Thunderstorms with Hail'=>array('Gewitter mit Hagel','','','','Burze z gradem','vihar j&eacute;ges&#337;vel','','عواصف رعدية مصحوبة ببرد','Tordenvejr med hagl','Stoirmeacha toirn&iacute; agus clocha sneachta','Onweer met hagel','torden og hagl','&#1075;&#1088;&#1086;&#1079;&#1099; &#1080; &#1075;&#1088;&#1072;&#1076;','Grmljavina sa gradom','暴風雨夾冰雹'),
                        'Thunderstorms with Small Hail'=>array('Gewitter mit Hagel','','','','Burze z drobnym gradem','vihar apr&oacute; j&eacute;gdarabk&aacute;kkal','','عواصف رعدية مصحوبة ببرد صغير','Tordenvejr med hagl','Stoirmeacha toirn&iacute; agus clocha beaga sneachta','Onweer met lichte hagel','torden og hagl','&#1075;&#1088;&#1086;&#1079;&#1099; &#1080; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1075;&#1088;&#1072;&#1076;','Grmljavina sa blagim gradom','暴風雨夾小冰雹'),
                        'Unknown'=>array('Unbekannt','','','','Nieznane / Nieznany / Nieznana','ismeretlen','','غير معروف','Ukendt','N&iacute; fios','Onbekend','ukjent','&#1085;&#1077;&#1080;&#1079;&#1074;&#1077;&#1089;&#1090;&#1085;&#1086;','Nepoznato','未知'),
                        'Volcanic Ash'=>array('Vulkanische Asche','','','','Py&#322;y wulkaniczne','vulk&aacute;nhamu','','رماد بركاني','Vulkansk aske','Luaithreach bholc&aacute;nach','Vulkaan as','vulkansk aske','&#1074;&#1091;&#1083;&#1082;&#1072;&#1085;&#1080;&#1095;&#1077;&#1089;&#1082;&#1080;&#1081; &#1087;&#1077;&#1087;&#1077;&#1083;','Vulkanski prah','火山塵'),
                        'Clear/Sunny'=>array('Klar / Sonnig','','','','Bezchmurnie / S&#322;onecznie','tiszta','','واضح / مشمس','Klar / Solrig','Sp&eacute;ir ghlan / grianmhar','Onbewolkt/zonnig','klart/solskinn','&#1103;&#1089;&#1085;&#1086;/&#1089;&#1086;&#1083;&#1085;&#1077;&#1095;&#1085;&#1086;','Vedro/Sun&#269;ano','晴朗'),
                        'Heavy rain at times'=>array('Kr&auml;ftiger Regen bei Zeiten','','','','Rzadkie silne opady deszczu','id&#337;nk&eacute;nt heves es&#337;','','مطر غزير في بعض الأحيان','Kraftig regn til tider','Gleadhradh b&aacute;ist&iacute; scait&iacute;','Af en toe regen','tidvis mye regn','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077;  &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080;','Povremena jaka ki&scaron;a','時有大量陣雨'),
                        'Light rain shower'=>array('Leichte Regenschauer','','','','Niewielkie opady przelotne','enyhe z&aacute;pores&#337;','','ضوء مطر','Let regnbyge','Cith &eacute;adrom b&aacute;ist&iacute;','Lichte regen','lette regnbyger','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1081; &#1083;&#1080;&#1074;&#1077;&#1085;&#1100;','Blaga ki&scaron;a','少量陣雨'),
                        'Light showers of ice pellets'=>array('Leichte Eisregenschauer','','','','Niewielkie przelotne opady marzn&#261;cego deszczu','enyhe j&eacute;gdaraszit&aacute;l&aacute;s','','زخات مطر خفيفة من كريات الثلج','Lette iskonbyger','Ceathanna &eacute;adroma de mhill&iacute;n&iacute; oighir','Lichte regen met hagel','lette byger med isregn','&#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1077; &#1083;&#1077;&#1076;&#1103;&#1085;&#1099;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080;','Blaga susne&#382;ica','少量陣雨夾霰'),
                        'Light sleet'=>array('Leichter Graupel','','','','Niewielki deszcz ze &#347;niegiem','enyhe &oacute;nos es&#337;','','مطر متجمد منخفض','Let slud','Flichshneachta &eacute;adrom','Lichte natte sneeuw ','lett sludd','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1072;&#1103; &#1089;&#1083;&#1103;&#1082;&#1086;&#1090;&#1100;','Blaga susne&#382;ica','少雨夾雪'),
                        'Light sleet showers'=>array('Leichte Graupelschauer','','','','Niewielki przelotny deszcz ze &#347;niegiem','enyhe &oacute;nos es&#337;permet','','زخات مطر متجمده منخفضة','Lette sludbyger','Ceathanna &eacute;adroma flichshneachta','Lichte natte sneeuw ','lette sluddbyger','&#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080; &#1089;&#1086; &#1089;&#1085;&#1077;&#1075;&#1086;&#1084;','Blaga susne&#382;ica','少雨夾雪陣'),
                        'Moderate or Heavy freezing'=>array('M&auml;ssig bis kr&auml;ftiger Frost','','','','Umiarkowane lub silne mrozy','m&eacute;rs&eacute;kelt vagy er&#337;s fagy','','معتدل أو متجمد ثقيل','Moderat eller st&aelig;rk frost','Sioc measartha n&oacute; g&eacute;ar','Matige tot strenge vorst','moderat til sterk kulde','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1077; &#1080;&#1083;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1079;&#1072;&#1084;&#1086;&#1088;&#1086;&#1079;&#1082;&#1080;','Umeren do jak mraz','中到強度冰凍'),
                        'Moderate or heavy rain in area with thunder'=>array('M&auml;ssig bis kr&auml;ftiger Regen mit &ouml;rtlichen Gewittern','','','','Umiarkowane lub silne opady deszczu z lokalnymi burzami','m&eacute;rs&eacute;kelt vagy er&#337;s es&#337; mennyd&ouml;rg&eacute;ssel','','معتدل أو مطر غزير في المنطقة مع رعود','Moderat eller kraftig regn med torden','B&aacute;isteach mheasartha n&oacute; gleadhradh b&aacute;ist&iacute; sa gceantar agus toirneach','Matige tot zware regenbuien met onweer','moderat til kraftig regn i omr&aring;der med torden','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1077; &#1080;&#1083;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080; &#1089; &#1075;&#1088;&#1086;&#1079;&#1072;&#1084;&#1080;','Umerena do jaka ki&scaron;a sa grmljavinom','中到強度凍雨帶雷'),
                        'Moderate or heavy rain shower'=>array('M&auml;ssig bis kr&auml;ftige Regenschauer','','','','Umiarkowane lub silne przelotne opady deszczu','m&eacute;rs&eacute;kelt vagy z&aacute;por','','معتدل أو مطر غزير','Moderat eller kraftig regnbyge','Cith trom n&oacute; measartha b&aacute;ist&iacute;','Matige tot zware buien','moderate til kraftige regnbyger','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081; &#1080;&#1083;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Umerena do jaka ki&scaron;a','中到強度陣雨'),
                        'Moderate or heavy showers of ice pellets'=>array('M&auml;ssig bis kr&auml;ftige Eisregenschauer','','','','Umiarkowane lub silne przelotne opady marzn&#261;cego deszczu','m&eacute;rs&eacute;kelt vagy er&#337;s j&eacute;gz&aacute;por','','معتدل أو نزول كريات الثلج','Moderate eller kraftige iskornbyger','Ceathanna troma n&oacute; measartha mill&iacute;n&iacute; oighir','Matige tot zware regenbuien met hagel','moderate til kraftige byger med isregn','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1077; &#1080;&#1083;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1083;&#1077;&#1076;&#1103;&#1085;&#1099;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080;','Umeren do jak sneg','中到強度霰陣'),
                        'Moderate or heavy sleet showers'=>array('M&auml;ssig bis kr&auml;ftige Graupelschauer','','','','Umiarkowane lub silne przelotne opady deszczu ze &#347;niegiem','m&eacute;rs&eacute;kelt vagy er&#337;s &oacute;nos es&#337;','','زخات مطر متجمد معتدل أو الثقيلة','Moderate eller kraftige sludbyger','Ceathanna measartha n&oacute; troma clocha sneachta','Matige tot zware buien met natte sneeuw','moderate til kraftige sluddbyger','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1077; &#1080;&#1083;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080; &#1089;&#1086; &#1089;&#1085;&#1077;&#1075;&#1086;&#1084;','Umerena do jaka susne&#382;ica','中到強度陣雨夾雪'),
                        'Moderate or heavy snow in area with thunder'=>array('M&auml;ssig bis kr&auml;ftiger Schnee in Gebieten mit Gewitter','','','','Umiarkowane lub silne opady &#347;niegu z lokalnymi burzami','m&eacute;rs&eacute;kelt vagy er&#337;s havaz&aacute;s mennyd&ouml;rg&eacute;ssel','','معتدل أو ثلوج في المنطقة مع رعد','Moderat eller kraftig sne med torden','Sneachta measartha n&oacute; trom sa gceantar agus toirneach','Matige to zware sneeuwbuien met onweer','moderat til kraftig sn&oslash;fall i omr&aring;der med torden','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081; &#1080;&#1083;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075; &#1089; &#1075;&#1088;&#1086;&#1079;&#1086;&#1081;','Umeren do jak sneg sa grmljavinom','中到強度地區下雪夾帶打雷'),
                        'Moderate or heavy snow showers'=>array('M&auml;ssig bis kr&auml;ftige Schneeschauer','','','','Umiarkowane lub silne przelotne opady &#347;niegu','m&eacute;rs&eacute;kelt vagy er&#337;s h&oacute;z&aacute;por','','تساقط ثلوج متوسطة أو ثقيلة','Moderate eller kraftige snebyger','Ceathanna measartha n&oacute; troma sneachta','Matige tot zware sneeuwbuien','moderate til kraftige sn&oslash;byger','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1077; &#1080;&#1083;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1077; &#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;&#1099;','Umeren do jak sneg','中到強度陣雪'),
                        'Moderate rain'=>array('M&auml;ssiger Regen','','','','Umiarkowane opady deszczu','m&eacute;rs&eacute;kelt es&#337;','','مطر معتدل','Moderat regn','B&aacute;isteach mheasartha','Matige regen','moderat regn','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Umerena ki&scaron;a','中度雨勢'),
                        'Moderate rain at times'=>array('Zeitweise m&auml;ssiger Regen','','','','Rzadkie umiarkowane opady deszczu','id&#337;nk&eacute;nt m&eacute;rs&eacute;kelt es&#337;','','مطر معتدل في بعض الأحيان','Til tider moderat regn','B&aacute;isteach mheasartha scait&iacute;','Af en toe regen','til tider moderat regn','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081; &#1082;&#1088;&#1072;&#1090;&#1082;&#1086;&#1074;&#1088;&#1077;&#1084;&#1077;&#1085;&#1085;&#1099;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Umerena ki&scaron;a umerenog inteziteta','一陣陣中強度雨勢'),
                        'Moderate snow'=>array('M&auml;ssiger Schneefall','','','','Umiarkowane opady &#347;niegu','m&eacute;rs&eacute;kelt havaz&aacute;s','','ثلوج معتدلة','Moderat sne','Sneachta measartha','Matige sneeuwbuien','moderat sn&oslash;fall','&#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;','Umeren sneg','中強度雪'),
                        'Patchy freezing drizzle nearby'=>array('&Ouml;rtlich &uuml;berfrierender Nieselregen','','','','Niejednolita lokalna marzn&#261;ca m&#380;awka','foltokban helyi j&eacute;gszit&aacute;l&aacute;s','','رذاذ متجمد غير مكتمل قريبا','Pletvis frysende st&oslash;vregn i n&aelig;rheden','Paist&iacute; br&aacute;d&aacute;n seaca in aice l&aacute;imhe','Lokaal hier en daar glad','spredt duskregn i n&aelig;romr&aring;det','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100;','Povremeno ledeno rominjanje','附近部分地區下凍雨'),
                        'Patchy heavy snow'=>array('&Ouml;rtlich kr&auml;ftiger Schnee','','','','Niejednolite silne opady &#347;niegu','foltokban er&#337;s havaz&aacute;s','','ثلوج غزيرة غير مكتمله','Pletvis kraftig sne','Paist&iacute; sneachta trom','Lokaal zware sneeuwbuien','spredt, tungt sn&oslash;fall','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1089;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;','Povremen jak sneg','部分地區下大雪'),
                        'Patchy light drizzle'=>array('&Ouml;rtlich leichter Nieselregen','','','','Niejednolita s&#322;aba m&#380;awka','foltokban enyhe szit&aacute;l&oacute; es&#337;','','رذاذ خفيف غير مكتمل','Pletvis let st&oslash;vregn','Paist&iacute; br&aacute;d&aacute;in &eacute;adl&uacute;th','Lokaal lichte miezer','spredt duskregn','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1072;&#1103; &#1080;&#1079;&#1084;&#1086;&#1088;&#1086;&#1079;&#1100;','Povremeno blago rominjanje','部分地區下毛雨'),
                        'Patchy light rain'=>array('&Ouml;rtlich leichter Regen','','','','Niejednolite lekkie opady deszczu','foltokban enyhe es&#337;','','مطر خفيف غير مكتمل','Pletvis finregn','Paist&iacute; b&aacute;isteach &eacute;adrom','Lokaal lichte buien','spredt, lett regn','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080;','Povremena blaga ki&scaron;a','部分地區下小雨'),
                        'Patchy light rain in area with thunder'=>array('&Ouml;rtlich  leichter Regen in Gebieten mit Gewitter','','','','Niejednolite lekkie opady deszczu z lokalnymi burzami','foltokban enyhe es&#337; mennyd&ouml;rg&eacute;ssel','','مطر خفيف غير مكتمل في المنطقة مع رعد','Pletvis finregn med torden','Paist&iacute; b&aacute;isteach &eacute;adrom sa gceantar mar aon le toirneach','Hier en daar een bui met lokaal onweer','spredt lett regn i omr&aring;der med torden','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1080;&#1077; &#1076;&#1086;&#1078;&#1076;&#1080; &#1089; &#1075;&#1088;&#1086;&#1079;&#1072;&#1084;&#1080;','Povremena blaga ki&scaron;a sa grmljavinom','部分地區小雨夾帶打雷'),
                        'Patchy light snow'=>array('&Ouml;rtlich leichter Schneefall','Quelques chutes l&eacute;g&egrave;res de neige probables','','','Niejednolite lekkie opady &#347;niegu','foltokban enyhe havaz&aacute;s','','ثلوج خفيفة غير مكتمله','Pletvis let sne','Paist&iacute; sneachta &eacute;adrom','Lokaal licht sneeuw','spredt lett sn&oslash;','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1089;&#1085;&#1077;&#1075;','Povremen blag sneg','部分地區小雪'),
                        'Patchy light snow in area with thunder'=>array('&Ouml;rtlich leichter Schneefall','Episodes neigeux et de tonnerre probables','','','Niejednolite lekkie opady &#347;niegu z lokalnymi burzami','foltokban enyhe havaz&aacute;s mennyd&ouml;rg&eacute;ssel','','ثلوج خفيفة في منطقة غير مكتمله مع رعد','Pletvis let sne med torden','Paist&iacute; sneachta &eacute;adrom sa gceantar agus toirneach','Hier en daar sneeuw met lokaal onweer','spredt, lett sn&oslash;fall i omr&aring;der med torden','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1085;&#1077;&#1073;&#1086;&#1083;&#1100;&#1096;&#1086;&#1081; &#1089;&#1085;&#1077;&#1075; &#1089; &#1075;&#1088;&#1086;&#1079;&#1086;&#1081;','Povremen blag sneg sa grmljavinom','部分地區小雪夾帶打雷'),
                        'Patchy moderate snow'=>array('&Ouml;rtlich m&auml;ssiger Schneefall','Episodes de neige l&eacute;g&egrave;re probables','','','Niejednolite umiarkowane opady &#347;niegu','foltokban m&eacute;rs&eacute;kelt havaz&aacute;s','','ثلوج معتدله غير مكتمل','Pletvis sne','Paist&iacute; sneachta measartha','Lokaal matige sneeuw','spredt, moderat sn&oslash;fall','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1091;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;','Povremen umeren sneg','部分地區下中強度雪'),
                        'Patchy rain nearby'=>array('&Ouml;rtlich Regen','Averses probables','','','Niejednolite lokalne opady deszczu','foltokban helyi es&#337;','','مطر غير مكتمل قريباً','Pletvis regn i n&aelig;rheden','Paist&iacute; b&aacute;ist&iacute; in aice l&aacute;imhe','Lokaal een bui','spredt regn i n&aelig;romr&aring;det','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1076;&#1086;&#1078;&#1076;&#1100;','Povremena ki&scaron;a','附近部分地區下雨'),
                        'Patchy sleet nearby'=>array('&Ouml;rtlich Graupelschauer','Averses de neige fondue probable','','','Niejednolite lokalne opady deszczu ze &#347;niegiem','foltokban helyi &oacute;nos es&#337;','','مطر متجمد غير مكتمل في مكان قريب','Pletvis slud i n&aelig;rheden','Paist&iacute; flichshneachta in aice l&aacute;imhe','Lokaal nattesneeuw','spredt sludd i n&aelig;romr&aring;det','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1084;&#1086;&#1082;&#1088;&#1099;&#1081; &#1089;&#1085;&#1077;&#1075;','Povremena susne&#382;ica','附近部分地區下雪雨'),
                        'Patchy snow nearby'=>array('&Ouml;rtlich Schneefall','Episodes neigeux probables','','','Niejednolite lokalne opady &#347;niegu','foltokban helyi havaz&aacute;s','','ثلج غير مكتمل قريب','Pletvis sne i n&aelig;rheden','Paist&iacute; sneachta in aice l&aacute;imhe','Lokaal een sneeuwbui','spredt sn&oslash;fall i n&aelig;romr&aring;det','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1089;&#1085;&#1077;&#1075;','Povremen sneg','附近部分地區下雪'),
                        'Thundery outbreaks in nearby'=>array('Gewitter m&ouml;glich','Passages orageux probables','','','Pobliskie grzmoty','helyi viharkit&ouml;r&eacute;sek','','رعد متفشي في مكان قريب','Mulighed for torden','Toirneach in aice l&aacute;imhe','Lokale onweersbui','muligheter for torden','&#1084;&#1077;&#1089;&#1090;&#1072;&#1084;&#1080; &#1075;&#1088;&#1086;&#1079;&#1099;','Mogu&#263;e oluje','附近地區有雷鳴'),
                        'Torrential rain shower'=>array('Starkregen','Pluies torrentielles','','','Przelotne opady ulewnego deszczu','szakad&oacute; z&aacute;pores&#337;','','امطار غزيرة منهمره','Kraftig regn','Cith – d&iacute;le bh&aacute;ist&iacute;','Hoosbuien','kraftig regnfall','&#1087;&#1088;&#1086;&#1083;&#1080;&#1074;&#1085;&#1086;&#1081; &#1076;&#1086;&#1078;&#1076;&#1100;','Jaka ki&scaron;a','強烈陣雨'),
                        'Chance of a Thunderstorm'=>array('Gewitter m&ouml;glich','Probabilit&eacute; de temp&ecirc;te','','','Mo&#380;liwa burza','vihar val&oacute;sz&iacute;n&#369;s&eacute;ge','','فرصة حدوث عواصف رعدية','Mulighed for tordenvejr','Baol stoirm thoirn&iacute; ann','Kans op onweer','muligheter for torden','&#1074;&#1077;&#1088;&#1086;&#1103;&#1090;&#1085;&#1086;&#1089;&#1090;&#1100; &#1075;&#1088;&#1086;&#1079;&#1099;','Mogu&#263;e oluje','雷陣雨機率'),
                        'Chance of Flurries'=>array('Schneefall m&ouml;glich','probabilit&eacute; de rafales','','','Mo&#380;liwe lekkie przelotne opady &#347;niegu','sz&eacute;ll&ouml;k&eacute;sek val&oacute;sz&iacute;n&#369;s&eacute;ge','','احتمال هبوب رياح','Mulighed for sne','Baol ceathanna sneachta ann','Kans op sneeuw','muligheter for sn&oslash;fall','&#1074;&#1077;&#1088;&#1086;&#1103;&#1090;&#1085;&#1086;&#1089;&#1090;&#1100; &#1089;&#1085;&#1077;&#1075;&#1086;&#1087;&#1072;&#1076;&#1072;','Mogu&#263;e padavine','陣風機率'),
                        'Chance of Freezing Rain'=>array('&Uuml;berfrierender Regen m&ouml;glich','probabilit&eacute; de pluie glac&eacute;e','','','Mo&#380;liwe opady marzn&#261;cego deszczu','fagyos es&#337; val&oacute;sz&iacute;n&#369;s&eacute;ge','','فرصة هطول أمطار متجمده','Mulighed for frysende regn','Baol b&aacute;isteach sheaca ann','Kans op ijsel','muligheter for underkj&oslash;lt regn','&#1074;&#1077;&#1088;&#1086;&#1103;&#1090;&#1085;&#1086;&#1089;&#1090;&#1100; &#1083;&#1077;&#1076;&#1103;&#1085;&#1086;&#1075;&#1086; &#1076;&#1086;&#1078;&#1076;&#1103;','Mogu&#263;a ledena ki&scaron;a','凍雨機率'),
                        'Chance of Rain'=>array('Regen m&ouml;glich','Probabilit&eacute; de pluie','','','Mo&#380;liwe opady deszczu','es&#337; val&oacute;sz&iacute;n&#369;s&eacute;ge','','فرصة هطول أمطار','Mulighed for regn','Baol b&aacute;ist&iacute; ann','Kans op regen','muligheter for regn','&#1074;&#1077;&#1088;&#1086;&#1103;&#1090;&#1085;&#1086;&#1089;&#1090;&#1100; &#1076;&#1086;&#1078;&#1076;&#1103;','Mogu&#263;a ki&scaron;a','降雨機率'),
                        'Chance of Sleet'=>array('Graupelschauer m&ouml;glich','probabilit&eacute; de neige fondue','','','Mo&#380;liwe opady deszczu ze &#347;niegiem','&oacute;nos es&#337; val&oacute;sz&iacute;n&#369;s&eacute;ge','','فرصة من المطر الثلجي','Mulighed for slud','Baol flichshneachta ann','Kans op natte sneeuw','muligheter for sludd','&#1074;&#1077;&#1088;&#1086;&#1103;&#1090;&#1085;&#1086;&#1089;&#1090;&#1100; &#1084;&#1086;&#1082;&#1088;&#1086;&#1075;&#1086; &#1089;&#1085;&#1077;&#1075;&#1072;','Mogu&#263;a susne&#382;ica','降雨帶雪機率'),
                        'Chance of Snow'=>array('Schneefall m&ouml;glich','Probabilit&eacute; de neige','','','Mo&#380;liwe opady &#347;niegu','h&oacute; val&oacute;sz&iacute;n&#369;s&eacute;ge','','فرصة تساقط ثلوج','Mulighed for sne','Baol sneachta ann','Kans op sneeuw','muligheter for sn&oslash;fall','&#1074;&#1077;&#1088;&#1086;&#1103;&#1090;&#1085;&#1086;&#1089;&#1090;&#1100; &#1089;&#1085;&#1077;&#1075;&#1072;','Mogu&#263; sneg','下雪機率'),
                        'Chance of Thunderstorms'=>array('Gewitter m&ouml;glich','Probabilit&eacute; de temp&ecirc;te','','','Mo&#380;liwe burze','vihar val&oacute;sz&iacute;n&#369;s&eacute;ge','','فرصة من العواصف الرعدية','Mulighed for tordenvejr','Baol stoirmeacha toirn&iacute; ann','Kans op storm met onweer','muligheter for tordenbyger','&#1074;&#1077;&#1088;&#1086;&#1103;&#1090;&#1085;&#1086;&#1089;&#1090;&#1100; &#1075;&#1088;&#1086;&#1079;','Mogu&#263;e oluje','雷陣雨機率'),
                        'Mostly Sunny'=>array('&Uuml;berwiegend sonnig','Majoritairement ensoleill&eacute;','','','Przewa&#380;nie s&#322;onecznie','t&ouml;bbnyire napos','','غائم جزئيا','Overvejende solrig','Grianmhar den chuid is m&oacute;','Overwegend zonnig','for det meste sol','&#1087;&#1088;&#1077;&#1080;&#1084;&#1091;&#1097;&#1077;&#1089;&#1090;&#1074;&#1077;&#1085;&#1085;&#1086; &#1089;&#1086;&#1083;&#1085;&#1077;&#1095;&#1085;&#1086;','Uglavnom sun&#269;ano','大部分晴天'),
                        'Partly Sunny'=>array('Teilweise sonnig','Des &eacute;claircies','','','Cz&#281;&#347;ciowo s&#322;onecznie','r&eacute;szben napos','','مشمس جزئيا','Delvis sol','Roinnt grianmhar','Half bewolkt','delvis sol','&#1087;&#1088;&#1077;&#1080;&#1084;&#1091;&#1097;&#1077;&#1089;&#1090;&#1074;&#1077;&#1085;&#1085;&#1086; &#1089;&#1086;&#1083;&#1085;&#1077;&#1095;&#1085;&#1086;','Delimi&#269;no sun&#269;ano','部分晴天'),
                        'Thunderstorms'=>array('Gewitter','temp&ecirc;te et tonnerre','','','Burze','vihar','','عواصف رعدية','Tordenvejr','Stoirmeacha toirn&iacute;','Onweer Storm','torden','&#1075;&#1088;&#1086;&#1079;&#1099;','Oluje','雷陣雨'),
                                                                            
                 );                       
                 if(!isset($weather_detail_lang[$term_in][$opt_language_index]))
                  {$term_out=$term_in;}
                else
                  {$term_out=$weather_detail_lang[$term_in][$opt_language_index];}
                  //echo "IN:".$term_in."OUT:".$term_out;
                return $term_out;
}



function GG_funx_translate_uvindex($term_in,$opt_language_index){
     $uv_index_lang = array(
      //updated 25-05
                          '0'=>array('Keine','Z&eacute;ro','Nulo','Nulla','zerowy','nulla','nenhum','لا','Lav','N&aacute;id','Onweer','ingen','&#1085;&#1091;&#1083;&#1077;&#1074;&#1086;&#1081;','Nula',''),
                          '1'=>array('Niedrig','Faible','Bajo','Basso','niski','gyenge','Baixo','منخفض','Lav','&Iacute;seal','Onweer','lav','&#1085;&#1080;&#1079;&#1082;&#1080;&#1081;','Nizak',''),
                          '2'=>array('Niedrig','Faible','Bajo','Basso','niski','gyenge','Baixo','منخفض','Lav','&Iacute;seal','Onweer','lav','&#1085;&#1080;&#1079;&#1082;&#1080;&#1081;','Nizak',''),
                          '3'=>array('Mittel','Mod&eacute;r&eacute;','Moderado','Medio','umiarkowany','k&ouml;zepes','Moderado','متوسط','Moderat','Me&aacute;nach','Onweer','moderat','&#1089;&#1088;&#1077;&#1076;&#1085;&#1080;&#1081;','umereno',''),
                          '4'=>array('Mittel','Mod&eacute;r&eacute;','Moderado','Medio','umiarkowany','k&ouml;zepes','Moderado','متوسط','Moderat','Me&aacute;nach','Onweer','moderat','&#1089;&#1088;&#1077;&#1076;&#1085;&#1080;&#1081;','umereno',''),
                          '5'=>array('Mittel','Mod&eacute;r&eacute;','Moderado','Medio','umiarkowany','k&ouml;zepes','Moderado','متوسط','Moderat','Me&aacute;nach','Natte Sneeuw','moderat','&#1089;&#1088;&#1077;&#1076;&#1085;&#1080;&#1081;','umereno',''),
                          '6'=>array('Hoch','Elev&eacute;','Alto','Alto','wysoki','magas','Alto','عاليه','H&oslash;j','Ard','Regen en hagel','h&oslash;y','&#1074;&#1099;&#1089;&#1086;&#1082;&#1080;&#1081;','Visok',''),
                          '7'=>array('Hoch','Elev&eacute;','Alto','Alto','wysoki','magas','Alto','عاليه','H&oslash;j','Ard','Veel natte sneeuw','h&oslash;y','&#1074;&#1099;&#1089;&#1086;&#1082;&#1080;&#1081;','Visok',''),
                          '8'=>array('Sehr Hoch','Tr&ecirc;s Elev&eacute;','Muy Alto','Molto Alto','bardzo wysoki','nagyon magas','Muito Alto','عالية جدا','Meget h&oslash;j','An-ard','Lichte regen met kans op ijsel','meget h&oslash;y','&#1086;&#1095;&#1077;&#1085;&#1100; &#1074;&#1099;&#1089;&#1086;&#1082;&#1080;&#1081;','Veoma visok',''),
                          '9'=>array('Sehr Hoch','Tr&ecirc;s Elev&eacute;','Muy Alto','Molto Alto','bardzo wysoki','nagyon magas','Muito Alto','عالية جدا','Meget h&oslash;j','An-ard','Lichte regen','meget h&oslash;y','&#1086;&#1095;&#1077;&#1085;&#1100; &#1074;&#1099;&#1089;&#1086;&#1082;&#1080;&#1081;','Veoma visok',''),
                          '10'=>array('Sehr Hoch','Tr&ecirc;s Elev&eacute;','Muy Alto','Molto Alto','bardzo wysoki','nagyon magas','Muito Alto','عالية جدا','Meget h&oslash;j','An-ard','Regen met kans op ijsel','meget h&oslash;y','&#1074;&#1099;&#1089;&#1086;&#1082;&#1080;&#1081;','Veoma visok',''),
                          '10+'=>array('Extrem Hoch','Extr&ecirc;me Elev&eacute','Extremademente Alto','Estremo Alto','ekstremalnie wysoki','extr&eacute;m magas','Extremamente Alto','عالية جدا','Ekstrem','R&iacute;-ard','Extreem hoge','ekstrem h&oslash;y','&#1101;&#1082;&#1089;&#1090;&#1088;&#1077;&#1084;&#1072;&#1083;&#1100;&#1085;&#1086; &#1074;&#1099;&#1089;&#1086;&#1082;&#1080;&#1081;','Ekstremno visok',''),

                          
                          );
    if(!isset($uv_index_lang[$term_in][$opt_language_index]))
      {$term_out=$term_in;}
    else
      {$term_out=$uv_index_lang[$term_in][$opt_language_index];}
    return $term_out;
}

function GG_funx_translate_array($term_in,$language)
{
    //update 25-04
    $trans_array=array();
    $term_save=$term_in;
    $term_in="YYY".strtolower($term_in)."Y";
    if ($language=="ar"){
      $trans_array= array(
      'الشروط الفعلية', 	'عاصفة ثلجية', 	'الرياح', 	'صافي', 	'كثافة سحابة', 	'غائم', 	'ندى', 	'الشعور وكأنه', 	'الثلوج', 	'ضباب', 	'الجُمْعَة', 	'منذ', 	'الرياح', 	'برد', 	'ضباب', 	'رطوبة', 	'طول اليوم', 	'الأحد', 	'المرحلة القمرية', 	'التوقعات', 	'ليلاً', 	'هطول', 	'الضغط', 	'ممطر', 	'رمل', 	'مطر متجمد', 	'دخان', 	'مثلج', 	'عذراً.. لاتوجد بيانات متوفره حالياً!', 	'رذاذ', 	'قليله', 	'مشمس', 	'شروق الشمس في', 	'غروب الشمس في', 	'الإربعا', 	'شديده', 	'عواصف', 	'الأثنين', 	'وضوح', 	'الثلثاء', 	'غبار واسع الانتشار', 	'هادئه','السبت', 'في', 	'ساعات','نهاراً',   
       );}
    if ($language=="da"){
      $trans_array= array( 
      'aktuelt', 	'snestorm', 	'vindstille', 	'skyfri', 	'skyd&aelig;kke', 	'skyet', 	'dugpunkt', 	'f&oslash;les som', 	'snefygning', 	't&aring;ge', 	'fredag', 	'fra', 	'vindst&oslash;d', 	'hagl', 	'dis', 	'fugtighed', 	'dagsl&aelig;ngde', 	'mandag', 	'm&aring;nefase', 	'udsigt', 	'nat', 	'nedb&oslash;r', 	'tryk', 	'regn', 	'sand', 	'slud', 	'r&oslash;g', 	'snefygning', 	'Desv&aelig;rre ingen aktuelle vejrdata tilg&aelig;ngelige!', 	'spray', 	's&oslash;ndag', 	'solrig', 	'solopgang', 	'solnedgang', 	'torsdag', 	'i dag', 	'i morgen', 	'tirsdag', 	'sigtbarhed', 	'onsdag', 	'udbredt st&oslash;v', 	'vind', 'l&oslash;rdag', 'i', 	'h', 'dag',
    );}
    if ($language=="de"){
      $trans_array= array( 
      'Aktuell', 	'Schneesturm', 	'windstill', 	'wolkenlos', 	'Bew&ouml;lkungsdichte', 	'bew&ouml;lkt', 	'Taupunkt', 	'Gef&uuml;hlt', 	'Schneegest&ouml;ber', 	'Nebel', 	'Freitag', 	'aus', 	'B&ouml;en', 	'Hagel', 	'Dunst', 	'Feuchte', 	'Tagl&auml;nge', 	'Montag', 	'Mondphase', 	'Aussichten', 	'Nachts', 	'Niederschlag', 	'Druck', 	'Regen', 	'Sand', 	'Schneeregen', 	'Rauch', 	'Schnee', 	'Leider keine aktuellen Wetterdaten verf&uuml;gbar!', 	'Spray', 	'Sonntag', 	'sonnig', 	'Sonnenaufgang um', 	'Sonnenuntergang um', 	'Donnerstag', 	'Heute', 	'Morgen', 	'Dienstag', 	'Sichtbarkeit', 	'Mittwoch', 	'verbreitet Staub', 	'Wind', 'Samstag','in', 	'Uhr',  'Tags&uuml;ber',  
    );}
    if ($language=="es"){
      $trans_array=array( 
      'Actualmente', 	'ventisca', 	'Calma', 	'despejado', 	'Nube de densidad', 	'nublado', 	'Punto de roc&iacute;o', 	'T.de sensaci&oacute;n', 	'r&aacute;fagas', 	'niebla', 	'Viernes', 	'-', 	'R&aacute;fagas', 	'granizo', 	'neblina', 	'Humedad', 	'Duraci&oacute;n del dia', 	'Lunes', 	'Fase Lunar', 	'Perspectivas', 	'De la noche', 	'Precipitaci&oacute;n', 	'Presi&oacute;n', 	'lluvia', 	'arena', 	'aguanieve', 	'humo', 	'nieve', 	'Perd&oacute;n! Datos del tiempo no disponibles!', 	'spray', 	'Domingo', 	'soleado', 	'Amanecer', 	'Ocaso', 	'Jueves', 	'Hoy', 	'Ma&ntilde;ana', 	'Martes', 	'Visibilidad', 	'Miercoles', 	'Nubes de polvo', 	'Viento', 's&aacute;bado', 'en', 	'h', 'De dia', 
     );}
    if ($language=="fr"){
      $trans_array=array(
      'Actuellement', 	'Blizzard', 	'calme', 	'd&eacute;gag&eacute;', 	'La densit&eacute; des nuages', 	'nuageux', 	'Point de ros&eacute;e', 	'T.Ressentie', 	'averses de neige', 	'brouillard', 	'Vendredi', 	'du', 	'Rafales', 	'la gr&ecirc;le', 	'brume', 	'Humidit&eacute;', 	'Dur&eacute;e du jour', 	'Lundi', 	'Phase de la lune', 	'Perspectives', 	'Le nuit', 	'Pr&eacute;cipitations', 	'Pression', 	'pluie', 	'le sable', 	'neige fondue', 	'de fum&eacute;e', 	'neige', 	'D&eacute;sol&eacute; donn&eacute;es m&eacute;t&eacute;orologiques non disponibles', 	'de pulv&eacute;risation', 	'Dimanche', 	'ensoleill&eacute;', 	'Lever du soleil &agrave;', 	'Coucher du soleil', 	'Jeudi', 	'Aujourd&acute;hui', 	'Demain', 	'Mardi', 	'La visibilit&eacute;', 	'Mercredi', 	'la poussi&egrave;re r&eacute;pandue', 	'Vent', 'samedi', 'en', 	'h',  'Le jour', 
    );}
     if ($language=="ga"){
      $trans_array=array( 
      'D&aacute;la&iacute; aimsire mar at&aacute;', 	'S&iacute;obadh sneachta', 	'Ina chalm', 	'Sp&eacute;ir ghlan ', 	'Dl&uacute;s na scamall', 	'Scamallach', 	'Dr&uacute;chtphointe', 	'Teocht, &oacute; thaobh moth&uacute;:', 	'Ceathanna sneachta', 	'Ceo', 	'D&eacute; hAoine', 	'-', 	'Siota&iacute; gaoithe', 	'Clocha sneachta', 	'R&oacute; samh', 	'Bogthaise', 	'Fad an lae', 	'D&eacute; Luain', 	'C&eacute;im na geala&iacute;', 	'Na laethanta romhainn', 	'O&iacute;che', 	'Frasa&iacute;ocht', 	'Br&uacute;', 	'B&aacute;isteach', 	'Gaineamh', 	'Flichshneachta', 	'Deatach', 	'Sneachta', 	'&Aacute;r leithsc&eacute;al! N&iacute;l sonra&iacute; aimsire ar f&aacute;il!', 	'C&aacute;itheadh', 	'D&eacute; Domhnaigh', 	'Grianmhar', 	'&Eacute;ir&iacute; gr&eacute;ine ag:', 	'Lu&iacute; gr&eacute;ine ag:', 	'D&eacute;ardaoin', 	'Inniu', 	'Am&aacute;rach', 	'D&eacute; M&aacute;irt', 	'L&eacute;argas', 	'D&eacute; C&eacute;adaoin', 	'Deannach fairsing', 	'Gaoth','D&eacute; Sathairn', 'i gceann', 	'u','l&aacute;', 
    );}
     if ($language=="hu"){
      $trans_array=array( 
      'Aktu&aacute;lis adatok', 	'h&oacute;vihar', 	'sz&eacute;lcsend', 	'der&#369;s', 	'felh&#337;s&#369;r&#369;s&eacute;g', 	'felh&#337;s', 	'harmatpont', 	'&eacute;rz&eacute;sre', 	'h&oacute;z&aacute;porok', 	'k&ouml;d', 	'p&eacute;ntek', 	'/*-*/', 	'sz&eacute;ll&ouml;k&eacute;sek', 	'j&eacute;ges&#337;', 	'p&aacute;ra', 	'relat&iacute;v p&aacute;ratartalom', 	'a nap hossza', 	'h&eacute;tf&#337;', 	'holdf&aacute;zis', 	'El&#337;rejelz&eacute;s:', 	'&eacute;jszaka', 	'a csapad&eacute;k val&oacute;sz&iacute;n&#369;s&eacute;ge:', 	'nyom&aacute;s', 	'es&#337;', 	'homok', 	'&oacute;nos es&#337;', 	'f&uuml;st', 	'h&oacute;', 	'Eln&eacute;z&eacute;st! Nincs el&eacute;rhet&#337; aktu&aacute;lis id&#337;j&aacute;r&aacute;s-jelent&eacute;s!', 	'permet', 	'vas&aacute;rnap', 	'napos ', 	'napkelte', 	'napnyugta', 	'cs&uuml;t&ouml;rt&ouml;k', 	'Ma', 	'holnap', 	'kedd', 	'l&aacute;t&oacute;t&aacute;vols&aacute;g', 	'szerda', 	'kiterjedt por', 	'l&eacute;gmozg&aacute;s', 'szombat','h&aacute;travan', 	'h',  'l&aacute;', 
    );}    
    if ($language=="it"){
      $trans_array= array(
      ' Attuale', 	'bufera di neve', 	'Senza vento', 	'sereno', 	'Nuvola densit&agrave;', 	'nuvuloso', 	'Punto di rugiad', 	'T.percepita', 	'folate', 	'Nebbia', 	'Venerdi', 	'da', 	'Raffiche', 	'grandine', 	'foschia', 	'Umidit&aacute;', 	'Durata del giorno', 	'Lunedi', 	'Fase Lunar', 	'Prospettive', 	'Di notte', 	'Precipitatione', 	'Pressione', 	'pioggia', 	'sabbia', 	'nevischio', 	'fumo', 	'neve', 	'Spiacenti no dati disponible!', 	'spray', 	'Domenica', 	'solare', 	'Alba', 	'Tramonto', 	'Giovedi', 	'Oggi', 	'Domani', 	'Martedi', 	'Visibilit&agrave;', 	'Mercoledi', 	'polvere diffusa', 	'Vento','sabato', 'en', 	'h',   'Durante il giorno', 
     );}
    if ($language=="nl"){  
     $trans_array= array(
     'Huidige omstandigheden', 	'Sneeuwstorm', 	'Windstil', 	'Onbewolkt', 	'Bewolkt', 	'Bewolkt', 	'Dauwpunt', 	'Voelt aan als', 	'Sneeuwbuien', 	'Mist', 	'vrijdag', 	'vanuit', 	'Windstoten', 	'Hagelbuien', 	'Nevel', 	'Vochtigheid', 	'Duur van de dag', 	'maandag', 	'Maanstand', 	'Komende dagen', 	'Nacht', 	'Neerslag', 	'Luchtdruk', 	'Regen', 	'Zand', 	'Natte sneeuw', 	'Rook', 	'Sneeuw', 	'Helaas geen weersvoorspelling beschikbaar', 	'Buien', 	'zondag', 	'zonnig', 	'zonsopkomst om', 	'zonsondergang om', 	'donderdag', 	'vandaag', 	'morgen', 	'dinsdag', 	'zicht', 	'woensdag', 	'grote stofwolken', 	'wind', 'zaterdag', 'in', 	'h', 'dag', 
    );}
    if ($language=="no"){  
     $trans_array= array(
     'Faktiske forhold', 	'Sn&oslash;storm', 	'Vindstille', 	'Klarv&aelig;r', 	'Lettere skyet', 	'Overskyet', 	'duggpunkt', 	'f&oslash;les som', 	'om ettermiddagen', 	't&aring;ke', 	'fredag', 	'Vind fra', 	'vindkast', 	'hagl', 	'dis', 	'luftfuktighet', 	'Dagslys', 	'mandag', 	'm&aring;nefase', 	'neste dager', 	'natt', 	'nedb&oslash;r', 	'lufttrykk', 	'regn', 	'sand', 	'sludd', 	'r&oslash;yk', 	'sn&oslash;', 	'Beklager! Ingen v&aelig;rdata tilgjengelig.', 	'spr&oslash;ytet&aring;ke', 	's&oslash;ndag', 	'sol', 	'soloppgang', 	'solnedgang', 	'torsdag', 	'i dag', 	'i morgen', 	'tirsdag', 	'sikt', 	'onsdag', 	'omfatende st&oslash;y', 	'vind',  'l&oslash;rdag', 'i', 	'h',  'dag', 
    );}
    if ($language=="pl"){  
     $trans_array= array(
     'Obecnie', 	'zawieje i zamiecie &#347;nie&#380;ne', 	'bezwietrznie', 	'bezchmurnie', 	'Zachmurzenie', 	'pochmurno', 	'Punkt rosy', 	'odczuwalne', 	's&#322;abe przelotne opady &#347;niegu', 	'mg&#322;y', 	'pi&#261;tek', 	'z', 	'podmuchy', 	'grad', 	'mg&#322;y', 	'Wilgotno&#347;&#263; powietrza', 	'D&#322;ugo&#347;&#263; dnia', 	'poniedzia&#322;ek', 	'Faza Ksi&#281;&#380;yca', 	'Kolejne dni', 	'W nocy', 	'Opady', 	'Ci&#347;nienie', 	'opady deszczu', 	'piasek', 	'deszcz ze &#347;niegiem', 	'dym', 	'opady &#347;niegu', 	'Niestety aktualne dane pogodowe nie s&#261; dost&#281;pne!', 	'przelotna m&#380;awka', 	'niedziela', 	's&#322;onecznie', 	'Wsch&oacute;d S&#322;o&#324;ca o godz.', 	'Zach&oacute;d S&#322;o&#324;ca o godz.', 	'czwartek ', 	'Dzisiaj', 	'jutro', 	'wtorek', 	'Widoczno&#347;&#263;', 	'&#347;roda', 	'unosz&#261;ce si&#281; py&#322;y', 	'Wiatr', 'sobota', 'za', 	'h', 'W dzie&#324', 
    );}
    if ($language=="pt"){  
     $trans_array= array(
     'Agora', 	'nevasca', 	'Calma', 	'claro', 	'Densidade da nuvem', 	'nublado', 	'ponto de orvalh', 	'Sensa&ccedil;&atilde;o T&eacute;rmica', 	'flurries', 	'nevoeiro', 	'Sexta-feira', 	'de', 	'Rajadas', 	'granizo', 	'neblina', 	'Umidade', 	'Dura&ccedil;&atilde;o do dia', 	'Segunda-feira', 	'Fases da lua', 	'Perspectivas', 	'Noiche', 	'Precipita&ccedil;&atilde;o', 	'Press&atilde;o', 	'chuva', 	'areia', 	'sleet', 	'fuma&ccedil;a', 	'neve', 	'Sinto Muito! Dados meteorol&oacute;gicos n&atilde;o dispon&iacute;vel!', 	'spray', 	'Domingo', 	'ensolarado', 	'Amanhecer', 	'P&ocirc;r do sol', 	'Quinta-feira', 	'Hoje', 	'Manh&atilde;', 	'Ter&ccedil;a-feira', 	'visibilidade', 	'Quarta-feira', 	'poeira generalizada', 	'Vento', 'S&aacute;bado', 'en', 	'h',  'Dia', 
    );}
    if ($language=="ru"){  
     $trans_array= array(
     '&#1088;&#1077;&#1072;&#1083;&#1100;&#1085;&#1099;&#1077; &#1091;&#1089;&#1083;&#1086;&#1074;&#1080;&#1103;', 	'&#1073;&#1091;&#1088;&#1072;&#1085;', 	'&#1096;&#1090;&#1080;&#1083;&#1100;', 	'&#1103;&#1089;&#1085;&#1086;', 	'&#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;&#1089;&#1090;&#1100;', 	'&#1086;&#1073;&#1083;&#1072;&#1095;&#1085;&#1086;', 	'&#1090;&#1086;&#1095;&#1082;&#1072; &#1088;&#1086;&#1089;&#1099;', 	'&#1086;&#1097;&#1091;&#1097;&#1072;&#1077;&#1090;&#1089;&#1103; &#1082;&#1072;&#1082;', 	'&#1087;&#1086;&#1088;&#1099;&#1074;&#1099; &#1074;&#1077;&#1090;&#1088;&#1072;', 	'&#1090;&#1091;&#1084;&#1072;&#1085;', 	'&#1087;&#1103;&#1090;&#1085;&#1080;&#1094;&#1072;', 	'&#1086;&#1090;', 	'&#1087;&#1086;&#1088;&#1099;&#1074;&#1099; &#1074;&#1077;&#1090;&#1088;&#1072;', 	'&#1075;&#1088;&#1072;&#1076;', 	'&#1076;&#1099;&#1084;&#1082;&#1072;', 	'&#1074;&#1083;&#1072;&#1078;&#1085;&#1086;&#1089;&#1090;&#1100;', 	'&#1087;&#1088;&#1086;&#1076;&#1086;&#1083;&#1078;&#1080;&#1090;&#1077;&#1083;&#1100;&#1085;&#1086;&#1089;&#1090;&#1100; &#1076;&#1085;&#1103;', 	'&#1087;&#1086;&#1085;&#1077;&#1076;&#1077;&#1083;&#1100;&#1085;&#1080;&#1082;', 	'&#1092;&#1072;&#1079;&#1072; &#1083;&#1091;&#1085;&#1099;', 	'&#1087;&#1086;&#1089;&#1083;&#1077;&#1076;&#1091;&#1102;&#1097;&#1080;&#1077; &#1076;&#1085;&#1080;', 	'&#1085;&#1086;&#1095;&#1100;', 	'&#1086;&#1089;&#1072;&#1076;&#1082;&#1080;', 	'&#1076;&#1072;&#1074;&#1083;&#1077;&#1085;&#1080;&#1077;', 	'&#1076;&#1086;&#1078;&#1076;&#1100;', 	'&#1087;&#1099;&#1083;&#1100;&#1094;&#1072;', 	'&#1089;&#1083;&#1103;&#1082;&#1086;&#1090;&#1100;', 	'&#1089;&#1084;&#1086;&#1075;', 	'&#1089;&#1085;&#1077;&#1075;', 	'&#1076;&#1072;&#1085;&#1085;&#1099;&#1077; &#1086; &#1087;&#1086;&#1075;&#1086;&#1076;&#1077; &#1085;&#1077; &#1076;&#1086;&#1089;&#1090;&#1091;&#1087;&#1085;&#1099;!', 	'&#1084;&#1086;&#1088;&#1086;&#1089;&#1100;', 	'&#1074;&#1086;&#1089;&#1082;&#1088;&#1077;&#1089;&#1077;&#1085;&#1100;&#1077;', 	'&#1089;&#1086;&#1083;&#1085;&#1077;&#1095;&#1085;&#1086;', 	'&#1074;&#1086;&#1089;&#1093;&#1086;&#1076; &#1074; ', 	'&#1079;&#1072;&#1082;&#1072;&#1090; &#1074;', 	'&#1095;&#1077;&#1090;&#1074;&#1077;&#1088;&#1075;', 	'&#1089;&#1077;&#1075;&#1086;&#1076;&#1085;&#1103;', 	'&#1079;&#1072;&#1074;&#1090;&#1088;&#1072;', 	'&#1074;&#1090;&#1086;&#1088;&#1085;&#1080;&#1082;', 	'&#1074;&#1080;&#1076;&#1080;&#1084;&#1086;&#1089;&#1090;&#1100;', 	'&#1089;&#1088;&#1077;&#1076;&#1072;', 	'&#1086;&#1073;&#1096;&#1080;&#1088;&#1085;&#1072;&#1103; &#1079;&#1072;&#1087;&#1099;&#1083;&#1077;&#1085;&#1085;&#1086;&#1089;&#1090;&#1100;', 	'&#1074;&#1077;&#1090;&#1077;&#1088;','&#1089;&#1091;&#1073;&#1073;&#1086;&#1090;&#1072;','&#1074;', 	'h','&#1076;&#1077;&#1085;&#1100;',    
    );}
      if ($language=="sr"){  
     $trans_array= array(
     'trenutni uslovi', 	'me&#263;ava', 	'bez vetra', 	'vedro', 	'gusti oblaci', 	'obla&#269;no', 	'ta&#269;ka kondezacije', 	'subjektivni ose&#263;aj', 	'padavine', 	'magla', 	'petak', 	'od', 	'naleti', 	'grad', 	'izmaglica', 	'vla&#382;nost', 	'du&#382;ina dana', 	'ponedeljak', 	'faza meseca', 	'slede&#263;i dan', 	'no&#263;', 	'padavine', 	'pritisak', 	'ki&scaron;a', 	'pesak', 	'susne&#382;ica', 	'dim', 	'sneg', 	'&#381;ao nam je, vremenski podaci nisu dostupni!', 	'sprej', 	'nedelja', 	'sun&#269;ano', 	'izlazak sunca u', 	'zalazak sunca u', 	'&#269;etvrtak', 	'danas', 	'sutra', 	'utorak', 	'vidljivost', 	'sreda', 	'&scaron;irenje pra&scaron;ine', 	'Vetar', 'subota', 'U', 	'&#268;as', 'dan',  
    );}
    if ($language=="zh"){  
     $trans_array= array(
     '實際狀況', 	'暴風雪', 	'無風', 	'明朗', 	'有雲遮覆', 	'有雲', 	'露點', 	'感覺像是', 	'陣風', 	'霧', 	'星期五', 	'從', 	'強風', 	'冰雹', 	'薄霧', 	'濕度', 	'日的長短', 	'星期一', 	'月相週期', 	'隔天', 	'夜晚', 	'降雨量', 	'壓力', 	'雨', 	'沙', 	'凍雨', 	'煙', 	'雪', 	'抱歉!並無實際天氣之相關資料', 	'浪沫', 	'星期日', 	'晴天', 	'日出時間', 	'日落時間', 	'星期四', 	'今天', 	'明天', 	'星期二', 	'能見度', 	'星期三', 	'多沙塵', 	'風', 	'星期六', 	'在', 	'小時', 	'天',  
      );}
        
    $term_out=str_replace(
    array(
    'YYYactual conditionsY', 	'YYYblizzardY', 	'YYYcalmY', 	'YYYclear Y', 	'YYYcloudcoverY', 	'YYYcloudyY', 	'YYYdewpointY', 	'YYYfeels likeY', 	'YYYflurriesY', 	'YYYfogY', 	'YYYfridayY', 	'YYYfromY', 	'YYYgustsY', 	'YYYhailY', 	'YYYhazeY', 	'YYYhumidityY', 	'YYYlength of dayY', 	'YYYmondayY', 	'YYYmoonphaseY', 	'YYYnext daysY', 	'YYYnightY', 	'YYYprecipitationY', 	'YYYpressureY', 	'YYYrainY', 	'YYYsandY', 	'YYYsleetY', 	'YYYsmokeY', 	'YYYsnowY', 	'YYYsorry! no actual weather data available!Y', 	'YYYsprayY', 	'YYYsundayY', 	'YYYsunnyY', 	'YYYsunrise atY', 	'YYYsunset atY', 	'YYYthursdayY', 	'YYYtodayY', 	'YYYtomorrowY', 	'YYYtuesdayY', 	'YYYvisibilityY', 	'YYYwednesdayY', 	'YYYwidespread dustY', 	'YYYwindY','YYYsaturdayY','YYYinY', 	'YYYhrsY', 'YYYdayY',  
    ),$trans_array,$term_in);
    if(!$term_out){$term_out=$term_save;}
    return $term_out;    
} 

function GG_funx_translate_windspeed($term_in,$unit,$opt_language){
      $corr=1;
      $term_out="";
if ($opt_language=='en'){
if ($unit=='km/h'){$corr=1.609344;}
if ($term_in*$corr>0.5 and $term_in<=4.5){$term_out='Light Air';}
if ($term_in*$corr>4.6 and $term_in<=7.5){$term_out='Light Breeze';}
if ($term_in*$corr>7.5 and $term_in<=12.1){$term_out='Gentle Breeze';}
if ($term_in*$corr>12.1 and $term_in<=19){$term_out='Moderate Breeze';}
if ($term_in*$corr>19 and $term_in<=24.7){$term_out='Fresh Breeze';}
if ($term_in*$corr>24.7 and $term_in<=31.6){$term_out='Strong Breeze';}
if ($term_in*$corr>31.6 and $term_in<=38.6){$term_out='Moderate Gale';}
if ($term_in*$corr>38.6 and $term_in<=46.6){$term_out='Fresh Gale';}
if ($term_in*$corr>46.6 and $term_in<=54.7){$term_out='Strong Gale';}
if ($term_in*$corr>54.7 and $term_in<=63.9){$term_out='Whole Gale';}
if ($term_in*$corr>63.9 and $term_in<=73.1){$term_out='Storm';}
if ($term_in*$corr>73.1){$term_out='Hurricane';}
return $term_out;
}

if ($opt_language=='ar'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>10 and $term_in<=19){$term_out='الرياح خفيفه';}
if ($term_in*$corr>19 and $term_in<=28){$term_out='رياح لطيفة';}
if ($term_in*$corr>28 and $term_in<=37){$term_out='نسيم معتدل';}
if ($term_in*$corr>37 and $term_in<=46){$term_out='نسيم بارد';}
if ($term_in*$corr>46 and $term_in<=56){$term_out='رياح قويه';}
if ($term_in*$corr>56 and $term_in<=65){$term_out='رياح شديدة';}
if ($term_in*$corr>65 and $term_in<=74){$term_out='رياح ثابته';}
if ($term_in*$corr>74 and $term_in<=83){$term_out='عاصفه';}
if ($term_in*$corr>83 and $term_in<=102){$term_out='عاصفة ثقيلة';}
if ($term_in*$corr>102 and $term_in<=120){$term_out='عاصفة عنيفه';}
if ($term_in*$corr>120){$term_out='إعصار';}
return $term_out;
}

if ($opt_language=='de'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>1 and $term_in<=10){$term_out='Geringer Wind';}
if ($term_in*$corr>10 and $term_in<=19){$term_out='Leichter Wind';}
if ($term_in*$corr>19 and $term_in<=28){$term_out='Schwacher Wind';}
if ($term_in*$corr>28 and $term_in<=37){$term_out='M&auml;ssiger Wind';}
if ($term_in*$corr>37 and $term_in<=46){$term_out='Frischer Wind';}
if ($term_in*$corr>46 and $term_in<=56){$term_out='Starker Wind';}
if ($term_in*$corr>56 and $term_in<=65){$term_out='Starker bis st&uuml;ischer Wind';}
if ($term_in*$corr>65 and $term_in<=74){$term_out='St&uuml;rmischer Wind';}
if ($term_in*$corr>74 and $term_in<=83){$term_out='Sturm';}
if ($term_in*$corr>83 and $term_in<=102){$term_out='Schwerer Sturm';}
if ($term_in*$corr>102 and $term_in<=120){$term_out='Orkanartiger Sturm';}
if ($term_in*$corr>120){$term_out='Orkan';}
return $term_out;
}

if ($opt_language=='es'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>10 and $term_in<=19){$term_out='Brisa muy d&eacute;bil';}
if ($term_in*$corr>19 and $term_in<=28){$term_out='Brisa d&eacute;bil';}
if ($term_in*$corr>28 and $term_in<=37){$term_out='Brisa moderada';}
if ($term_in*$corr>37 and $term_in<=46){$term_out='Brisa fresca';}
if ($term_in*$corr>46 and $term_in<=56){$term_out='Brisa fuerte';}
if ($term_in*$corr>56 and $term_in<=65){$term_out='Viento fuerte';}
if ($term_in*$corr>65 and $term_in<=74){$term_out='Viento duro';}
if ($term_in*$corr>74 and $term_in<=83){$term_out='Muy duro';}
if ($term_in*$corr>83 and $term_in<=102){$term_out='Temporal';}
if ($term_in*$corr>102 and $term_in<=120){$term_out='Borrasca';}
if ($term_in*$corr>120){$term_out='Hurac&aacute;n';}
return $term_out;
}

if ($opt_language=='fr'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>6 and $term_in<=12){$term_out='L&eacute;ger brise';}
if ($term_in*$corr>12 and $term_in<=19){$term_out='Petite brise';}
if ($term_in*$corr>20 and $term_in<=29){$term_out='Jolie brise';}
if ($term_in*$corr>29 and $term_in<=39){$term_out='Bonne brise';}
if ($term_in*$corr>39 and $term_in<=50){$term_out='Vent frais';}
if ($term_in*$corr>50 and $term_in<=62){$term_out='Grand vent frais';}
if ($term_in*$corr>62 and $term_in<=74){$term_out='Coup de vent';}
if ($term_in*$corr>75 and $term_in<=89){$term_out='Fort coup de vent';}
if ($term_in*$corr>89 and $term_in<=103){$term_out='Temp&ecirc;te';}
if ($term_in*$corr>103 and $term_in<=118){$term_out='Viloente temp&ecirc;te';}
if ($term_in*$corr>118){$term_out='Ouragan';}
return $term_out;
}

if ($opt_language=='it'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>10 and $term_in<=19){$term_out='Brezza leggera';}
if ($term_in*$corr>19 and $term_in<=28){$term_out='Brezza tesa';}
if ($term_in*$corr>28 and $term_in<=37){$term_out='Vento moderato';}
if ($term_in*$corr>37 and $term_in<=46){$term_out='Vento teso';}
if ($term_in*$corr>46 and $term_in<=56){$term_out='Vento fresco';}
if ($term_in*$corr>56 and $term_in<=65){$term_out='Vento forte';}
if ($term_in*$corr>65 and $term_in<=74){$term_out='Burrasca';}
if ($term_in*$corr>74 and $term_in<=83){$term_out='Burrasca forte';}
if ($term_in*$corr>83 and $term_in<=102){$term_out='Tempesta';}
if ($term_in*$corr>102 and $term_in<=120){$term_out='Tempesta Violenta';}
if ($term_in*$corr>120){$term_out='Uragano';}
return $term_out;
}

if ($opt_language=='hu'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>0 and $term_in<=1){$term_out='sz&eacute;lcsend';}
if ($term_in*$corr>1 and $term_in<=6){$term_out='gyenge szell&#337;';}
if ($term_in*$corr>6 and $term_in<=11){$term_out='enyhe sz&eacute;l';}
if ($term_in*$corr>11 and $term_in<=19){$term_out='gyenge sz&eacute;l';}
if ($term_in*$corr>19 and $term_in<=29){$term_out='m&eacute;rs&eacute;kelt sz&eacute;l';}
if ($term_in*$corr>29 and $term_in<=39){$term_out='&eacute;l&eacute;nk sz&eacute;l';}
if ($term_in*$corr>39 and $term_in<=49){$term_out='er&#337;s sz&eacute;l';}
if ($term_in*$corr>49 and $term_in<=60){$term_out='viharos sz&eacute;l';}
if ($term_in*$corr>60 and $term_in<=72){$term_out='&eacute;l&eacute;nk viharos sz&eacute;l';}
if ($term_in*$corr>72 and $term_in<=85){$term_out='heves vihar';}
if ($term_in*$corr>85 and $term_in<=100){$term_out='d&uuml;h&ouml;ng&#337; vihar';}
if ($term_in*$corr>100 and $term_in<=115){$term_out='heves sz&eacute;lv&eacute;sz';}
if ($term_in*$corr>115 and $term_in<=120){$term_out='ork&aacute;n';}
if ($term_in*$corr>120){$term_out='hurrik&aacute;n';}
return $term_out;
}

if ($opt_language=='po'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>1 and $term_in<=6){$term_out='bardzo s&#322;aby';}
if ($term_in*$corr>6 and $term_in<=12){$term_out='s&#322;aby';}
if ($term_in*$corr>12 and $term_in<=20){$term_out='&#322;agodny';}
if ($term_in*$corr>20 and $term_in<=29){$term_out='umiarkowany';}
if ($term_in*$corr>29 and $term_in<=39){$term_out='do&#347;&#263; silny';}
if ($term_in*$corr>39 and $term_in<=50){$term_out='silny';}
if ($term_in*$corr>50 and $term_in<=62){$term_out='bardzo silny';}
if ($term_in*$corr>62 and $term_in<=75){$term_out='gwa&#322;towny';}
if ($term_in*$corr>75 and $term_in<=89){$term_out='wichura';}
if ($term_in*$corr>89 and $term_in<=103){$term_out='silna wichura';}
if ($term_in*$corr>103 and $term_in<=117){$term_out='gwa&#322;towna wichura';}
if ($term_in*$corr>117){$term_out='huragan';}
return $term_out;
}

if ($opt_language=='pt'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>0 and $term_in<=1){$term_out='Calmo';}
if ($term_in*$corr>1 and $term_in<=5){$term_out='Aragem';}
if ($term_in*$corr>6 and $term_in<=11){$term_out='Brisa leve';}
if ($term_in*$corr>12 and $term_in<=19){$term_out='Brisa Fraca';}
if ($term_in*$corr>20 and $term_in<=28){$term_out='Brisa Moderada';}
if ($term_in*$corr>29 and $term_in<=38){$term_out='Brisa Forte';}
if ($term_in*$corr>39 and $term_in<=49){$term_out='Vento Fresco';}
if ($term_in*$corr>50 and $term_in<=61){$term_out='Vento Forte';}
if ($term_in*$corr>62 and $term_in<=74){$term_out='Ventania';}
if ($term_in*$corr>75 and $term_in<=88){$term_out='Ventania Forte';}
if ($term_in*$corr>89 and $term_in<=102){$term_out='Tempestade Violenta';}
if ($term_in*$corr>103 and $term_in<=117){$term_out='Tempestade Violenta';}
if ($term_in*$corr>118){$term_out='Furac&atilde;o';}
return $term_out;
}

if ($opt_language=='da'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>0 and $term_in<=1){$term_out='Stille';}
if ($term_in*$corr>1 and $term_in<=6){$term_out='Luftning';}
if ($term_in*$corr>7 and $term_in<=11){$term_out='Svag vind';}
if ($term_in*$corr>12 and $term_in<=19){$term_out='Let vind';}
if ($term_in*$corr>20 and $term_in<=29){$term_out='J&aelig;vn vind';}
if ($term_in*$corr>30 and $term_in<=39){$term_out='Frisk vind';}
if ($term_in*$corr>40 and $term_in<=50){$term_out='H&aring;rd vind';}
if ($term_in*$corr>51 and $term_in<=62){$term_out='Stiv kuling';}
if ($term_in*$corr>63 and $term_in<=75){$term_out='H&aring;rd kuling';}
if ($term_in*$corr>76 and $term_in<=87){$term_out='Stormende kuling';}
if ($term_in*$corr>88 and $term_in<=102){$term_out='Storm';}
if ($term_in*$corr>103 and $term_in<=117){$term_out='St&aelig;rk storm';}
if ($term_in*$corr>118){$term_out='Orkan';}
return $term_out;
}

if ($opt_language=='ga'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>1 and $term_in<=10){$term_out='Leoithne ghaoithe ar &eacute;igean';}
if ($term_in*$corr>10 and $term_in<=19){$term_out='Leoithne bheag ghaoithe';}
if ($term_in*$corr>19 and $term_in<=28){$term_out='Leoithne chaoin ghaoithe';}
if ($term_in*$corr>28 and $term_in<=37){$term_out='Leoithne mheasartha ghaoithe';}
if ($term_in*$corr>37 and $term_in<=46){$term_out='Leoithne theann ghaoithe';}
if ($term_in*$corr>46 and $term_in<=56){$term_out='Leoithne l&aacute;idir ghaoithe';}
if ($term_in*$corr>56 and $term_in<=65){$term_out='G&aacute;la measartha';}
if ($term_in*$corr>65 and $term_in<=74){$term_out='G&aacute;la teann';}
if ($term_in*$corr>74 and $term_in<=83){$term_out='G&aacute;la l&aacute;idir';}
if ($term_in*$corr>83 and $term_in<=102){$term_out='G&aacute;la an-l&aacute;idir';}
if ($term_in*$corr>102 and $term_in<=120){$term_out='Stoirm';}
if ($term_in*$corr>120){$term_out='Hairic&iacute;n';}
return $term_out;
}

if ($opt_language=='nl'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>1 and $term_in<=10){$term_out='Windstil';}
if ($term_in*$corr>10 and $term_in<=19){$term_out='Zwakke wind';}
if ($term_in*$corr>19 and $term_in<=28){$term_out='Vrij matige wind';}
if ($term_in*$corr>28 and $term_in<=37){$term_out='Matige wind';}
if ($term_in*$corr>37 and $term_in<=46){$term_out='Vrij krachtige wind';}
if ($term_in*$corr>46 and $term_in<=56){$term_out='Krachtige wind';}
if ($term_in*$corr>56 and $term_in<=65){$term_out='Harde wind';}
if ($term_in*$corr>65 and $term_in<=74){$term_out='Stormachtige wind';}
if ($term_in*$corr>74 and $term_in<=83){$term_out='Storm';}
if ($term_in*$corr>83 and $term_in<=102){$term_out='Zware storm';}
if ($term_in*$corr>102 and $term_in<=120){$term_out='Zeer zware storm';}
if ($term_in*$corr>120){$term_out='Orkaan';}
return $term_out;
}

if ($opt_language=='no'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>1 and $term_in<=6){$term_out='Flau vind';}
if ($term_in*$corr>6 and $term_in<=11){$term_out='Svak vind';}
if ($term_in*$corr>11 and $term_in<=19){$term_out='Lett bris';}
if ($term_in*$corr>19 and $term_in<=29){$term_out='Laber bris';}
if ($term_in*$corr>29 and $term_in<=39){$term_out='Frisk bris';}
if ($term_in*$corr>39 and $term_in<=50){$term_out='Liten kuling';}
if ($term_in*$corr>50 and $term_in<=62){$term_out='Stiv kuling';}
if ($term_in*$corr>62 and $term_in<=75){$term_out='Sterk kuling';}
if ($term_in*$corr>75 and $term_in<=87){$term_out='Liten storm';}
if ($term_in*$corr>87 and $term_in<=102){$term_out='Full storm';}
if ($term_in*$corr>102 and $term_in<=117){$term_out='Sterk storm';}
if ($term_in*$corr>117){$term_out='Orkan';}
return $term_out;
}

if ($opt_language=='ru'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>0 and $term_in<=1){$term_out='&#1064;&#1090;&#1080;&#1083;&#1100;';}
if ($term_in*$corr>1 and $term_in<=5){$term_out='&#1058;&#1080;&#1093;&#1080;&#1081;';}
if ($term_in*$corr>6 and $term_in<=11){$term_out='&#1051;&#1077;&#1075;&#1082;&#1080;&#1081;';}
if ($term_in*$corr>12 and $term_in<=19){$term_out='&#1057;&#1083;&#1072;&#1073;&#1099;&#1081;';}
if ($term_in*$corr>20 and $term_in<=28){$term_out='&#1059;&#1084;&#1077;&#1088;&#1077;&#1085;&#1085;&#1099;&#1081;';}
if ($term_in*$corr>29 and $term_in<=38){$term_out='&#1057;&#1074;&#1077;&#1078;&#1080;&#1081;';}
if ($term_in*$corr>39 and $term_in<=49){$term_out='&#1057;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081;';}
if ($term_in*$corr>50 and $term_in<=61){$term_out='&#1050;&#1088;&#1077;&#1087;&#1082;&#1080;&#1081;';}
if ($term_in*$corr>62 and $term_in<=74){$term_out='&#1054;&#1095;&#1077;&#1085;&#1100; &#1082;&#1088;&#1077;&#1087;&#1082;&#1080;&#1081;';}
if ($term_in*$corr>75 and $term_in<=88){$term_out='&#1064;&#1090;&#1086;&#1088;&#1084;';}
if ($term_in*$corr>89 and $term_in<=102){$term_out='&#1057;&#1080;&#1083;&#1100;&#1085;&#1099;&#1081; &#1096;&#1090;&#1086;&#1088;&#1084;';}
if ($term_in*$corr>103 and $term_in<=117){$term_out='&#1046;&#1077;&#1089;&#1090;&#1086;&#1082;&#1080;&#1081; &#1096;&#1090;&#1086;&#1088;&#1084;';}
if ($term_in*$corr>117){$term_out='&#1059;&#1088;&#1072;&#1075;&#1072;&#1085;';}
return $term_out;
}

if ($opt_language=='se'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>1 and $term_in<=10){$term_out='Nizak vetar';}
if ($term_in*$corr>10 and $term_in<=19){$term_out='Povetarac';}
if ($term_in*$corr>19 and $term_in<=28){$term_out='Slab vetar';}
if ($term_in*$corr>28 and $term_in<=37){$term_out='Umereni vetrovi';}
if ($term_in*$corr>37 and $term_in<=46){$term_out='Sve&#382; vetar';}
if ($term_in*$corr>46 and $term_in<=56){$term_out='Jak vetar';}
if ($term_in*$corr>56 and $term_in<=65){$term_out='Sna&#382;an vetar';}
if ($term_in*$corr>65 and $term_in<=74){$term_out='Bura';}
if ($term_in*$corr>74 and $term_in<=83){$term_out='Oluja';}
if ($term_in*$corr>83 and $term_in<=102){$term_out='Jaka oluja';}
if ($term_in*$corr>102 and $term_in<=120){$term_out='Nasilna oluja';}
if ($term_in*$corr>120){$term_out='Uragan';}
return $term_out;
}

if ($opt_language=='zh'){
if ($unit=='mph'){$corr=1.609344;}
if ($term_in*$corr>0 and $term_in<=2){$term_out='無風/靜止';}
if ($term_in*$corr>2 and $term_in<=6){$term_out='輕微/微風/軟風';}
if ($term_in*$corr>7 and $term_in<=12){$term_out='輕微/微風/輕風';}
if ($term_in*$corr>13 and $term_in<=19){$term_out='和緩/溫和/微風';}
if ($term_in*$corr>20 and $term_in<=30){$term_out='和緩/和風';}
if ($term_in*$corr>31 and $term_in<=40){$term_out='清勁/清新/清風';}
if ($term_in*$corr>41 and $term_in<=51){$term_out='強風/清勁';}
if ($term_in*$corr>52 and $term_in<=62){$term_out='強風/疾風,熱帶性低氣壓';}
if ($term_in*$corr>63 and $term_in<=75){$term_out='烈風/疾勁/大風/輕度颱風';}
if ($term_in*$corr>76 and $term_in<=87){$term_out='烈風/輕度颱風';}
if ($term_in*$corr>88 and $term_in<=103){$term_out='暴風/狂風/輕度颱風';}
if ($term_in*$corr>104 and $term_in<=117){$term_out='暴風/颶風/輕度颱風';}
if ($term_in*$corr>118 and $term_in<=183){$term_out='颶風/中度颱風';}
if ($term_in*$corr>184){$term_out='颶風/強烈颱風 ';}
return $term_out;
}


      
}
function GG_funx_translate_pressure($term_in,$unit,$opt_language){
        //update 24-04
        $corr=1;
        $term_out="";
        if ($opt_language=="ar"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="منخفضه";}
          if ($term_in*$corr>1020){$term_out="مرتفعه";}
        }
        if ($opt_language=="de"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Tief";}
          if ($term_in*$corr>1020){$term_out="Hoch";}
        }
        if ($opt_language=="en"){
          if ($unit ==  "mbar"){$corr=33.8637526;}
          if ($term_in/$corr<29.6){$term_out="Low ";}     //29.6
          if ($term_in/$corr>30.1){$term_out="High ";}
        }
        if ($opt_language=="es"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Baja ";}
          if ($term_in*$corr>1020){$term_out="Alta ";}
        }
        if ($opt_language=="fr"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Basse ";}
          if ($term_in*$corr>1020){$term_out="Haute ";}
        }
        if ($opt_language=="it"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Bassa ";}
          if ($term_in*$corr>1020){$term_out="Alta ";}
        }
        if ($opt_language=="hu"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="alacsony ";}
          if ($term_in*$corr>1020){$term_out="magas ";}
        }
        if ($opt_language=="pl"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Niskie ";}
          if ($term_in*$corr>1020){$term_out="Wysokie ";}
        }
        if ($opt_language=="pt"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Bassa ";}
          if ($term_in*$corr>1020){$term_out="Alta ";}
        }
        if ($opt_language=="da"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Lav ";}
          if ($term_in*$corr>1020){$term_out="H&oslash;j ";}
        }
        if ($opt_language=="ga"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Teocht is &iacute;sle ";}
          if ($term_in*$corr>1020){$term_out="Teocht is airde ";}
        }
        if ($opt_language=="nl"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Laag ";}
          if ($term_in*$corr>1020){$term_out="Hoog ";}
        }
        if ($opt_language=="no"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Blav ";}
          if ($term_in*$corr>1020){$term_out="h&oslash;y ";}
        }
        if ($opt_language=="ru"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="&#1053;&#1080;&#1079;&#1082;&#1086; ";}
          if ($term_in*$corr>1020){$term_out="&#1042;&#1099;&#1089;&#1086;&#1082;&#1086; ";}
        }
        if ($opt_language=="sr"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="Nizak ";}
          if ($term_in*$corr>1020){$term_out="Visok ";}
        }
        if ($opt_language=="zh"){
          if ($unit ==  "in"){$corr=33.8637526;}
          if ($term_in*$corr<1003){$term_out="低 ";}
          if ($term_in*$corr>1020){$term_out="高 ";}
        }
        if ($opt_language=="xx"){ //to transfer pressure from mb to in
          if ($unit ==  "in"){$corr=33.8637526;}
          $term_out=round($term_in/$corr,2);
        }        
        return $term_out;       
}

function GG_funx_translate_inch($term_in,$unit){
        $corr=1;
        $term_out=$term_in;
        if ($unit ==  "->in"){$corr=25;
        $term_out=round($term_in/$corr,2);}
        if ($unit ==  "->mm"){$corr=25;
        $term_out=round($term_in*$corr,0);}
        return $term_out;       
}
function GG_funx_translate_speed($term_in,$unit){
        $corr=0.621371192;
        $term_out="";
        if ($unit ==  "mph"){
        $term_out=round($term_in*$corr,0);}
        if ($unit ==  "kmph"){
        $term_out=round($term_in/$corr,0);;
        }
        return $term_out;       
}
 
function GG_funx_translate_fahrenheit($term_in,$unit){
        $corr=1;
        $corr2=0;
        $term_out="";
        if ($unit ==  "m"){$corr=1.8;$corr2=32;}
        $term_out=round(($term_in-$corr2)/$corr,0);
        if ($unit ==  "m"){
        $term_out=$term_out."&deg;C";
        }
        else
        {
        $term_out=$term_out."&deg;F";
        }
        return $term_out;       
}

function GG_funx_translate_winddirections($term_in,$language){
//updated 25-04
  $term_in_save=$term_in;
  if (strlen($term_in)==1){$term_in=$term_in."X";}
  if (strlen($term_in)==2){$term_in=$term_in."X";}
  if ($language=="ar"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',),  
  array(	'O', 	'ONO', 	'OSO', 	'N', 	'NO', 	'NNO', 	'NNW', 	'NW', 	'S', 	'SO', 	'SSO', 	'SSW', 	'SW', 	'اتجاهات مختلفه', 	'W', 	'WNW', 	'WSW',), 
  $term_in);
  return $term_out;
  }
  if ($language=="da"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',),  
  array(	'&Oslash;', 	'&Oslash;N&Oslash;', 	'&Oslash;S&Oslash;', 	'N', 	'N&Oslash;', 	'NN&Oslash;', 	'NNV', 	'NV', 	'S', 	'S&Oslash;', 	'SS&Oslash;', 	'SSV', 	'SV', 	'vekslende retninger', 	'V', 	'VNV', 	'VSV',), 
  $term_in);
  return $term_out;
  } 
  if ($language=="de"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',),  	
  array(	'O', 	'ONO', 	'OSO', 	'N', 	'NO', 	'NNO', 	'NNW', 	'NW', 	'S', 	'SO', 	'SSO', 	'SSW', 	'SW', 	'verschiedenen Richtungen', 	'W', 	'WNW', 	'WSW',), 	
  $term_in);
  return $term_out;
  } 
  if ($language=="es"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',),  
  array(	'E', 	'ENE', 	'ESE', 	'N', 	'NE', 	'NNE', 	'NNO', 	'NO', 	'S', 	'SE', 	'SSE', 	'SSO', 	'SO', 	'VAR', 	'O', 	'ONO', 	'OSO',), 
  $term_in);
  return $term_out;
  }
  if ($language=="fr"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 
  array(	'E', 	'ENE', 	'ESE', 	'N', 	'NE', 	'NNE', 	'NNO', 	'NO', 	'S', 	'SE', 	'SSE', 	'SSO', 	'SO', 	'VAR', 	'O', 	'ONO', 	'OSO',), 
  $term_in);
  return $term_out;
  }
  if ($language=="ga"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 
  array(	'Anoir', 	'Anoir l&aacute;mh anoir aduaidh', 	'Anoir l&aacute;mh anoir aneas', 	'Aduaidh', 	'Anoir aduaidh', 	'Aduaidh l&aacute;mh anoir aduaidh', 	'Aduaidh l&aacute;mh aniar aduaidh', 	'Aniar aduaidh', 	'Aneas', 	'Anoir aneas', 	'Aneas l&aacute;mh anoir aneas', 	'Aneas l&aacute;mh aniar aneas', 	'Aniar aneas', 	'Athraitheach', 	'Aniar', 	'Aniar l&aacute;mh aniar aduaidh', 	'Aniar l&aacute;mh aniar aneas',), 
  $term_in);
  return $term_out;
  }  
  if ($language=="hu"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 	
  array(	'K-i', 	'K-&Eacute;K-i', 	'K-DK-i', 	'&Eacute;-i', 	'&Eacute;K-i', 	'&Eacute;-&Eacute;K-i', 	'&Eacute;-&Eacute;Ny-i', 	'&Eacute;Ny-i', 	'D-i', 	'DK-i', 	'D-DK-i', 	'D-DNy-i', 	'DNy-i', 	'v&aacute;ltoz&oacute; ir&aacute;ny&uacute;', 	'Ny-i', 	'Ny-&Eacute;Ny-i', 	'Ny-DNy-i',), 
  $term_in);
  return $term_out;
  }
  if ($language=="it"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 
  array(	'E', 	'ENE', 	'ESE', 	'N', 	'NE', 	'NNE', 	'NNO', 	'NO', 	'S', 	'SE', 	'SSE', 	'SSO', 	'SO', 	'VAR', 	'O', 	'ONO', 	'OSO',), 
  $term_in);
  return $term_out;
  }
  if ($language=="nl"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 	
  array(	'Oost', 	'ONO', 	'OZO', 	'Noord', 	'NO', 	'NNO', 	'NNW', 	'NW', 	'Zuid', 	'ZO', 	'ZZO', 	'Sneeuw', 	'ZW', 	'Verschillende richtingen', 	'West', 	'WNW', 	'WZW',), 
  $term_in);
  return $term_out;
  }
  if ($language=="no"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 	
  array(	'&Oslash;', 	'&Oslash;N&Oslash;', 	'&Oslash;S&Oslash;', 	'N', 	'N&Oslash;', 	'NN&Oslash;', 	'NNV', 	'NW', 	'S', 	'S&Oslash;', 	'SS&Oslash;', 	'SSV', 	'SV', 	'ulike retninger', 	'V', 	'VNV', 	'VSV',), 
  $term_in);
  return $term_out;
  }
  if ($language=="pl"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 	
  array(	'kierunku wschodniego', 	'p&oacute;&#322;nocnego wschodu i wschodu', 	'po&#322;udniowego wschodu i wschodu', 	'p&oacute;&#322;nocy', 	'p&oacute;&#322;nocnego wschodu', 	'p&oacute;&#322;nocy i p&oacute;&#322;nocnego wschodu', 	'p&oacute;&#322;nocy i p&oacute;&#322;nocnego zachodu', 	'p&oacute;&#322;nocnego zachodu', 	'po&#322;udnia', 	'po&#322;udniowego wschodu', 	'po&#322;udnia i po&#322;udniowego wschodu', 	'po&#322;udnia i po&#322;udniowego zachodu', 	'po&#322;udniowego zachodu', 	'kierunk&oacute;w zmiennych', 	'zachodu', 	'zachodu i p&oacute;&#322;nocnego zachodu', 	'zachodu i po&#322;udniowego zachodu',), 
  $term_in);
  }  
  if ($language=="pt"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 
  array(	'E', 	'ENE', 	'ESE', 	'N', 	'NE', 	'NNE', 	'NNO', 	'NO', 	'S', 	'SE', 	'SSE', 	'SSO', 	'SO', 	'VAR', 	'O', 	'ONO', 	'OSO',), 
  $term_in);
  }
  if ($language=="ru"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 
  array(	'&#1042;', 	'&#1042;&#1057;&#1042;', 	'&#1042;&#1070;&#1042;', 	'&#1057;', 	'&#1057;&#1042;', 	'&#1057;&#1057;&#1042;', 	'&#1057;&#1057;&#1047;', 	'&#1057;&#1047;', 	'&#1070;', 	'&#1070;&#1042;', 	'&#1070;&#1070;&#1042;', 	'&#1070;&#1070;&#1047;', 	'&#1070;&#1047;', 	'&#1074; &#1088;&#1072;&#1079;&#1085;&#1099;&#1093; &#1085;&#1072;&#1087;&#1088;&#1072;&#1074;&#1083;&#1077;&#1085;&#1080;&#1103;&#1093;', 	'&#1047;', 	'&#1047;&#1057;&#1047;', 	'&#1047;&#1070;&#1047;',), 
  $term_in);
  }
  if ($language=="sr"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 
  array(	'I', 	'ISI', 	'IJI', 	'S', 	'SI', 	'SSI', 	'SSZ', 	'SZ', 	'J', 	'JI', 	'JJI', 	'JJZ', 	'JZ', 	'promenljiv pravac', 	'Z', 	'ZSZ', 	'ZJZ',), 
  $term_in);
  }
  if ($language=="zh"){
  $term_out=str_replace(
  array(	'EXX', 	'ENE', 	'ESE', 	'NXX', 	'NEX', 	'NNE', 	'NNW', 	'NWX', 	'SXX', 	'SEX', 	'SSE', 	'SSW', 	'SWX', 	'VAR', 	'WXX', 	'WNW', 	'WSW',), 
  array(	'東', 	'東北東', 	'東南東', 	'北', 	'東北', 	'北北東', 	'北北西', 	'西北', 	'南', 	'東南', 	'南南東', 	'南南西', 	'西南', 	'不同方向', 	'西', 	'西北西', 	'西南西',), 
  $term_in);
  return $term_out;
  }
  if($term_out==""){
  $term_out=$term_in_save;
  return $term_out;}
       
}
function GG_funx_translate_winddirections_degrees($term_in){
$term_in=round($term_in/22.5,0);
//echo $term_in."<br /><br />";
  If($term_in==0 or $term_in==16 ){$term_out="N";}
  elseIf($term_in==1){$term_out="NNE";}
  elseIf($term_in==2){$term_out="NE";}
  elseIf($term_in==3){$term_out="ENE";}
  elseIf($term_in==4){$term_out="E";}
  elseIf($term_in==5){$term_out="ESE";}
  elseIf($term_in==6){$term_out="SE";}
  elseIf($term_in==7){$term_out="SSE";}
  elseIf($term_in==8){$term_out="S";}
  elseIf($term_in==9){$term_out="SSW";}
  elseIf($term_in==10){$term_out="SW";}
  elseIf($term_in==11){$term_out="WSW";}
  elseIf($term_in==12){$term_out="W";}
  elseIf($term_in==13){$term_out="WNW";}
  elseIf($term_in==14){$term_out="NW";}
  elseIf($term_in==15){$term_out="NNW";}
return $term_out;

}

function GG_funx_translate_capital($term_in)
{
    $trans_array=array(	
        'Georgetown','Andorra la Vella','Abu Dhabi','Kabul','Saint John&rsquo;s','The Valley','Tirana','Yerevan','Willemstad',
        'Luanda','Ross Dependency','Buenos Aires','Pago Pago','Vienna','Canberra','Oranjestad','Mariehamn','Baku','Stepanakert',
        'Sarajevo','Bridgetown','Dhaka','Brussels','Ouagadougou','Sofia','Manama','Bujumbura','Porto-Novo','Hamilton',
        'Bandar Seri Begawan','La Paz','Brasilia','Nassau','Thimphu','Bouvet Island','Gaborone','Minsk','Belmopan','Ottawa',
        'West Island','Kinshasa','Bangui','Brazzaville','Bern','Yamoussoukro','Avarua','Santiago','Yaounde','Beijing','Bogota',
        'San Jose','Havana','Praia','The Settlement','Nicosia','Nicosia','Prague','Berlin','Djibouti','Copenhagen','Roseau','Santo Domingo',
        'Algiers','Quito','Tallinn','Cairo','Asmara','Madrid','Addis Ababa','Helsinki','Suva','Stanley','Palikir','Torshavn',
        'Paris','Libreville','London','Saint George&rsquo;s','Tbilisi','Sokhumi','Tskhinvali','Cayenne','Saint Peter Port','Accra',
        'Gibraltar','Nuuk','Banjul','Conakry','Gustavia','Marigot','Basse-Terre','Malabo','Athens','South Georgia','Guatemala',
        'Hagatna','Bissau','Georgetown','Hong Kong','Heard Island','Tegucigalpa','Zagreb','Port-au-Prince','Budapest','Jakarta',
        'Dublin','Jerusalem','Douglas','New Delhi','British Indian Ocean Territory','Baghdad','Tehran','Reykjavik','Rome','Saint Helier',
        'Kingston','Amman','Tokyo','Nairobi','Bishkek','Phnom Penh','Tarawa','Moroni','Basseterre','Pyongyang','Seoul','Kuwait','George Town',
        'Astana','Vientiane','Beirut','Castries','Vaduz','Colombo','Monrovia','Maseru','Vilnius','Luxembourg','Riga','Tripoli',
        'Rabat','Monaco','Chisinau','Tiraspol','Podgorica','Antananarivo','Majuro','Skopje','Bamako','Naypyidaw','Ulaanbaatar',
        'Macau','Saipan','Fort-de-France','Nouakchott','Plymouth','Valletta','Port Louis','Male','Lilongwe','Mexico','Kuala Lumpur',
        'Maputo','Windhoek','Noumea','Niamey','Kingston','Abuja','Managua','Amsterdam','Oslo','Kathmandu','Yaren','Alofi',
        'Wellington','Muscat','Panama','Lima','Papeete','Clipperton Island','Port Moresby','Manila','Islamabad','Warsaw',
        'Saint-Pierre','Adamstown','San Juan','Lisbon','Melekeok','Asuncion','Doha','Saint-Denis','Bucharest','Belgrade',
        'Moscow','Kigali','Riyadh','Honiara','Victoria','Khartoum','Stockholm','Singapore','Jamestown','Ljubljana','Longyearbyen',
        'Bratislava','Freetown','San Marino','Dakar','Mogadishu','Hargeisa','Paramaribo','Sao Tome','San Salvador','Damascus',
        'Mbabane','Edinburgh','Grand Turk','N&rsquo;Djamena','Martin-de-Viviès','Lome','Bangkok','Dushanbe','Tokelau','Dili',
        'Ashgabat','Tunis','Nuku&rsquo;alofa','Ankara','Port-of-Spain','Funafuti','Taipei','Dar es Salaam','Kiev','Kampala',
        'Baker Island','Washington','Montevideo','Tashkent','Vatican City','Kingstown','Caracas','Road Town','Charlotte Amalie',
        'Hanoi','Port-Vila','Mata&rsquo;utu','Apia','Sanaa','Mamoudzou','Pretoria','Lusaka','Harare','London',

    );                                                                       
    $term_out=str_replace(array( 	
        'AC','AD','AE','AF','AG','AI','AL','AM','AN','AO','AQ','AR','AS','AT','AU','AW','AX','AZ','AZ','BA','BB','BD','BE','BF','BG','BH','BI','BJ','BM','BN','BO','BR','BS','BT','BV','BW','BY','BZ','CA','CC',
        'CD','CF','CG','CH','CI','CK','CL','CM','CN','CO','CR','CU','CV','CX','CY','CY','CZ','DE','DJ','DK','DM','DO','DZ','EC','EE','EG','ER','ES','ET','FI','FJ','FK','FM','FO','FR','GA','GB','GD','GE','GE',
        'GE','GF','GG','GH','GI','GL','GM','GN','GP','GP','GP','GQ','GR','GS','GT','GU','GW','GY','HK','HM','HN','HR','HT','HU','ID','IE','IL','IM','IN','IO','IQ','IR','IS','IT','JE','JM','JO','JP','KE','KG',
        'KH','KI','KM','KN','KP','KR','KW','KY','KZ','LA','LB','LC','LI','LK','LR','LS','LT','LU','LV','LY','MA','MC','MD','MD','ME','MG','MH','MK','ML','MM','MN','MO','MP','MQ','MR','MS','MT','MU','MV','MW',
        'MX','MY','MZ','NA','NC','NE','NF','NG','NI','NL','NO','NP','NR','NU','NZ','OM','PA','PE','PF','PF','PG','PH','PK','PL','PM','PN','PR','PT','PW','PY','QA','RE','RO','RS','RU','RW','SA','SB','SC','SD',
        'SE','SG','SH','SI','SJ','SK','SL','SM','SN','SO','SO','SR','ST','SV','SY','SZ','TA','TC','TD','TF','TG','TH','TJ','TK','TL','TM','TN','TO','TR','TT','TV','TW','TZ','UA','UG','UM','US','UY','UZ','VA',
        'VC','VE','VG','VI','VN','VU','WF','WS','YE','YT','ZA','ZM','ZW','UK',
    ),
    $trans_array		
    ,	
    $term_in);
    return $term_out;    
} 


function GG_funx_translate_country($term_in)
{$trans_array=array(	
        'AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC',
        'SD','TN','TX','UT','VT','VA','WA','WV','WI','WY',
    );                                                                       
    $term_out=str_replace(array( 	
        'alabama','alaska','arizona','arkansas','california','colorado','connecticut','delaware','florida','georgia','hawaii','idaho','illinois','indiana','iowa','kansas','kentucky','louisiana','maine',
        'maryland','massachusetts','michigan','minnesota','mississippi','missouri','montana','mebraska','mevada','mew hampshire','new jersey','new mexico','new york','north carolina','north dakota','ohio',
        'oklahoma','oregon','pennsylvania','rhode island','south carolina','south dakota','tennessee','texas','utah','vermont','virginia','washington','west virginia','wisconsin','wyoming',
    ),
    $trans_array		
    ,	
    $term_in);
    return $term_out;
} 

function GG_funx_translate_statename($term_in,$opt_language_index)
{
//update 24-04
$term_in=strtolower($term_in);
$state_name=array(

'af'=>array('Afghanistan','Afghanistan','Afganist&aacute;n','Afghanistan','Afganistan','Afganiszt&aacute;n','Afeganist&atilde;o','أفغانستان','Afghanistan','Afghanistan','Afghanistan','Afghanistan','&#1040;&#1092;&#1075;&#1072;&#1085;&#1080;&#1089;&#1090;&#1072;&#1085;','Avganistan','阿富汗'),
'al'=>array('Albanien','Albanie','Albania','Albania','Albania','Alb&aacute;nia','Alb&acirc;nia','ألبانيا','Albanien','Albania','Albani&euml;','Albania','&#1040;&#1083;&#1073;&#1072;&#1085;&#1080;&#1103;','Albanija','阿爾巴尼亞'),
'dz'=>array('Algerien','Alg&eacute;rie','Argelia','Algeria','Algieria','Alg&eacute;ria','Arg&eacute;lia','الجزائر','Algeriet','Algeria','Algerije','Algerie','&#1040;&#1083;&#1078;&#1080;&#1088;','Al&#382;ir','阿爾及利亞'),
'ad'=>array('Andorra','Andorre','Andorra','Andorra','Andora','Andorra','Andorra','أندورا','Andorra','Andorra','Andorra','Andorra','&#1040;&#1085;&#1076;&#1086;&#1088;&#1088;&#1072;','Andora','安道爾'),
'ao'=>array('Angola','Angola','Angola','Angola','Angola','Angola','Angola','أنغولا','Angola','Angola','Angola','Angola','&#1040;&#1085;&#1075;&#1086;&#1083;&#1072;','Angola','安哥拉'),
'ai'=>array('Anguilla','Anguilla','Anguila','Anguilla','Anguilla','Anguilla','Anguilla','أنغيلا','Anguilla','Anguilla','Anguilla','Anguilla','&#1040;&#1085;&#1075;&#1080;&#1083;&#1100;&#1103;','Angvila','安圭拉'),
'aq'=>array('Antarktika','Antarctique','ntarctica','Antartide','Antarktyda','Antarktisz','Antarctica','القارة القطبية الجنوبيه','Antarktis Antarktika','Antarctica','Antarctica','Antarktika','&#1040;&#1085;&#1090;&#1072;&#1088;&#1082;&#1090;&#1080;&#1076;&#1072;','Antarktik','南極洲'),
'ag'=>array('Antigua und Barbuda','Antigua-et-Barbuda','Antigua y Barbuda','Antigua e Barbuda','Antigua i Barbuda','Antigua &eacute;s Barbuda','Ant&iacute;gua e Barbuda','أنتيغوا وبربودا','Antigua og Barbuda','Antigua and Barbuda','Antigua en Barbuda','Antigua og Barbuda','&#1040;&#1085;&#1090;&#1080;&#1075;&#1091;&#1072; &#1080; &#1041;&#1072;&#1088;&#1073;&#1091;&#1076;&#1072;','Antigva i Barbuda','安提瓜'),
'sa'=>array('Saudi-Arabien','Arabie saoudite','Arabia Saudita','Arabia Saudita','Arabia Saudyjska','Sza&uacute;d-Ar&aacute;bia','Ar&aacute;bia Saudita','المملكة العربية السعودية','Saudi-Arabien','Saudi Arabia','Saoedi-Arabi&euml;','Saudi-Arabia','&#1057;&#1072;&#1091;&#1076;&#1086;&#1074;&#1089;&#1082;&#1072;&#1103; &#1040;&#1088;&#1072;&#1074;&#1080;&#1103;','Saudijska Arabija','沙地阿剌伯'),
'ar'=>array('Argentinien','Argentine','Argentina','Argentina','Argentyna','Argent&iacute;na','Argentina','الأرجنتين','Argentina','Argentina','Argentini&euml;','Argentina','&#1040;&#1088;&#1075;&#1077;&#1085;&#1090;&#1080;&#1085;&#1072;','Argentina','阿根廷'),
'am'=>array('Armenien','Arm&eacute;nie','Armenia','Armenia','Armenia','&Ouml;rm&eacute;nyorsz&aacute;g','Arm&eacute;nia','أرمينيا','Armenien','Armenia','Armeni&euml;','Armenia','&#1040;&#1088;&#1084;&#1077;&#1085;&#1080;&#1103;','Jermenija','亞美尼亞'),
'aw'=>array('Aruba','Aruba','Aruba','Aruba','Aruba','Aruba','Aruba','أوربا','Aruba','Aruba','Aruba','Aruba','&#1040;&#1088;&#1091;&#1073;&#1072;','Aruba','阿魯巴'),
'au'=>array('Australien','Australie','Australia','Australia','Australia','Ausztr&aacute;lia','Austr&aacute;lia','استراليا','Australien','Australia','Australi&euml;','Australia','&#1040;&#1074;&#1089;&#1090;&#1088;&#1072;&#1083;&#1080;&#1103;','Australija','澳洲'),
'at'=>array('&Ouml;sterreich','Autriche','Austria','Austria','Austria','Ausztria','&Aacute;ustria','النمسا','&Oslash;strig','Austria','Oostenrijk','&Oslash;sterrike','&#1040;&#1074;&#1089;&#1090;&#1088;&#1080;&#1103;','Austrija','奧地利'),
'az'=>array('Aserbaidschan','Azerba&iuml;djan','Azerbaiy&aacute;n','Azerbaigian','Azerbejd&#380;an','Azerbajdzs&aacute;n','Azerbaij&atilde;o','أذربيجان','Aserbajdsjan','Azerbaijan','Azerbeidzjan','Aserbajdsjan','&#1040;&#1079;&#1077;&#1088;&#1073;&#1072;&#1081;&#1076;&#1078;&#1072;&#1085;','Azerbejd&#382;an','阿塞拜疆'),
'bs'=>array('Bahamas','Bahamas','Bahamas','Bahamas','Bahamy','Bahama-szigetek','Baamas','جزر البهاما','Bahamas','Bahamas','Bahamas','Bahamas','&#1041;&#1072;&#1075;&#1072;&#1084;&#1099;','Bahami','巴哈馬'),
'bh'=>array('Bahrain','Bahre&iuml;n','Bar&eacute;in','Bahrain','Bahrajn','Bahrein','Bar&eacute;m','البحرين','Bahrain','Bahrain','Bahrein','Bahrain','&#1041;&#1072;&#1093;&#1088;&#1077;&#1081;&#1085;','Bahrein','巴林'),
'bd'=>array('Bangladesch','Bangladesh','Banglad&eacute;s','Bangladesh','Bangladesz','Banglades','Bangladeche','البنجلاديش','Bangladesh','Bangladesh','Bangladesh','Bangladesh','&#1041;&#1072;&#1085;&#1075;&#1083;&#1072;&#1076;&#1077;&#1096;','Banglade&scaron;','孟加拉'),
'bb'=>array('Barbados','Barbade','Barbados','Barbados','Barbados','Barbados','Barbados','بربادوس','Barbados','Barbados','Barbados','Barbados','&#1041;&#1072;&#1088;&#1073;&#1072;&#1076;&#1086;&#1089;','Barbados','巴巴多斯'),
'be'=>array('Belgien','Belgique','B&eacute;lgica','Belgio','Belgia','Belgium','B&eacute;lgica','بلجيكا','Belgien','Belgium','Belgi&euml;','Belgia','&#1041;&#1077;&#1083;&#1100;&#1075;&#1080;&#1103;','Belgija','比利時'),
'bz'=>array('Belize','Belize','Belice','Belize','Belize','Belize','Belize','بليز','Belize','Belize','Belize','Belize','&#1041;&#1077;&#1083;&#1080;&#1079;','Belize','伯利茲'),
'bj'=>array('Benin','B&eacute;nin','Ben&iacute;n','Benin','Benin','Benin','Benim','بنين','Benin','Benin','Benin','Benin','&#1041;&#1077;&#1085;&#1080;&#1085;','Benin','貝寧'),
'bm'=>array('Bermuda','Bermudes','Bermudas','Bermuda','Bermudy','Bermuda','Bermudas','برمودا','Bermuda','Bermuda','Bermuda','Bermuda','&#1041;&#1077;&#1088;&#1084;&#1091;&#1076;&#1099;','Bermudi','百慕達'),
'bt'=>array('Bhutan','Bhoutan','But&aacute;n','Bhutan','Bhutan','Bhut&aacute;n','But&atilde;o','بوتان','Bhutan','Bhutan','Bhutan','Bhutan','&#1041;&#1091;&#1090;&#1072;&#1085;','Butan','不丹'),
'by'=>array('Wei&szlig;russland','Bi&eacute;lorussie','Bielorrusia','Bielorussia','Bia&#322;oru&#347;','Feh&eacute;roroszorsz&aacute;g','Bielorr&uacute;ssia','روسيا البيضاء','Hviderusland','Belarus','Wit-Rusland','Hviterussland','&#1041;&#1077;&#1083;&#1086;&#1088;&#1091;&#1089;&#1089;&#1080;&#1103;','Belorusija','白俄羅斯'),
'mm'=>array('Burma','Birmanie','Birmania','Birmania','Birma','Mianmar','Myanmar','بورما','Burma','Myanmar','Myanmar','Myanmar','&#1052;&#1100;&#1103;&#1085;&#1084;&#1072;','Mianmar','Myanmar'),
'bo'=>array('Bolivien','Bolivie','Bolivia','Bolivia','Boliwia','Bol&iacute;via','Bol&iacute;via','بوليفيا','Bolivia','Bolivia, Plurinational State of','Bolivia','Bolivia','&#1041;&#1086;&#1083;&#1080;&#1074;&#1080;&#1103;','Bolivija','玻利維亞'),
'bq'=>array('Bonaire Sint Eustatius und Saba','Bonaire Saint-Eustache et Saba','onaire Saint Eustatius and Saba','Isole BES','Bonaire Sint Eustatius i Saba','Bonaire Saint Eustatius and Saba','Bonaire Saint Eustatius and Saba','بونير','Nederlandske Antiller','Bonaire Saint Eustatius and Saba','Bonaire Sint Eustatius en Saba','Karibisk Nederland','&#1041;&#1086;&#1085;&#1101;&#1081;&#1088;, &#1057;&#1080;&#1085;&#1090;-&#1069;&#1089;&#1090;&#1072;&#1090;&#1080;&#1091;&#1089; &#1080; &#1057;&#1072;&#1073;&#1072;','Bonaire Saint Eustatius and Saba','Bonaire'),
'bw'=>array('Botswana','Botswana','Botsuana','Botswana','Botswana','Botswana','Botsuana','بوتسوانا','Botswana','Botswana','Botswana','Botswana','&#1041;&#1086;&#1090;&#1089;&#1074;&#1072;&#1085;&#1072;','Bocvana','博茨瓦納'),
'ba'=>array('Bosnien und Herzegowina','Bosnie-Herz&eacute;govine','Bosnia y Herzegovina','Bosnia-Erzegovina','Bo&#347;nia i Hercegowina','Bosznia-Hercegovina','B&oacute;snia e Herzegovina','البوسنة والهرسك','Bosnien-Hercegovina','Bosnia and Herzegovina','Bosni&euml; en Herzegovina','Bosnia-Hercegovina','&#1041;&#1086;&#1089;&#1085;&#1080;&#1103; &#1080; &#1043;&#1077;&#1088;&#1094;&#1077;&#1075;&#1086;&#1074;&#1080;&#1085;&#1072;','Bosna i Hercegovina','波斯尼亞'),
'br'=>array('Brasilien','Br&eacute;sil','Brasil','Brasile','Brazylia','Braz&iacute;lia','Brasil','البرازيل','Brasilien','Brazil','Brazili&euml;','Brasil','&#1041;&#1088;&#1072;&#1079;&#1080;&#1083;&#1080;&#1103;','Brazil','巴西'),
'bn'=>array('Brunei Darussalam','Brunei','Brun&eacute;i','Brunei','Brunei','Brunei','Brunei','بروناي دار السلام','Brunei','Brunei Darussalam','Brunei','Brunei Darussalam','&#1041;&#1088;&#1091;&#1085;&#1077;&#1081;','Brunej','汶萊'),
'io'=>array('Britisches Territorium im Indischen Ozean','Territoire britannique de loc&eacute;an Indien','Territorio Brit&aacute;nico del Oc&eacute;ano &Iacute;ndico','Territorio britannico delloceano Indiano','Brytyjskie Terytorium Oceanu Indyjskiego','Brit Indiai-&oacute;ce&aacute;ni Ter&uuml;let','British Indian Ocean Territory','إقليم المحيط الهندي البريطاني','British Indian Ocean Territory','British Indian Ocean Territory','Brits Indische Oceaanterritorium','Det britiske territoriet i Indiahavet','&#1041;&#1088;&#1080;&#1090;&#1072;&#1085;&#1089;&#1082;&#1072;&#1103; &#1090;&#1077;&#1088;&#1088;&#1080;&#1090;&#1086;&#1088;&#1080;&#1103; &#1074; &#1048;&#1085;&#1076;&#1080;&#1081;&#1089;&#1082;&#1086;&#1084; &#1086;&#1082;&#1077;&#1072;&#1085;&#1077;','Britanska Teritorija Indijskog Okeana','英屬印度洋地區'),
'vg'=>array('Britische Jungferninseln','&Icirc;les Vierges britanniques','Islas V&iacute;rgenes Brit&aacute;nicas','Isole Vergini britanniche','Brytyjskie Wyspy Dziewicze','Brit Virgin-szigetek','Ilhas Virgens Brit&acirc;nicas','جزر فيرجن البريطانية','Britiske Jomfru&oslash;er','Virgin Islands, British','Britse Maagdeneilanden','De britiske Jomfru&oslash;yene','&#1041;&#1088;&#1080;&#1090;&#1072;&#1085;&#1089;&#1082;&#1080;&#1077; &#1042;&#1080;&#1088;&#1075;&#1080;&#1085;&#1089;&#1082;&#1080;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Britanska Devi&#269;anska Ostrva','英屬處女群島'),
'bf'=>array('Burkina Faso','Burkina Faso','Burkina Faso','Burkina Faso','Burkina Faso','Burkina Faso','Burquina Faso','بوركينا فاسو','Burkina Faso','Burkina Faso','Burkina Faso','Burkina Faso','&#1041;&#1091;&#1088;&#1082;&#1080;&#1085;&#1072;-&#1060;&#1072;&#1089;&#1086;','Burkina Faso','布基納法索'),
'bi'=>array('Burundi','Burundi','Burundi','Burundi','Burundi','Burundi','Bur&uacute;ndi','بوروندي','Burundi','Burundi','Burundi','Burundi','&#1041;&#1091;&#1088;&#1091;&#1085;&#1076;&#1080;','Burundi','布隆迪'),
'bg'=>array('Bulgarien','Bulgarie','Bulgaria','Bulgaria','Bu&#322;garia','Bulg&aacute;ria','Bulg&aacute;ria','بلغاريا','Bulgarien','Bulgaria','Bulgarije','Bulgaria','&#1041;&#1086;&#1083;&#1075;&#1072;&#1088;&#1080;&#1103;','Bugarska','保加利亞'),
'cl'=>array('Chile','Chili','Chile','Cile','Chile','Chile','Chile','تشيلي','Chile','Chile','Chili','Chile','&#1063;&#1080;&#1083;&#1080;','&#268;ile','智利'),
'cn'=>array('China','Chine','China','Cina','Chiny','K&iacute;na','China','الصين','Kina','China','China','Kina','&#1050;&#1053;&#1056;','Kina','中國人民共和國'),
'hr'=>array('Kroatien','Croatie','Croacia','Croazia','Chorwacja','Horv&aacute;torsz&aacute;g','Cro&aacute;cia','كرواتيا','Kroatien','Croatia','Kroati&euml;','Kroatia','&#1061;&#1086;&#1088;&#1074;&#1072;&#1090;&#1080;&#1103;','Hrvatska','克羅地亞'),
'cw'=>array('Cura&ccedil;ao','Cura&ccedil;ao','ura&ccedil;ao','Cura&ccedil;ao','Cura&ccedil;ao','Cura&ccedil;ao','Cura&ccedil;ao','كوراساو','Cura&ccedil;ao','Cura&ccedil;ao','Cura&ccedil;ao','Cura&ccedil;ao','&#1050;&#1102;&#1088;&#1072;&#1089;&#1072;&#1086;','Cura&ccedil;ao','Cura&ccedil;ao'),
'cy'=>array('Zypern','Chypre (pays)','Chipre','Cipro','Cypr','Ciprus','Chipre','قبرص','Cypern','Cyprus','Cyprus','Kypros','&#1050;&#1080;&#1087;&#1088;','Kipar','塞浦路斯'),
'td'=>array('Tschad','Tchad','Chad','Ciad','Czad','Cs&aacute;d','Chade','تشاد','Tchad','Chad','Tsjaad','Tsjad','&#1063;&#1072;&#1076;','&#268;ad','乍得'),
'me'=>array('Montenegro','Mont&eacute;n&eacute;gro','Montenegro','Montenegro','Czarnog&oacute;ra','Montenegr&oacute;','Montenegro','الجبل الأسود','Montenegro','Montenegro','Montenegro','Montenegro','&#1063;&#1077;&#1088;&#1085;&#1086;&#1075;&#1086;&#1088;&#1080;&#1103;','Crna Gora','黑山'),
'cz'=>array('Tschechische Republik','R&eacute;publique tch&egrave;que','Rep&uacute;blica Checa','Repubblica Ceca','Czechy','Csehorsz&aacute;g','Rep&uacute;blica Checa','الجمهورية التشيكية','Tjekkiet','Czech Republic','Tsjechi&euml;','Tsjekkia','&#1063;&#1077;&#1093;&#1080;&#1103;','&#268;e&scaron;ka Republika','捷克'),
'um'=>array('United States Minor Outlying Islands','&Icirc;les mineures &eacute;loign&eacute;es des &Eacute;tats-Unis','nited States Minor Outlying Islands','Isole minori degli Stati Uniti','Dalekie Wyspy Mniejsze Stan&oacute;w Zjednoczonych','Amerikai Csendes-&oacute;ce&aacute;ni szigetek','United States Minor Outlying Islands','الولايات المتحدة البعيدة الجزر الصغيرة','United States Minor Outlying Islands','United States Minor Outlying Islands','Kleine Pacifische eilanden van de Verenigde Staten','USAs ytre sm&aring;&oslash;yer','&#1042;&#1085;&#1077;&#1096;&#1085;&#1080;&#1077; &#1084;&#1072;&#1083;&#1099;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072; (&#1057;&#1064;&#1040;)','Manja udaljena ostrva SAD','美國邊疆小島'),
'dk'=>array('D&auml;nemark','Danemark','Dinamarca','Danimarca','Dania','D&aacute;nia','Dinamarca','الدنيمارك','Danmark','Denmark','Denemarken','Danmark','&#1044;&#1072;&#1085;&#1080;&#1103;','Danska','丹麥'),
'cd'=>array('Kongo','R&eacute;p. d&eacute;m. du Congo / (R&eacute;publique d&eacute;mocratique du Congo)','Rep. Dem. del Congo','Rep. Dem. del Congo','Demokratyczna Republika Konga','Kong&oacute;i Demokratikus K&ouml;zt&aacute;rsas&aacute;g (Zaire)','Congo-Kinshasa','الكونغو','Demokratiske Republik Congo','Congo','Congo-Kinshasa','Den demokratiske republikken Kongo','&#1044;&#1056; &#1050;&#1086;&#1085;&#1075;&#1086;','Demokratska Republika Kongo','剛果'),
'dm'=>array('Dominica','Dominique','Dominica','Dominica','Dominika','Dominikai K&ouml;z&ouml;ss&eacute;g','Dom&iacute;nica','دومينيكا','Dominica','Dominica','Dominica','Dominica','&#1044;&#1086;&#1084;&#1080;&#1085;&#1080;&#1082;&#1072;','Dominika','多明尼加'),
'do'=>array('Dominikanische Republik','R&eacute;publique dominicaine','Rep&uacute;blica Dominicana','Repubblica Dominicana','Dominikana','Dominikai K&ouml;zt&aacute;rsas&aacute;g','Rep&uacute;blica Dominicana','جمهورية الدومينيكان','Dominikanske Republik','Dominican Republic','Dominicaanse Republiek','Den dominikanske republikk','&#1044;&#1086;&#1084;&#1080;&#1085;&#1080;&#1082;&#1072;&#1085;&#1089;&#1082;&#1072;&#1103; &#1056;&#1077;&#1089;&#1087;&#1091;&#1073;&#1083;&#1080;&#1082;&#1072;','Dominikanska Republika','多明尼加'),
'dj'=>array('Dschibuti','Djibouti','Yibuti','Gibuti','D&#380;ibuti','Dzsibuti','Jibuti','جيبوتي','Djibouti','Djibouti','Djibouti','Djibouti','&#1044;&#1078;&#1080;&#1073;&#1091;&#1090;&#1080;','D&#382;ibuti','吉布提'),
'eg'=>array('&Auml;gypten','&Eacute;gypte','Egipto','Egitto','Egipt','Egyiptom','Egipto','مصر','Egypten','Egypt','Egypte','Egypt','&#1045;&#1075;&#1080;&#1087;&#1077;&#1090;','Egipat','埃及'),
'ec'=>array('Ecuador','&Eacute;quateur','Ecuador','Ecuador','Ekwador','Ecuador','Equador','الاكوادور','Ecuador','Ecuador','Ecuador','Ecuador','&#1069;&#1082;&#1074;&#1072;&#1076;&#1086;&#1088;','Ekvador','厄瓜多爾'),
'er'=>array('Eritrea','&Eacute;rythr&eacute;e','Eritrea','Eritrea','Erytrea','Eritrea','Eritreia','اريتريا','Eritrea','Eritrea','Eritrea','Eritrea','&#1069;&#1088;&#1080;&#1090;&#1088;&#1077;&#1103;','Eritreja','厄利特利亞'),
'ee'=>array('Estland','Estonie','Estonia','Estonia','Estonia','&Eacute;sztorsz&aacute;g','Est&oacute;nia','استونيا','Estland','Estonia','Estland','Estland','&#1069;&#1089;&#1090;&#1086;&#1085;&#1080;&#1103;','Estonija','愛沙尼亞'),
'et'=>array('&Auml;thiopien','&Eacute;thiopie','Etiop&iacute;a','Etiopia','Etiopia','Eti&oacute;pia','Eti&oacute;pia','أثيوبيا','Etiopien','Ethiopia','Ethiopi&euml;','Etiopia','&#1069;&#1092;&#1080;&#1086;&#1087;&#1080;&#1103;','Etiopija','埃塞俄比亞'),
'fk'=>array('Falklandinseln','&Icirc;les Malouines','Islas Malvinas','Isole Falkland','Falklandy','Falkland-szigetek','Falkland Islands','جزر فوكلاند','Falklands&oslash;erne','Falkland Islands','Falklandeilanden','Falklands&oslash;yene','&#1060;&#1086;&#1083;&#1082;&#1083;&#1077;&#1085;&#1076;&#1089;&#1082;&#1080;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Foklandska Ostrva (Malvini)','福克蘭群島'),
'fj'=>array('Fidschi','Fidji','Fiyi','Figi','Fid&#380;i','Fidzsi','Fiji','فيجي','Fiji','Fiji','Fiji','Fiji','&#1060;&#1080;&#1076;&#1078;&#1080;','Fid&#382;i','斐濟'),
'ph'=>array('Philippinen','Philippines','Filipinas','Filippine','Filipiny','F&uuml;l&ouml;p-szigetek','Filipinas','الفلبين','Filippinerne','Philippines','Filipijnen','Filippinene','&#1060;&#1080;&#1083;&#1080;&#1087;&#1087;&#1080;&#1085;&#1099;','Filipini','菲律賓'),
'fi'=>array('Finnland','Finlande','Finlandia','Finlandia','Finlandia','Finnorsz&aacute;g','Finl&acirc;ndia','فنلندا','Finland','Finland','Finland','Finland','&#1060;&#1080;&#1085;&#1083;&#1103;&#1085;&#1076;&#1080;&#1103;','Finska','芬蘭'),
'fr'=>array('Frankreich','France','Francia','Francia','Francja','Franciaorsz&aacute;g','Fran&ccedil;a','فرنسا','Frankrig','France','Frankrijk','Frankrike','&#1060;&#1088;&#1072;&#1085;&#1094;&#1080;&#1103;','Francuska','法國'),
'tf'=>array('Franz&ouml;sische S&uuml;d- und Antarktisgebiete','Terres australes et antarctiques fran&ccedil;aises','Territorios Australes Franceses','Terre Australi e Antartiche Francesi','Francuskie Terytoria Po&#322;udniowe i Antarktyczne','Francia D&eacute;li &eacute;s Antarktiszi Ter&uuml;letek','French Southern Territories','الأراضي الفرنسية الجنوبية والقارة القطبية الجنوبية','Franske Sydterritorier','French Southern Territories','Franse Zuidelijke en Antarctische Gebieden','Franske sydterritorier','&#1060;&#1088;&#1072;&#1085;&#1094;&#1091;&#1079;&#1089;&#1082;&#1080;&#1077; &#1070;&#1078;&#1085;&#1099;&#1077; &#1080; &#1040;&#1085;&#1090;&#1072;&#1088;&#1082;&#1090;&#1080;&#1095;&#1077;&#1089;&#1082;&#1080;&#1077; &#1058;&#1077;&#1088;&#1088;&#1080;&#1090;&#1086;&#1088;&#1080;&#1080;','Francuske Ju&#382;ne Teritorije','法屬南部地區'),
'ga'=>array('Gabun','Gabon','Gab&oacute;n','Gabon','Gabon','Gabon','Gab&atilde;o','الغابون','Gabon','Gabon','Gabon','Gabon','&#1043;&#1072;&#1073;&#1086;&#1085;','Gabon','加蓬'),
'gm'=>array('Gambia','Gambie','Gambia','Gambia','Gambia','Gambia','G&acirc;mbia','غامبيا','Gambia','Gambia','Gambia','Gambia','&#1043;&#1072;&#1084;&#1073;&#1080;&#1103;','Gambija','岡比亞'),
'gs'=>array('S&uuml;dgeorgien und die S&uuml;dlichen Sandwichinseln','G&eacute;orgie du Sud-et-les &Icirc;les Sandwich du Sud','Islas Georgias del Sur y Sandwich del Sur','Georgia del Sud e isole Sandwich','Georgia Po&#322;udniowa i Sandwich Po&#322;udniowy','D&eacute;li-Georgia &eacute;s D&eacute;li-Sandwich-szigetek','South Georgia and the South Sandwich Islands','جورجيا الجنوبية وجزر ساندويتش الجنوبية','South Georgia og South Sandwich Islands','South Georgia and the South Sandwich Islands','Zuid-Georgia en de Zuidelijke Sandwicheilanden','S&oslash;r-Georgia og S&oslash;r-Sandwich&oslash;yene','&#1070;&#1078;&#1085;&#1072;&#1103; &#1043;&#1077;&#1086;&#1088;&#1075;&#1080;&#1103; &#1080; &#1070;&#1078;&#1085;&#1099;&#1077; &#1057;&#1072;&#1085;&#1076;&#1074;&#1080;&#1095;&#1077;&#1074;&#1099; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Ju&#382;na D&#382;ord&#382;ija i Ju&#382;na Sendvi&#269;ka Ostrva','南喬治亞及南三文治群島'),
'gh'=>array('Ghana','Ghana','Ghana','Ghana','Ghana','Gh&aacute;na','Gana','غانا','Ghana','Ghana','Ghana','Ghana','&#1043;&#1072;&#1085;&#1072;','Gana','加納'),
'gi'=>array('Gibraltar','Gibraltar','Gibraltar','Gibilterra','Gibraltar','Gibralt&aacute;r','Gibraltar','جبل طارق','Gibraltar','Gibraltar','Gibraltar','Gibraltar','&#1043;&#1080;&#1073;&#1088;&#1072;&#1083;&#1090;&#1072;&#1088;','Gibraltar','直布羅陀'),
'gr'=>array('Griechenland','Gr&egrave;ce','Grecia','Grecia','Grecja','G&ouml;r&ouml;gorsz&aacute;g','Gr&eacute;cia','يونان','Gr&aelig;kenland','Greece','Griekenland','Hellas','&#1043;&#1088;&#1077;&#1094;&#1080;&#1103;','Gr&#269;ka','希臘'),
'gd'=>array('Grenada','Grenade (pays)','Granada','Grenada','Grenada','Grenada','Granada','غرينادا','Grenada','Grenada','Grenada','Grenada','&#1043;&#1088;&#1077;&#1085;&#1072;&#1076;&#1072;','Grenada','格林納達'),
'gl'=>array('Gr&ouml;nland','Groenland','Groenlandia','Groenlandia','Grenlandia','Gr&ouml;nland','Greenland','غرينلاند','Gr&oslash;nland','Greenland','Groenland','Gr&oslash;nland','&#1043;&#1088;&#1077;&#1085;&#1083;&#1072;&#1085;&#1076;&#1080;&#1103;','Grenland','格陵蘭'),
'ge'=>array('Georgien','G&eacute;orgie (pays)','Georgia','Georgia','Gruzja','Gr&uacute;zia','Ge&oacute;rgia','جورجيا','Georgien','Georgia','Georgi&euml;','Georgia','&#1043;&#1088;&#1091;&#1079;&#1080;&#1103;','Gruzija','格魯吉亞'),
'gu'=>array('Guam','Guam','Guam','Guam','Guam','Guam','Guam','غوام','Guam','Guam','Guam','Guam','&#1043;&#1091;&#1072;&#1084;','Guam','關島'),
'gg'=>array('Guernsey','Guernesey','Guernsey','Guernsey','Guernsey','Guernsey','Guernsey','غيرنسي','Guernsey','Guernsey','Guernsey','Guernsey','Guernsey','Gernzi','根西島'),
'gy'=>array('Guyana','Guyana','Guyana','Guyana','Gujana','Guyana','Guiana','غيانا','Guyana','Guyana','Guyana','Guyana','&#1043;&#1072;&#1081;&#1072;&#1085;&#1072;','Gijana','圭亞那'),
'gf'=>array('Franz&ouml;sisch-Guayana','Guyane','Guayana Francesa','Guyana Francese','Gujana Francuska','Francia Guyana','French Guiana','غيانا الفرنسية','Fransk Guiana','French Guiana','Frans-Guyana','Fransk Guyana','&#1043;&#1074;&#1080;&#1072;&#1085;&#1072;','Francuska Gijana','法屬圭亞那'),
'gp'=>array('Guadeloupe','Guadeloupe','Guadalupe','Guadalupa','Gwadelupa','Guadeloupe','Guadalupe','جوادلوب','Guadeloupe','Guadeloupe','Guadeloupe','Guadeloupe','&#1043;&#1074;&#1072;&#1076;&#1077;&#1083;&#1091;&#1087;&#1072;','Gvadelup','瓜德魯普'),
'gt'=>array('Guatemala','Guatemala','Guatemala','Guatemala','Gwatemala','Guatemala','Guatemala','غواتيمالا','Guatemala','Guatemala','Guatemala','Guatemala','&#1043;&#1074;&#1072;&#1090;&#1077;&#1084;&#1072;&#1083;&#1072;','Gvatemala','危地馬拉'),
'gn'=>array('Guinea','Guin&eacute;e','Guinea','Guinea','Gwinea','Guinea','Guin&eacute;','غينيا','Guinea','Guinea','Guinee','Guinea','&#1043;&#1074;&#1080;&#1085;&#1077;&#1103;','Gvineja','畿內亞'),
'gw'=>array('Guinea-Bissau','Guin&eacute;e-Bissau','Guinea-Bissau','Guinea-Bissau','Gwinea Bissau','Bissau-Guinea','Guin&eacute;-Bissau','غينيا بيساو','Guinea-Bissau','Guinea-Bissau','Guinee-Bissau','Guinea-Bissau','&#1043;&#1074;&#1080;&#1085;&#1077;&#1103;-&#1041;&#1080;&#1089;&#1072;&#1091;','Gvineja Bisao','畿內亞比紹'),
'gq'=>array('&Auml;quatorialguinea','Guin&eacute;e &eacute;quatoriale','Guinea Ecuatorial','Guinea Equatoriale','Gwinea R&oacute;wnikowa','Egyenl&iacute;t&#337;i-Guinea','Guin&eacute; Equatorial','غينيا الاستوائية','&AElig;kvatorialguinea','Equatorial Guinea','Equatoriaal-Guinea','Ekvatorial-Guinea','&#1069;&#1082;&#1074;&#1072;&#1090;&#1086;&#1088;&#1080;&#1072;&#1083;&#1100;&#1085;&#1072;&#1103; &#1043;&#1074;&#1080;&#1085;&#1077;&#1103;','Ekvatorijalna Gvineja','赤道畿內亞'),
'ht'=>array('Haiti','Ha&iuml;ti','Hait&iacute;','Haiti','Haiti','Haiti','Haiti','هايتي','Haiti','Haiti','Ha&iuml;ti','Haiti','&#1043;&#1072;&#1080;&#1090;&#1080;','Haiti','海地'),
'es'=>array('Spanien','Espagne','pain','Spagna','Hiszpania','Spanyolorsz&aacute;g','Espanha','إسبانيا','Spanien','Spain','Spanje','Spania','&#1048;&#1089;&#1087;&#1072;&#1085;&#1080;&#1103;','&Scaron;panija','西班牙'),
'nl'=>array('Niederlande','Pays-Bas','Pa&iacute;ses Bajos','Paesi Bassi','Holandia','Hollandia','Pa&iacute;ses Baixos','هولندا','Holland','Netherlands','Nederland','Nederland','&#1053;&#1080;&#1076;&#1077;&#1088;&#1083;&#1072;&#1085;&#1076;&#1099;','Holandija','荷蘭'),
'hn'=>array('Honduras','Honduras','Honduras','Honduras','Honduras','Honduras','Honduras','هندوراس','Honduras','Honduras','Honduras','Honduras','&#1043;&#1086;&#1085;&#1076;&#1091;&#1088;&#1072;&#1089;','Honduras','洪都拉斯'),
'hk'=>array('Hongkong','Hong Kong','Hong Kong','Hong Kong','Hongkong','Hongkong','Hong Kong','هونغ كونغ','ongkong','Hong Kong','Hongkong','Hongkong','&#1043;&#1086;&#1085;&#1082;&#1086;&#1085;&#1075;','Hongkong','香港'),
'in'=>array('Indien','Inde','India','India','Indie','India','&Iacute;ndia','الهند','Indien','India','India','India','&#1048;&#1085;&#1076;&#1080;&#1103;','Indija','印度'),
'id'=>array('Indonesien','Indon&eacute;sie','Indonesia','Indonesia','Indonezja','Indon&eacute;zia','Indon&eacute;sia','أندونيسيا','Indonesien','Indonesia','Indonesi&euml;','Indonesia','&#1048;&#1085;&#1076;&#1086;&#1085;&#1077;&#1079;&#1080;&#1103;','Indonezija','印尼'),
'iq'=>array('Irak','Irak','Irak','Iraq','Irak','Irak','Iraque','العراق','Irak','Iraq','Irak','Irak','&#1048;&#1088;&#1072;&#1082;','Irak','伊拉克'),
'ir'=>array('Iran','Iran','Ir&aacute;n','Iran','Iran','Ir&aacute;n','Ir&atilde;o','إيران','Iran','Iran','Iran','Iran','&#1048;&#1088;&#1072;&#1085;','Iran','伊朗'),
'ie'=>array('Irland','Irlande (pays)','Irlanda','Irlanda','Irlandia','&Iacute;rorsz&aacute;g','Irlanda','إيرلندا','Irland','Ireland','Ierland','Irland','&#1048;&#1088;&#1083;&#1072;&#1085;&#1076;&#1080;&#1103;','Irska','愛爾蘭'),
'is'=>array('Island','Islande','Islandia','Islanda','Islandia','Izland','Isl&acirc;ndia','أيسلندا','Island','Iceland','IJsland','Island','&#1048;&#1089;&#1083;&#1072;&#1085;&#1076;&#1080;&#1103;','Island','冰島'),
'il'=>array('Israel','Isra&euml;l','Israel','Israele','Izrael','Izrael','Israel','فلسطين','Israel','Israel','Isra&euml;l','Israel','&#1048;&#1079;&#1088;&#1072;&#1080;&#1083;&#1100;','Izrael','以色列'),
'jm'=>array('Jamaika','Jama&iuml;que','Jamaica','Giamaica','Jamajka','Jamaica','Jamaica','جامايكا','Jamaica','Jamaica','Jamaica','Jamaica','&#1071;&#1084;&#1072;&#1081;&#1082;&#1072;','Jamajka','牙買加'),
'jp'=>array('Japan','Japon','Jap&oacute;n','Giappone','Japonia','Jap&aacute;n','Jap&atilde;o','اليابان','Japan','Japan','Japan','Japan','&#1071;&#1087;&#1086;&#1085;&#1080;&#1103;','Japan','日本'),
'ye'=>array('Jemen','emen','Yemen','Yemen','Jemen','Jemen','I&eacute;men','اليمن','Yemen','Yemen','Jemen','Jemen','&#1049;&#1077;&#1084;&#1077;&#1085;','Jemen','也門'),
'je'=>array('Jersey','Jersey','Jersey','Jersey','Jersey','Jersey','Jersey','جيرسي','Jersey','Jersey','Jersey','Jersey','&#1044;&#1078;&#1077;&#1088;&#1089;&#1080;','D&#382;ersi','澤西'),
'jo'=>array('Jordanien','Jordanie','Jordania','Giordania','Jordania','Jord&aacute;nia','Jord&acirc;nia','الأردن','Jordan','Jordan','Jordani&euml;','Jordan','&#1048;&#1086;&#1088;&#1076;&#1072;&#1085;&#1080;&#1103;','Jordan','約旦'),
'ky'=>array('Kaimaninseln','&Icirc;les Ca&iuml;mans','Islas Caim&aacute;n','Isole Cayman','Kajmany','Kajm&aacute;n-szigetek','Ilhas Cayman','جزر كايمان','Cayman&oslash;erne','Cayman Islands','Kaaimaneilanden','Cayman&oslash;yene','&#1050;&#1072;&#1081;&#1084;&#1072;&#1085;&#1086;&#1074;&#1099; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Kajmanska Ostrva','開曼群島'),
'kh'=>array('Kambodscha','Cambodge','Camboya','Cambogia','Kambod&#380;a','Kambodzsa','Camboja','كمبوديا','Cambodja','Cambodia','Cambodja','Kambodsja','&#1050;&#1072;&#1084;&#1073;&#1086;&#1076;&#1078;&#1072;','Kambod&#382;a','柬埔寨'),
'cm'=>array('Kamerun','Cameroun','Camer&uacute;n','Camerun','Kamerun','Kamerun','Camar&otilde;es','الكاميرون','Cameroun','Cameroon','Kameroen','Kamerun','&#1050;&#1072;&#1084;&#1077;&#1088;&#1091;&#1085;','Kamerun','喀麥隆'),
'ca'=>array('Kanada','Canada','Canad&aacute;','Canada','Kanada','Kanada','Canad&aacute;','كندا','Canada','Canada','Canada','Canada','&#1050;&#1072;&#1085;&#1072;&#1076;&#1072;','Kanada','加拿大'),
'qa'=>array('Katar','Qatar','Catar','Qatar','Katar','Katar','Catar','قطر','Qatar','Qatar','Qatar','Qatar','&#1050;&#1072;&#1090;&#1072;&#1088;','Katar','卡塔爾'),
'kz'=>array('Kasachstan','Kazakhstan','Kazajist&aacute;n','Kazakistan','Kazachstan','Kazahszt&aacute;n','Cazaquist&atilde;o','كازاخستان','Kasakhstan','Kazakhstan','Kazachstan','Kasakhstan','&#1050;&#1072;&#1079;&#1072;&#1093;&#1089;&#1090;&#1072;&#1085;','Kazahstan','哈薩克'),
'ke'=>array('Kenia','Kenya','Kenia','Kenya','Kenia','Kenya','Qu&eacute;nia','كينيا','Kenya','Kenya','Kenia','Kenya','&#1050;&#1077;&#1085;&#1080;&#1103;','Kenija','肯雅'),
'kg'=>array('Kirgisistan','Kirghizistan','Kirguist&aacute;n','Kirghizistan','Kirgistan','Kirgiziszt&aacute;n','Quirguizist&atilde;o','قيرغيزستان','Kirgisistan','Kyrgyzstan','Kirgizi&euml;','Kirgisistan','&#1050;&#1080;&#1088;&#1075;&#1080;&#1079;&#1080;&#1103;','Kirgizija','吉爾吉斯'),
'ki'=>array('Kiribati','Kiribati','Kiribati','Kiribati','Kiribati','Kiribati','Quirib&aacute;ti','كيريباتي','Kiribati','Kiribati','Kiribati','Kiribati','&#1050;&#1080;&#1088;&#1080;&#1073;&#1072;&#1090;&#1080;','Kiribati','基里巴斯'),
'co'=>array('Kolumbien','Colombie','Colombia','Colombia','Kolumbia','Kolumbia','Col&ocirc;mbia','كولومبيا','Colombia','Colombia','Colombia','Colombia','&#1050;&#1086;&#1083;&#1091;&#1084;&#1073;&#1080;&#1103;','Kolumbija','哥倫比亞'),
'km'=>array('Komoren','Comores','Comoras','Comore','Komory','Comore-szigetek','Comores','جزر القمر','Comorerne','Comoros','Comoren','Komorene','&#1050;&#1086;&#1084;&#1086;&#1088;&#1099;','Komori','科摩羅'),
'cg'=>array('Kongo','Congo-Brazzaville / (Congo)','Rep&uacute;blica del Congo','Repubblica del Congo','Kongo','Kong&oacute;i K&ouml;zt&aacute;rsas&aacute;g (Kong&oacute;)','Congo-Brazzaville','الكونغو','Congo','Congo','Congo-Brazzaville','Republikken Kongo','&#1056;&#1077;&#1089;&#1087;&#1091;&#1073;&#1083;&#1080;&#1082;&#1072; &#1050;&#1086;&#1085;&#1075;&#1086;','Kongo','剛果'),
'kr'=>array('S&uuml;dkorea','Cor&eacute;e du Sud','Corea del Sur','Corea del Sud','Korea Po&#322;udniowa','D&eacute;l-Korea (Koreai K&ouml;zt&aacute;rsas&aacute;g)','Coreia do Sul','كوريا الجنوبية','Sydkorea','Korea','Zuid-Korea','S&oslash;r-Korea','&#1070;&#1078;&#1085;&#1072;&#1103; &#1050;&#1086;&#1088;&#1077;&#1103;','Ju&#382;na Koreja','南韓'),
'kp'=>array('Nordkorea','Cor&eacute;e du Nord','Corea del Norte','Corea del Nord','Korea P&oacute;&#322;nocna','&Eacute;szak-Korea (Koreai NDK)','Coreia do Norte','كوريا الشمالية','Nordkorea','Korea Democratic Peoples Republic of','Noord-Korea','Nord-Korea','&#1050;&#1053;&#1044;&#1056;','Severna Koreja','北韓'),
'cr'=>array('Costa Rica','Costa Rica','Costa Rica','Costa Rica','Kostaryka','Costa Rica','Costa Rica','كوستا ريكا','Costa Rica','Costa Rica','Costa Rica','Costa Rica','&#1050;&#1086;&#1089;&#1090;&#1072;-&#1056;&#1080;&#1082;&#1072;','Kostarika','哥斯達黎加'),
'cu'=>array('Kuba','Cuba','Cuba','Cuba','Kuba','Kuba','Cuba','كوبا','Cuba','Cuba','Cuba','Cuba','&#1050;&#1091;&#1073;&#1072;','Kuba','古巴'),
'kw'=>array('Kuwait','Kowe&iuml;t','Kuwait','Kuwait','Kuwejt','Kuvait','Kuwait','الكويت','Kuwait','Kuwait','Koeweit','Kuwait','&#1050;&#1091;&#1074;&#1077;&#1081;&#1090;','Kuvajt','科威特'),
'la'=>array('Laos','Laos','ao Peoples Democratic Republic','Laos','Laos','Laosz','Laos','لاوس','Laos','Lao Peoples Democratic Republic','Laos','Laos','&#1051;&#1072;&#1086;&#1089;','Laos','老撾'),
'ls'=>array('Lesotho','Lesotho','Lesoto','Lesotho','Lesotho','Lesotho','Lesoto','ليسوتو','Lesotho','Lesotho','Lesotho','Lesotho','&#1051;&#1077;&#1089;&#1086;&#1090;&#1086;','Lesoto','萊索托'),
'lb'=>array('Libanon','Liban','L&iacute;bano','Libano','Liban','Libanon','L&iacute;bano','لبنان','Libanon','Lebanon','Libanon','Libanon','&#1051;&#1080;&#1074;&#1072;&#1085;','Liban','黎巴嫩'),
'lr'=>array('Liberia','Liberia','Liberia','Liberia','Liberia','Lib&eacute;ria','Lib&eacute;ria','ليبيريا','Liberia','Liberia','Liberia','Liberia','&#1051;&#1080;&#1073;&#1077;&#1088;&#1080;&#1103;','Liberija','利比里亞'),
'ly'=>array('Libyen','Libye','Libia','Libia','Libia','L&iacute;bia','L&iacute;bia','ليبيا','Libyen','Libyan Arab Jamahiriya','Libi&euml;','Libya','&#1051;&#1080;&#1074;&#1080;&#1103;','Libija','利比亞'),
'li'=>array('Liechtenstein','Liechtenstein','Liechtenstein','Liechtenstein','Liechtenstein','Liechtenstein','Listenstaine','Liechtenstein','Liechtenstein','Liechtenstein','Liechtenstein','Liechtenstein','&#1051;&#1080;&#1093;&#1090;&#1077;&#1085;&#1096;&#1090;&#1077;&#1081;&#1085;','Lihten&scaron;tajn','列支敦士登'),
'lt'=>array('Litauen','Lituanie','Lituania','Lituania','Litwa','Litv&aacute;nia','Litu&acirc;nia','ليتوانيا','Litauen','Lithuania','Litouwen','Litauen','&#1051;&#1080;&#1090;&#1074;&#1072;','Litvanija','立陶宛'),
'lu'=>array('Luxemburg','Luxembourg (pays)','Luxemburgo','Lussemburgo','Luksemburg','Luxemburg','Luxemburgo','لوكسمبورغ','Luxembourg','Luxembourg','Luxemburg','Luxembourg','&#1051;&#1102;&#1082;&#1089;&#1077;&#1084;&#1073;&#1091;&#1088;&#1075;','Luksemburg','盧森堡'),
'mk'=>array('Mazedonien','Mac&eacute;doine (pays)','Rep&uacute;blica de Macedonia','Macedonia','Macedonia','Maced&oacute;nia','Maced&oacute;nia','مقدونيا','Makedonien','Macedonia','Macedoni&euml;','Makedonia','&#1052;&#1072;&#1082;&#1077;&#1076;&#1086;&#1085;&#1080;&#1103;','Makedonija','馬其頓'),
'mg'=>array('Madagaskar','Madagascar','Madagascar','Madagascar','Madagaskar','Madagaszk&aacute;r','Madag&aacute;scar','مدغشقر','Madagaskar','Madagascar','Madagaskar','Madagaskar','&#1052;&#1072;&#1076;&#1072;&#1075;&#1072;&#1089;&#1082;&#1072;&#1088;','Madagaskar','馬達加斯加'),
'yt'=>array('Mayotte','Mayotte','Mayotte','Mayotte','Majotta','Mayotte','Mayotte','مايوت','Mayotte','Mayotte','Mayotte','Mayotte','&#1052;&#1072;&#1081;&#1086;&#1090;&#1090;&#1072;','Majot','美亞特'),
'mo'=>array('Macao','Macao','Macao','Macao','Makau','Maka&oacute;','Macau','ماكاو','Macao','Macao','Macau','Macao','&#1052;&#1072;&#1082;&#1072;&#1086;','Makao','澳門'),
'mw'=>array('Malawi','Malawi','Malaui','Malawi','Malawi','Malawi','Mal&aacute;vi','ملاوي','Malawi','Malawi','Malawi','Malawi','&#1052;&#1072;&#1083;&#1072;&#1074;&#1080;','Malavi','馬拉維'),
'mv'=>array('Malediven','Maldives','Maldivas','Maldive','Malediwy','Mald&iacute;v-szigetek','Maldivas','جزر المالديف','Maldiverne','Maldives','Maldiven','Maldivene','&#1052;&#1072;&#1083;&#1100;&#1076;&#1080;&#1074;&#1099;','Maldivi','馬爾代夫'),
'my'=>array('Malaysia','Malaisie','Malasia','Malesia','Malezja','Malajzia','Mal&aacute;sia','ماليزيا','Malaysia','Malaysia','Maleisi&euml;','Malaysia','&#1052;&#1072;&#1083;&#1072;&#1081;&#1079;&#1080;&#1103;','Malezija','馬來西亞'),
'ml'=>array('Mali','Mali','Mal&iacute;','Mali','Mali','Mali','Mali','مالي','Mali','Mali','Mali','Mali','&#1052;&#1072;&#1083;&#1080;','Mali','馬里'),
'mt'=>array('Malta','Malte','Malta','Malta','Malta','M&aacute;lta','Malta','مالطا','Malta','Malta','Malta','Malta','&#1052;&#1072;&#1083;&#1100;&#1090;&#1072;','Malta','馬爾他'),
'mp'=>array('N&ouml;rdliche Marianen','&Icirc;les Mariannes du Nord','Islas Marianas del Norte','Isole Marianne Settentrionali','Mariany P&oacute;&#322;nocne','&Eacute;szaki-Mariana-szigetek','Northern Mariana Islands','جزر ماريانا الشمالية','Nordmarianerne','Northern Mariana Islands','Noordelijke Marianen','Nord-Marianene','&#1057;&#1077;&#1074;&#1077;&#1088;&#1085;&#1099;&#1077; &#1052;&#1072;&#1088;&#1080;&#1072;&#1085;&#1089;&#1082;&#1080;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Severna Marijanska Ostrva','北馬里亞納群島'),
'ma'=>array('Marokko','Maroc','Marruecos','Marocco','Maroko','Marokk&oacute;','Marrocos','المغرب','Marokko','Morocco','Marokko','Marokko','&#1052;&#1072;&#1088;&#1086;&#1082;&#1082;&#1086;','Maroko','摩洛哥'),
'mq'=>array('Martinique','Martinique','Martinica','Martinica','Martynika','Martinique','Martinica','مارتينيك','Martinique','Martinique','Martinique','Martinique','&#1052;&#1072;&#1088;&#1090;&#1080;&#1085;&#1080;&#1082;&#1072;','Martinik','馬丁尼克'),
'mr'=>array('Mauretanien','Mauritanie','Mauritania','Mauritania','Mauretania','Maurit&aacute;nia','Maurit&acirc;nia','موريتانيا','Mauretanien','Mauritania','Mauritani&euml;','Mauritania','&#1052;&#1072;&#1074;&#1088;&#1080;&#1090;&#1072;&#1085;&#1080;&#1103;','Mauritanija','毛里塔尼亞'),
'mu'=>array('Mauritius','Maurice (pays)','Mauricio','Mauritius','Mauritius','Mauritius','Maur&iacute;cia','موريشيوس','Mauritius','Mauritius','Mauritius','Mauritius','&#1052;&#1072;&#1074;&#1088;&#1080;&#1082;&#1080;&#1081;','Mauricijus','毛里裘斯'),
'mx'=>array('Mexiko','Mexique','M&eacute;xico','Messico','Meksyk','Mexik&oacute;','M&eacute;xico','المكسيك','Mexico','Mexico','Mexico','Mexico','&#1052;&#1077;&#1082;&#1089;&#1080;&#1082;&#1072;','Meksiko','墨西哥'),
'fm'=>array('Mikronesien','Micron&eacute;sie (pays)','Micronesia','Micronesia','Mikronezja','Mikron&eacute;zia','Micron&eacute;sia','ميكرونيزيا','Mikronesien','Micronesia','Micronesia','Mikronesiaf&oslash;derasjonen','&#1052;&#1080;&#1082;&#1088;&#1086;&#1085;&#1077;&#1079;&#1080;&#1103;','Mikronezija','密克羅尼西亞'),
'mc'=>array('Monaco','Monaco','M&oacute;naco','Monaco','Monako','Monaco','M&oacute;naco','موناكو','Monaco','Monaco','Monaco','Monaco','&#1052;&#1086;&#1085;&#1072;&#1082;&#1086;','Monako','摩納哥'),
'mn'=>array('Mongolei','Mongolie','Mongolia','Mongolia','Mongolia','Mong&oacute;lia','Mong&oacute;lia','منغوليا','Mongoliet','Mongolia','Mongoli&euml;','Mongolia','&#1052;&#1086;&#1085;&#1075;&#1086;&#1083;&#1080;&#1103;','Mongolija','蒙古'),
'ms'=>array('Montserrat','Montserrat','Montserrat','Montserrat','Montserrat','Montserrat (Egyes&uuml;lt Kir&aacute;lys&aacute;g)','Montserrat','مونتسيرات','Montserrat','Montserrat','Montserrat','Montserrat','&#1052;&#1086;&#1085;&#1090;&#1089;&#1077;&#1088;&#1088;&#1072;&#1090;','Monserat','蒙瑟拉特島'),
'mz'=>array('Mosambik','Mozambique','Mozambique','Mozambico','Mozambik','Mozambik','Mo&ccedil;ambique','موزامبيق','Mozambique','Mozambique','Mozambique','Mosambik','&#1052;&#1086;&#1079;&#1072;&#1084;&#1073;&#1080;&#1082;','Mozambik','莫三鼻給'),
'md'=>array('Moldawien','Moldavie','Moldavia','Moldavia','Mo&#322;dawia','Moldova','Mold&aacute;via','مولدافيا','Moldova','Moldova','Moldavi&euml;','Moldova','&#1052;&#1086;&#1083;&#1076;&#1072;&#1074;&#1080;&#1103;','Moldavija','摩爾多瓦'),
'na'=>array('Namibia','Namibie','amibia','Namibia','Namibia','Nam&iacute;bia','Nam&iacute;bia','ناميبيا','Namibia','Namibia','Namibi&euml;','Namibia','&#1053;&#1072;&#1084;&#1080;&#1073;&#1080;&#1103;','Namibija','納米比亞'),
'nr'=>array('Nauru','Nauru','Nauru','Nauru','Nauru','Nauru','Nauru','ناورو','Nauru','Nauru','Nauru','Nauru','&#1053;&#1072;&#1091;&#1088;&#1091;','Nauru','瑙魯'),
'np'=>array('Nepal','N&eacute;pal','Nepal','Nepal','Nepal','Nep&aacute;l','Nepal','نيبال','Nepal','Nepal','Nepal','Nepal','&#1053;&#1077;&#1087;&#1072;&#1083;','Nepal','尼泊爾'),
'de'=>array('Deutschland','Allemagne','Alemania','Germania','Niemcy','N&eacute;metorsz&aacute;g','Alemanha','ألمانيا','Tyskland','Germany','Duitsland','Tyskland','&#1043;&#1077;&#1088;&#1084;&#1072;&#1085;&#1080;&#1103;','Nema&#269;ka','德國'),
'ne'=>array('Niger','Niger','N&iacute;ger','Niger','Niger','Niger','N&iacute;ger','النيجر','Niger','Niger','Niger','Niger','&#1053;&#1080;&#1075;&#1077;&#1088;','Niger','尼日爾'),
'ng'=>array('Nigeria','Nigeria','igeria','Nigeria','Nigeria','Nig&eacute;ria','Nig&eacute;ria','نيجيريا','Nigeria','Nigeria','Nigeria','Nigeria','&#1053;&#1080;&#1075;&#1077;&#1088;&#1080;&#1103;','Nigerija','尼日利亞'),
'ni'=>array('Nicaragua','Nicaragua','icaragua','Nicaragua','Nikaragua','Nicaragua','Nicar&aacute;gua','نيكاراغوا','Nicaragua','Nicaragua','Nicaragua','Nicaragua','&#1053;&#1080;&#1082;&#1072;&#1088;&#1072;&#1075;&#1091;&#1072;','Nikaragva','尼加拉瓜'),
'nu'=>array('Niue','Niue','Niue','Niue','Niue','Niue','Niue','نيوي','Niue','Niue','Niue','Niue','&#1053;&#1080;&#1091;&#1101;','Niue','紐埃'),
'nf'=>array('Norfolkinsel','Norfolk','Norfolk','Isola Norfolk','Norfolk','Norfolk-sziget','Norfolk Island','نورفولك','Norfolk Island','Norfolk Island','Norfolk','Norfolk&oslash;ya','&#1054;&#1089;&#1090;&#1088;&#1086;&#1074; &#1053;&#1086;&#1088;&#1092;&#1086;&#1083;&#1082;','Norfolk','諾福克島'),
'no'=>array('Norwegen','Norv&egrave;ge','Noruega','Norvegia','Norwegia','Norv&eacute;gia','Noruega','النرويج','Norge','Norway','Noorwegen','Norge','&#1053;&#1086;&#1088;&#1074;&#1077;&#1075;&#1080;&#1103;','Norve&scaron;ka','挪威'),
'nc'=>array('Neukaledonien','Nouvelle-Cal&eacute;donie','Nueva Caledonia','Nuova Caledonia','Nowa Kaledonia','&Uacute;j-Kaled&oacute;nia','New Caledonia','كاليدونيا الجديدة','Ny Kaledonien','New Caledonia','Nieuw-Caledoni&euml;','Ny-Caledonia','&#1053;&#1086;&#1074;&#1072;&#1103; &#1050;&#1072;&#1083;&#1077;&#1076;&#1086;&#1085;&#1080;&#1103;','Nova Kaledonija','新喀里多尼亞'),
'nz'=>array('Neuseeland','Nouvelle-Z&eacute;lande','Nueva Zelanda','Nuova Zelanda','Nowa Zelandia','&Uacute;j-Z&eacute;land','Nova Zel&acirc;ndia','نيوزيلندا','New Zealand','New Zealand','Nieuw-Zeeland','New Zealand','&#1053;&#1086;&#1074;&#1072;&#1103; &#1047;&#1077;&#1083;&#1072;&#1085;&#1076;&#1080;&#1103;','Novi Zeland','紐西蘭'),
'om'=>array('Oman','Oman','Om&aacute;n','Oman','Oman','Om&aacute;n','Om&atilde;','عمان','Oman','Oman','Oman','Oman','&#1054;&#1084;&#1072;&#1085;','Oman','阿曼'),
'pk'=>array('Pakistan','Pakistan','Pakist&aacute;n','Pakistan','Pakistan','Pakiszt&aacute;n','Paquist&atilde;o','باكستان','Pakistan','Pakistan','Pakistan','Pakistan','&#1055;&#1072;&#1082;&#1080;&#1089;&#1090;&#1072;&#1085;','Pakistan','巴基斯坦'),
'pw'=>array('Palau','Palaos','Palaos','Palau','Palau','Palau','Palau','بالاو','Palau','Palau','Palau','Palau','&#1055;&#1072;&#1083;&#1072;&#1091;','Palau','帛琉'),
'ps'=>array('Pal&auml;stinensische Autonomiegebiete','Palestine','Autoridad Nacional Palestina','Autorit&agrave; Nazionale Palestinese','Palestyna','Palesztina','Palestinian Territory','فلسطين','Pal&aelig;stina','Palestinian Territory','Palestijnse Autoriteit','Palestina','&#1055;&#1072;&#1083;&#1077;&#1089;&#1090;&#1080;&#1085;&#1089;&#1082;&#1072;&#1103; &#1085;&#1072;&#1094;&#1080;&#1086;&#1085;&#1072;&#1083;&#1100;&#1085;&#1072;&#1103; &#1072;&#1076;&#1084;&#1080;&#1085;&#1080;&#1089;&#1090;&#1088;&#1072;&#1094;&#1080;&#1103;','Palestina','巴勒斯坦'),
'pa'=>array('Panama','Panam&aacute;','Panam&aacute;','Panam&aacute;','Panama','Panama','Panam&aacute;','بنما','Panama','Panama','Panama','Panama','&#1055;&#1072;&#1085;&#1072;&#1084;&#1072;','Panama','巴拿馬'),
'pg'=>array('Papua-Neuguinea','Papouasie-Nouvelle-Guin&eacute;e','Pap&uacute;a Nueva Guinea','Papua Nuova Guinea','Papua-Nowa Gwinea','P&aacute;pua &Uacute;j-Guinea','Papua-Nova Guin&eacute;','بابوا غينيا الجديدة','Papua Ny Guinea','Papua New Guinea','Papoea-Nieuw-Guinea','Papua Ny-Guinea','&#1055;&#1072;&#1087;&#1091;&#1072; — &#1053;&#1086;&#1074;&#1072;&#1103; &#1043;&#1074;&#1080;&#1085;&#1077;&#1103;','Papua Nova Gvineja','巴布亞新畿內亞'),
'py'=>array('Paraguay','Paraguay','Paraguay','Paraguay','Paragwaj','Paraguay','Paraguai','باراغواي','Paraguay','Paraguay','Paraguay','Paraguay','&#1055;&#1072;&#1088;&#1072;&#1075;&#1074;&#1072;&#1081;','Paragvaj','巴拉圭'),
'pe'=>array('Peru','P&eacute;rou','eru','Per&ugrave;','Peru','Peru','Peru','بيرو','Peru','Peru','Peru','Peru','&#1055;&#1077;&#1088;&#1091;','Peru','秘魯'),
'pn'=>array('Pitcairninseln','&Icirc;les Pitcairn','Islas Pitcairn','Isole Pitcairn','Pitcairn','Pitcairn-szigetek','Pitcairn','بيتكيرن','Pitcairn','Pitcairn','Pitcairneilanden','Pitcairn&oslash;yene','&#1054;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072; &#1055;&#1080;&#1090;&#1082;&#1101;&#1088;&#1085;','Ostrva Pitkern','皮特凱恩島'),
'pf'=>array('Franz&ouml;sisch-Polynesien','Polyn&eacute;sie fran&ccedil;aise','Polinesia Francesa','Polinesia francese','Polinezja Francuska','Francia Polin&eacute;zia','Polin&eacute;sia Francesa','بولينيزيا الفرنسية','Fransk Polynesien','French Polynesia','Frans-Polynesi&euml;','Fransk Polynesia','&#1060;&#1088;&#1072;&#1085;&#1094;&#1091;&#1079;&#1089;&#1082;&#1072;&#1103; &#1055;&#1086;&#1083;&#1080;&#1085;&#1077;&#1079;&#1080;&#1103;','Francuska Polinezija','法屬玻里尼西亞'),
'pl'=>array('Polen','Pologne','Polonia','Polonia','Polska','Lengyelorsz&aacute;g','Pol&oacute;nia','بولندا','Polen','Poland','Polen','Polen','&#1055;&#1086;&#1083;&#1100;&#1096;&#1072;','Poljska','波蘭'),
'pr'=>array('Puerto Rico','Porto Rico','Puerto Rico','Porto Rico','Portoryko','Puerto Rico','Porto Rico','بورتوريكو','Puerto Rico','Puerto Rico','Puerto Rico','Puerto Rico','&#1055;&#1091;&#1101;&#1088;&#1090;&#1086;-&#1056;&#1080;&#1082;&#1086;','Portoriko','波多黎各'),
'pt'=>array('Portugal','Portugal','Portugal','Portogallo','Portugalia','Portug&aacute;lia','Portugal','البرتغال','Portugal','Portugal','Portugal','Portugal','&#1055;&#1086;&#1088;&#1090;&#1091;&#1075;&#1072;&#1083;&#1080;&#1103;','Portugal','葡萄牙'),
'za'=>array('S&uuml;dafrika','Afrique du Sud','Sud&aacute;frica','Sudafrica','Republika Po&#322;udniowej Afryki','D&eacute;l-afrikai K&ouml;zt&aacute;rsas&aacute;g','&Aacute;frica do Sul','جنوب أفريقيا','Sydafrika','South Africa','Zuid-Afrika','S&oslash;r-Afrika','&#1070;&#1040;&#1056;','Ju&#382;noafri&#269;ka Republika','南非'),
'cv'=>array('Kap Verde','Cap-Vert','Cabo Verde','Capo Verde','Republika Zielonego Przyl&#261;dka','Z&ouml;ld-foki K&ouml;zt&aacute;rsas&aacute;g','Cabo Verde','كابو فيردي','Kap Verde','Cape Verde','Kaapverdi&euml;','Kapp Verde','&#1050;&#1072;&#1073;&#1086;-&#1042;&#1077;&#1088;&#1076;&#1077;','Zelenortska Ostrva','佛得角'),
'cf'=>array('Zentralafrikanische Republik','R&eacute;publique centrafricaine','Rep&uacute;blica Centroafricana','Repubblica Centrafricana','Republika &#346;rodkowoafryka&#324;ska','K&ouml;z&eacute;p-Afrika','Rep&uacute;blica Centro-Africana','جمهورية أفريقيا الوسطى','Centralafrikanske Republik','Central African Republic','Centraal-Afrikaanse Republiek','Den sentralafrikanske republikk','&#1062;&#1040;&#1056;','Centralnoafri&#269;ka Republika','中非'),
're'=>array('R&eacute;union','La R&eacute;union','Reuni&oacute;n','Riunione','Reunion','R&eacute;union R&eacute;union','Reunion','ريونيون','R&eacute;union','R&eacute;union','R&eacute;union','R&eacute;union','&#1056;&#1077;&#1102;&#1085;&#1100;&#1086;&#1085;','Reunion','留尼旺'),
'ru'=>array('Russische F&ouml;deration','Russie','Rusia','Russia','Rosja','Oroszorsz&aacute;g','R&uacute;ssia','روسيا','Rusland','Russian Federation','Rusland','Russland','&#1056;&#1086;&#1089;&#1089;&#1080;&#1103;','Rusija','俄羅斯'),
'ro'=>array('Rum&auml;nien','Roumanie','Rumania','Romania','Rumunia','Rom&aacute;nia','Rom&eacute;nia','رومانيا','Rum&aelig;nien','Romania','Roemeni&euml;','Romania','&#1056;&#1091;&#1084;&#1099;&#1085;&#1080;&#1103;','Rumunija','羅馬尼亞'),
'rw'=>array('Ruanda','Rwanda','Ruanda','Ruanda','Rwanda','Ruanda','Ruanda','رواندا','Rwanda','Rwanda','Rwanda','Rwanda','&#1056;&#1091;&#1072;&#1085;&#1076;&#1072;','Ruanda','盧旺達'),
'eh'=>array('Westsahara','ahara occidental','Rep&uacute;blica &Aacute;rabe Saharaui Democr&aacute;tica','Sahara Occidentale','Sahara Zachodnia','Nyugat-Szahara','Western Sahara','الصحراء الغربية','Vestsahara','Western Sahara','Westelijke Sahara','Vest-Sahara','&#1047;&#1072;&#1087;&#1072;&#1076;&#1085;&#1072;&#1103; &#1057;&#1072;&#1093;&#1072;&#1088;&#1072;','Zapadna Sahara','西撒哈拉'),
'kn'=>array('St. Kitts und Nevis','Saint-Christophe-et-Ni&eacute;v&egrave;s','San Crist&oacute;bal y Nieves','Saint Kitts e Nevis','Saint Kitts i Nevis','Saint Kitts &eacute;s Nevis','S&atilde;o Crist&oacute;v&atilde;o e Neves','شارع كيتس نيفيس اوند','Saint Kitts og Nevis','Saint Kitts and Nevis','Saint Kitts en Nevis','Saint Kitts og Nevis','&#1057;&#1077;&#1085;&#1090;-&#1050;&#1080;&#1090;&#1089; &#1080; &#1053;&#1077;&#1074;&#1080;&#1089;','Sveti Kits i Nevis','聖基茨島及尼維斯島'),
'lc'=>array('St. Lucia','Sainte-Lucie','Santa Luc&iacute;a','Santa Lucia','Saint Lucia','Saint Lucia','Santa L&uacute;cia','سانتا لوسيا','Saint Lucia','Saint Lucia','Saint Lucia','Saint Lucia','&#1057;&#1077;&#1085;&#1090;-&#1051;&#1102;&#1089;&#1080;&#1103;','Sveta Lucija','聖盧西亞'),
'vc'=>array('St. Vincent und die Grenadinen','Saint-Vincent-et-les-Grenadines','San Vicente y las Granadinas','Saint Vincent e Grenadine','Saint Vincent i Grenadyny','Saint Vincent &eacute;s a Grenadine-szigetek','S&atilde;o Vicente e Granadinas','سانت فنسنت وغرينادين','Saint Vincent og Grenadinerne','Saint Vincent and the Grenadines','Saint Vincent en de Grenadines','Saint Vincent og Grenadinene','&#1057;&#1077;&#1085;&#1090;-&#1042;&#1080;&#1085;&#1089;&#1077;&#1085;&#1090; &#1080; &#1043;&#1088;&#1077;&#1085;&#1072;&#1076;&#1080;&#1085;&#1099;','Sveti Vinsent i Grenadini','聖文森及格瑞那丁'),
'bl'=>array('Saint-Barth&eacute;lemy','Saint-Barth&eacute;lemy','San Bartolom&eacute;','Saint-Barth&eacute;lemy','Saint-Barth&eacute;lemy','Saint Barth&eacute;lemy','Saint-Barth&eacute;lemy','سانت بارتيليمي','Saint Barth&eacute;lemy','Saint Barth&eacute;lemy','Saint-Barth&eacute;lemy','Saint Barth&eacute;lemy','&#1057;&#1077;&#1085;-&#1041;&#1072;&#1088;&#1090;&#1077;&#1083;&#1100;&#1084;&#1080;','Saint Barth&eacute;lemy','聖巴托洛繆島'),
'mf'=>array('Saint-Martin','Saint-Martin (Antilles fran&ccedil;aises)','San Mart&iacute;n','Saint-Martin','Saint-Martin','Saint Martin','S&atilde;o Martinho','Saint Pierre and Miquelon','Saint Martin','Saint Martin','Sint-Maarten','Saint Martin','&#1057;&#1077;&#1085;-&#1052;&#1072;&#1088;&#1090;&#1077;&#1085;','Saint Martin','法屬聖馬田'),
'pm'=>array('Saint-Pierre und Miquelon','Saint-Pierre-et-Miquelon','San Pedro y Miquel&oacute;n','Saint-Pierre e Miquelon','Saint-Pierre i Miquelon','Saint-Pierre &eacute;s Miquelon','Saint-Pierre e Miquelon','El Salvador','Saint-Pierre og Miquelon','Saint Pierre and Miquelon','Saint-Pierre en Miquelon','Saint-Pierre og Miquelon','&#1057;&#1077;&#1085;-&#1055;&#1100;&#1077;&#1088; &#1080; &#1052;&#1080;&#1082;&#1077;&#1083;&#1086;&#1085;','Sveti Pjer i Mikelon','聖皮埃蘭及密克隆群島'),
'sv'=>array('El Salvador','Salvador','El Salvador','El Salvador','Salwador','El Salvador','Salvador','Samoa','El Salvador','El Salvador','El Salvador','El Salvador','&#1057;&#1072;&#1083;&#1100;&#1074;&#1072;&#1076;&#1086;&#1088;','Salvador','薩爾瓦多'),
'ws'=>array('Samoa','Samoa','Samoa','Samoa','Samoa','Szamoa','Samoa','ساموا','Samoa','Samoa','Samoa','Samoa','&#1057;&#1072;&#1084;&#1086;&#1072;','Samoa','薩摩亞'),
'as'=>array('Amerikanisch-Samoa','Samoa am&eacute;ricaines','merican Samoa','Samoa americane','Samoa Ameryka&#324;skie','Amerikai Szamoa','American Samoa','ميريكان ساموا','Amerikansk Samoa','American Samoa','Amerikaans-Samoa','Amerikansk Samoa','&#1040;&#1084;&#1077;&#1088;&#1080;&#1082;&#1072;&#1085;&#1089;&#1082;&#1086;&#1077; &#1057;&#1072;&#1084;&#1086;&#1072;','Ameri&#269;ka Samoa','美屬薩摩亞'),
'sm'=>array('San Marino','Saint-Marin','San Marino','San Marino','San Marino','San Marino','S&atilde;o Marinho','سان مارينو','San Marino','San Marino','San Marino','San Marino','&#1057;&#1072;&#1085;-&#1052;&#1072;&#1088;&#1080;&#1085;&#1086;','San Marino','聖馬力諾'),
'sn'=>array('Senegal','S&eacute;n&eacute;gal','Senegal','Senegal','Senegal','Szeneg&aacute;l','Senegal','السنغال','Senegal','Senegal','Senegal','Senegal','&#1057;&#1077;&#1085;&#1077;&#1075;&#1072;&#1083;','Senegal','塞內加爾'),
'rs'=>array('Serbien','Serbie','Serbia','Serbia','Serbia','Szerbia','S&eacute;rvia','صربيا','Serbien','Serbia','Servi&euml;','Serbia','&#1057;&#1077;&#1088;&#1073;&#1080;&#1103;','Srbija','塞爾維亞'),
'sc'=>array('Seychellen','Seychelles','Seychelles','Seychelles','Seszele','Seychelle-szigetek','Seicheles','سيشيل','Seychellerne','Seychelles','Seychellen','Seychellene','&#1057;&#1077;&#1081;&#1096;&#1077;&#1083;&#1100;&#1089;&#1082;&#1080;&#1077; &#1054;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Sej&scaron;eli','塞舌爾'),
'sl'=>array('Sierra Leone','Sierra Leone','Sierra Leona','Sierra Leone','Sierra Leone','Sierra Leone','Serra Leoa','Sierra Leone','Sierra Leone','Sierra Leone','Sierra Leone','Sierra Leone','&#1057;&#1100;&#1077;&#1088;&#1088;&#1072;-&#1051;&#1077;&#1086;&#1085;&#1077;','Sijera Leone','塞拉利昂'),
'sg'=>array('Singapur','Singapour','Singapur','Singapore','Singapur','Szingap&uacute;r','Singapura','سنغافورة','Singapore','Singapore','Singapore','Singapore','&#1057;&#1080;&#1085;&#1075;&#1072;&#1087;&#1091;&#1088;','Singapur','新加坡'),
'sx'=>array('Sint Maarten','Saint-Martin','int Maarten','int Maarten','Sint Maarten','Sint Maarten','Sint Maarten','سانت مارتن','Sint Maarten','Sint Maarten','Sint Maarten','Sint Maarten','&#1057;&#1080;&#1085;&#1090;-&#1052;&#1072;&#1088;&#1090;&#1077;&#1085; (&#1053;&#1080;&#1076;&#1077;&#1088;&#1083;&#1072;&#1085;&#1076;&#1099;)','Sint Maarten','Sint Maarten'),
'so'=>array('Somalia','Somalie','Somalia','Somalia','Somalia','Szom&aacute;lia','Som&aacute;lia','الصومال','Somalia','Somalia','Somali&euml;','Somalia','&#1057;&#1086;&#1084;&#1072;&#1083;&#1080;','Somalija','索馬里'),
'lk'=>array('Sri Lanka','Sri Lanka','Sri Lanka','Sri Lanka','Sri Lanka','Sr&iacute; Lanka','Sri Lanca','سري لانكا','Sri Lanka','Sri Lanka','Sri Lanka','Sri Lanka','&#1064;&#1088;&#1080;-&#1051;&#1072;&#1085;&#1082;&#1072;','&Scaron;ri Lanka','斯里蘭卡'),
'us'=>array('Vereinigte Staaten von Amerika','&Eacute;tats-Unis','ri Lanka','Stati Uniti dAmerica','Stany Zjednoczone','Amerikai Egyes&uuml;lt &Aacute;llamok','Estados Unidos','الولايات المتحدة الأمريكية','USA','United States','Verenigde Staten','Amerikas forente stater','&#1057;&#1064;&#1040;','Sjedinjene Ameri&#269;ke Dr&#382;ave','美國'),
'sz'=>array('Swasiland','Swaziland','Suazilandia','Swaziland','Suazi','Szv&aacute;zif&ouml;ld','Suazil&acirc;ndia','سوازيلاند','Swaziland','Swaziland','Swaziland','Swaziland','&#1057;&#1074;&#1072;&#1079;&#1080;&#1083;&#1077;&#1085;&#1076;','Svazilend','斯威士蘭'),
'sd'=>array('Sudan','Soudan','Sud&aacute;n','Sudan','Sudan','Szud&aacute;n','Sud&atilde;o','سودان','Sudan','Sudan','Soedan','Sudan','&#1057;&#1091;&#1076;&#1072;&#1085;','Sudan','蘇丹'),
'sr'=>array('Suriname','Suriname','Surinam','Suriname','Surinam','Suriname','Suriname','سورينام','Surinam','Suriname','Suriname','Surinam','&#1057;&#1091;&#1088;&#1080;&#1085;&#1072;&#1084;','Surinam','蘇里南'),
'sj'=>array('Svalbard und Jan Mayen','Svalbard et &icirc;le Jan Mayen','Svalbard y Jan Mayen','Svalbard e Jan Mayen','Svalbard i Jan Mayen','Svalbard (Spitzberg&aacute;k) &eacute;s Jan Mayen','Svalbard','ذ سفالبارد جان ماين','Norge Svalbard og Jan Mayen','Svalbard and Jan Mayen','Spitsbergen en Jan Mayen','Svalbard og Jan Mayen','&#1064;&#1087;&#1080;&#1094;&#1073;&#1077;&#1088;&#1075;&#1077;&#1085; &#1080; &#1071;&#1085;-&#1052;&#1072;&#1081;&#1077;&#1085;','Svalbard i Jan Majen','斯瓦巴及尖棉島'),
'sy'=>array('Syrien','Syrie','Siria','Siria','Syria','Sz&iacute;ria','S&iacute;ria','سوريا','Syrien','Syrian Arab Republic','Syri&euml;','Syria','&#1057;&#1080;&#1088;&#1080;&#1103;','Sirija','敘利亞'),
'ch'=>array('Schweiz','Suisse','Suiza','Svizzera','Szwajcaria','Sv&aacute;jc','Su&iacute;&ccedil;a','سويسرا','Schweiz','Switzerland','Zwitserland','Sveits','&#1064;&#1074;&#1077;&#1081;&#1094;&#1072;&#1088;&#1080;&#1103;','&Scaron;vajcarska','瑞士'),
'se'=>array('Schweden','Su&egrave;de','Suecia','Svezia','Szwecja','Sv&eacute;dorsz&aacute;g','Su&eacute;cia','السويد','Sverige','Sweden','Zweden','Sverige','&#1064;&#1074;&#1077;&#1094;&#1080;&#1103;','&Scaron;vedska','瑞典'),
'sk'=>array('Slowakei','Slovaquie','Eslovaquia','Slovacchia','S&#322;owacja','Szlov&aacute;kia','Eslov&aacute;quia','سلوفاكيا','Slovakiet','Slovakia','Slowakije','Slovakia','&#1057;&#1083;&#1086;&#1074;&#1072;&#1082;&#1080;&#1103;','Slova&#269;ka','斯洛伐克'),
'si'=>array('Slowenien','Slov&eacute;nie','Eslovenia','Slovenia','S&#322;owenia','Szlov&eacute;nia','Eslov&eacute;nia','سلوفينيا','Slovenien','Slovenia','Sloveni&euml;','Slovenia','&#1057;&#1083;&#1086;&#1074;&#1077;&#1085;&#1080;&#1103;','Slovenija','斯洛文尼亞'),
'tj'=>array('Tadschikistan','Tadjikistan','Tayikist&aacute;n','Tagikistan','Tad&#380;ykistan','T&aacute;dzsikiszt&aacute;n','Tajiquist&atilde;o','طاجيكستان','Tadsjikistan','Tajikistan','Tadzjikistan','Tadsjikistan','&#1058;&#1072;&#1076;&#1078;&#1080;&#1082;&#1080;&#1089;&#1090;&#1072;&#1085;','Tad&#382;ikistan','塔吉克'),
'th'=>array('Thailand','Tha&iuml;lande','Tailandia','Thailandia','Tajlandia','Thaif&ouml;ld','Tail&acirc;ndia','تايلاند','Thailand','Thailand','Thailand','Thailand','&#1058;&#1072;&#1080;&#1083;&#1072;&#1085;&#1076;','Tajland','泰國'),
'tw'=>array('Taiwan','Ta&iuml;wan / (R&eacute;publique de Chine (Ta&iuml;wan))','Taiw&aacute;n','Taiwan','Tajwan','Tajvan','Taiwan','تايوان','Republikken Kina Taiwan','Taiwan','Taiwan','Taiwan','&#1050;&#1080;&#1090;&#1072;&#1081;&#1089;&#1082;&#1072;&#1103; &#1056;&#1077;&#1089;&#1087;&#1091;&#1073;&#1083;&#1080;&#1082;&#1072;','Tajvan','中華民國 臺灣'),
'tz'=>array('Tansania','Tanzanie','Tanzania','Tanzania','Tanzania','Tanz&aacute;nia','Tanz&acirc;nia','تنزانيا','Tanzania','Tanzania','Tanzania','Tanzania','&#1058;&#1072;&#1085;&#1079;&#1072;&#1085;&#1080;&#1103;','Tanzanija','坦桑尼亞'),
'tl'=>array('Westtimor','Timor oriental','Timor Oriental','Timor Est','Timor Wschodni','Kelet-Timor','Timor Leste','تيمور الشرقية','&Oslash;sttimor','Timor-Leste','Oost-Timor','&Oslash;st-Timor','&#1042;&#1086;&#1089;&#1090;&#1086;&#1095;&#1085;&#1099;&#1081; &#1058;&#1080;&#1084;&#1086;&#1088;','Isto&#269;ni Timor','東帝汶'),
'tg'=>array('Togo','Togo','Togo','Togo','Togo','Togo','Togo','توغو','Togo','Togo','Togo','Togo','&#1058;&#1086;&#1075;&#1086;','Togo','多哥'),
'tk'=>array('Tokelau','Tokelau','Tokelau','Tokelau','Tokelau','Tokelau-szigetek','Tokelau','توكيلاو','Tokelau','Tokelau','Tokelau-eilanden','Tokelau','&#1058;&#1086;&#1082;&#1077;&#1083;&#1072;&#1091;','Tokelau','托克勞'),
'to'=>array('Tonga','Tonga','onga','Tonga','Tonga','Tonga','Tonga','تونغا','Tonga','Tonga','Tonga','Tonga','&#1058;&#1086;&#1085;&#1075;&#1072;','Tonga','東加'),
'tt'=>array('Trinidad und Tobago','Trinit&eacute;-et-Tobago','Trinidad y Tobago','Trinidad e Tobago','Trynidad i Tobago','Trinidad &eacute;s Tobago','Trindade e Tobago','ترينيداد وتوباغو','Trinidad og Tobago','Trinidad and Tobago','Trinidad en Tobago','Trinidad og Tobago','&#1058;&#1088;&#1080;&#1085;&#1080;&#1076;&#1072;&#1076; &#1080; &#1058;&#1086;&#1073;&#1072;&#1075;&#1086;','Trinidad i Tobago','千里達與多巴哥'),
'tn'=>array('Tunesien','Tunisie','T&uacute;nez','Tunisia','Tunezja','Tun&eacute;zia','Tun&iacute;sia','تونس','Tunesien','Tunisia','Tunesi&euml;','Tunisia','&#1058;&#1091;&#1085;&#1080;&#1089;','Tunis','突尼西亞'),
'tr'=>array('T&uuml;rkei','Turquie','Turqu&iacute;a','Turchia','Turcja','T&ouml;r&ouml;korsz&aacute;g','Turquia','تركيا','Tyrkiet','Turkey','Turkije','Tyrkia','&#1058;&#1091;&#1088;&#1094;&#1080;&#1103;','Turska','土耳其'),
'tm'=>array('Turkmenistan','Turkm&eacute;nistan','Turkmenist&aacute;n','Turkmenistan','Turkmenistan','T&uuml;rkmeniszt&aacute;n','Turquemenist&atilde;o','تركمانستان','Turkmenistan','Turkmenistan','Turkmenistan','Turkmenistan','&#1058;&#1091;&#1088;&#1082;&#1084;&#1077;&#1085;&#1080;&#1103;','Turkmenistan','土庫曼'),
'tc'=>array('Turks- und Caicosinseln','&Icirc;les Turques-et-Ca&iuml;ques','Islas Turcas y Caicos','Turks e Caicos','Turks i Caicos','Turks- &eacute;s Caicos-szigetek','Turks e Caicos','الاتراك','Turks- og Caicos&oslash;erne','Turks and Caicos Islands','Turks- en Caicoseilanden','Turks- og Caicos&oslash;yene','&#1058;&#1105;&#1088;&#1082;&#1089; &#1080; &#1050;&#1072;&#1081;&#1082;&#1086;&#1089;','Ostrva Turks i Kaikos Ostrva','土克斯及開科斯群島'),
'tv'=>array('Tuvalu','Tuvalu','Tuvalu','Tuvalu','Tuvalu','Tuvalu','Tuvalu','توفالو','Tuvalu','Tuvalu','Tuvalu','Tuvalu','&#1058;&#1091;&#1074;&#1072;&#1083;&#1091;','Tuvalu','圖瓦魯'),
'ug'=>array('Uganda','Ouganda','Uganda','Uganda','Uganda','Uganda','Uganda','أوغندا','Uganda','Uganda','Oeganda','Uganda','&#1059;&#1075;&#1072;&#1085;&#1076;&#1072;','Uganda','烏干達'),
'ua'=>array('Ukraine','Ukraine','kraine','Ucraina','Ukraina','Ukrajna','Ucr&acirc;nia','أوكرانيا','Ukraine','Ukraine','Oekra&iuml;ne','Ukraina','&#1059;&#1082;&#1088;&#1072;&#1080;&#1085;&#1072;','Ukrajina','烏克蘭'),
'uy'=>array('Uruguay','Uruguay','Uruguay','Uruguay','Urugwaj','Uruguay','Uruguai','أوروغواي','Uruguay','Uruguay','Uruguay','Uruguay','&#1059;&#1088;&#1091;&#1075;&#1074;&#1072;&#1081;','Urugvaj','烏拉圭'),
'uz'=>array('Usbekistan','Ouzb&eacute;kistan','Uzbekist&aacute;n','Uzbekistan','Uzbekistan','&Uuml;zbegiszt&aacute;n','Usbequist&atilde;o','أوزبكستان','Usbekistan','Uzbekistan','Oezbekistan','Usbekistan','&#1059;&#1079;&#1073;&#1077;&#1082;&#1080;&#1089;&#1090;&#1072;&#1085;','Uzbekistan','烏茲別克'),
'vu'=>array('Vanuatu','Vanuatu','Vanuatu','Vanuatu','Vanuatu','Vanuatu','Vanuatu','فانواتو','Vanuatu','Vanuatu','Vanuatu','Vanuatu','&#1042;&#1072;&#1085;&#1091;&#1072;&#1090;&#1091;','Vanuatu','萬那杜'),
'wf'=>array('Wallis und Futuna','Wallis et Futuna','Wallis y Futuna','Wallis e Futuna','Wallis i Futuna','Wallis &eacute;s Futuna','Wallis e Futuna','اليس اوند فوتونا','Wallis og Futuna','Wallis and Futuna','Wallis en Futuna','Wallis og Futuna','&#1059;&#1086;&#1083;&#1083;&#1080;&#1089; &#1080; &#1060;&#1091;&#1090;&#1091;&#1085;&#1072;','Valis i Futuna','沃里斯與伏塔那島'),
'va'=>array('Vatikanstadt','Saint-Si&egrave;ge (&Eacute;tat de la Cit&eacute; du Vatican)','Ciudad del Vaticano','Citt&agrave; del Vaticano','Watykan','Vatik&aacute;n','Vaticano','مدينة الفاتيكان','Vatikanstaten','Holy See','Vaticaanstad','Vatikanstaten','&#1042;&#1072;&#1090;&#1080;&#1082;&#1072;&#1085;','Vatikan','梵蒂岡'),
've'=>array('Venezuela','enezuela','Venezuela','Venezuela','Wenezuela','Venezuela','Venezuela','فنزويلا','Venezuela','Venezuela','Venezuela','Venezuela','&#1042;&#1077;&#1085;&#1077;&#1089;&#1091;&#1101;&#1083;&#1072;','Venecuela','委内瑞拉'),
'gb'=>array('Gro&szlig;britannien','Royaume-Uni','Reino Unido','Regno Unito','Wielka Brytania','Egyes&uuml;lt Kir&aacute;lys&aacute;g','Reino Unido','المملكة المتحدة','Storbritannien','United Kingdom','Verenigd Koninkrijk','Storbritannia','&#1042;&#1077;&#1083;&#1080;&#1082;&#1086;&#1073;&#1088;&#1080;&#1090;&#1072;&#1085;&#1080;&#1103;','Ujedinjeno Kraljevstvo','英國'),
'vn'=>array('Vietnam','iet Nam','Vietnam','Vietnam','Wietnam','Vietnam','Vietname','فيتنام','Vietnam','Viet Nam','Vietnam','Vietnam','&#1042;&#1100;&#1077;&#1090;&#1085;&#1072;&#1084;','Vijetnam','越南'),
'ci'=>array('Elfenbeink&uuml;ste','C&ocirc;te dIvoire','Costa de Marfil','Costa dAvorio','Wybrze&#380;e Ko&#347;ci S&#322;oniowej','Elef&aacute;ntcsontpart','Costa do Marfim','ساحل العاج','Elfenbenskysten','C&ocirc;te dIvoire','Ivoorkust','Elfenbenskysten','&#1050;&#1086;&#1090;-&#1076;’&#1048;&#1074;&#1091;&#1072;&#1088;','Obala slonova&#269;e','象牙海岸'),
'bv'=>array('Bouvetinsel','&Icirc;le Bouvet','Isla Bouvet','Isola Bouvet','Wyspa Bouveta','Bouvet-sziget','Bouvet Island','إيسلا بوفيت','Bouvet&oslash;en','Bouvet Island','Bouvet','Bouvet&oslash;ya','&#1054;&#1089;&#1090;&#1088;&#1086;&#1074; &#1041;&#1091;&#1074;&#1077;','Buvetovo Ostrvo','鮑威特島'),
'cx'=>array('Weihnachtsinsel','&Icirc;le Christmas','Isla de Navidad','Isola di Natale','Wyspa Bo&#380;ego Narodzenia','Kar&aacute;csony-sziget','Christmas Island','جزيرة كريسماس','Jule&oslash;en','Christmas Island','Christmaseiland','Christmas&oslash;ya','&#1054;&#1089;&#1090;&#1088;&#1086;&#1074; &#1056;&#1086;&#1078;&#1076;&#1077;&#1089;&#1090;&#1074;&#1072;','Bo&#382;i&#263;no Ostrvo','聖誕島'),
'im'=>array('Insel Man','&Icirc;le de Man','Isla de Man','Isola di Man','Wyspa Man','Isle of Man','Ilha de Man','جزيرة مان','Isle of Man','Isle of Man','Man','Isle of Man','&#1054;&#1089;&#1090;&#1088;&#1086;&#1074; &#1052;&#1101;&#1085;','Ostrvo Men','曼島'),
'sh'=>array('St. Helena','Sainte-H&eacute;l&egrave;neAscension et Tristan da Cunha','Santa Helena A. y T.','SantElena','Wyspa &#346;wi&#281;tej Heleny, Wyspa Wniebowst&#261;pienia i Tristan da Cunha','Szent Ilona','Saint Helena Ascension and Tristan da Cunha','سانت هيلانة','Sankt Helena','Saint Helena Ascension and Tristan da Cunha','Sint-Helena Ascension en Tristan da Cunha','St. Helena','&#1054;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072; &#1057;&#1074;&#1103;&#1090;&#1086;&#1081; &#1045;&#1083;&#1077;&#1085;&#1099; &#1042;&#1086;&#1079;&#1085;&#1077;&#1089;&#1077;&#1085;&#1080;&#1103; &#1080; &#1058;&#1088;&#1080;&#1089;&#1090;&#1072;&#1085;-&#1076;&#1072;-&#1050;&#1091;&#1085;&#1100;&#1103;','Sveta Jelena','聖海倫娜島'),
'ax'=>array('&Aring;land','&Aring;land','&Aring;land','sole &Aring;land','Wyspy Alandzkie','&Aring;land','&Aring;land Islands','آلاند','&Aring;lands&oslash;erne','&Aring;land Islands','&Aring;land','&Aring;land','&#1040;&#1083;&#1072;&#1085;&#1076;&#1089;&#1082;&#1080;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Alandska Ostrva','亞蘭群島'),
'ck'=>array('Cookinseln','&Icirc;les Cook','Islas Cook','Isole Cook','Wyspy Cooka','Cook-szigetek','Cook Islands','جزر كوك','Cook&oslash;erne','Cook Islands','Cookeilanden','Cook&oslash;yene','&#1054;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072; &#1050;&#1091;&#1082;&#1072;','Kukova ostrva','科克群島'),
'vi'=>array('Amerikanische Jungferninseln','&Icirc;les Vierges des &Eacute;tats-Unis','Islas V&iacute;rgenes de los Estados Unidos','Isole Vergini americane','Wyspy Dziewicze Stan&oacute;w Zjednoczonych','Amerikai Virgin-szigetek','Virgin Islands U.S.','U. S. جزر فيرجن','Amerikanske Jomfru&oslash;er','Virgin Islands, U.S.','Amerikaanse Maagdeneilanden','De amerikanske Jomfru&oslash;yene','&#1040;&#1084;&#1077;&#1088;&#1080;&#1082;&#1072;&#1085;&#1089;&#1082;&#1080;&#1077; &#1042;&#1080;&#1088;&#1075;&#1080;&#1085;&#1089;&#1082;&#1080;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Ameri&#269;ka Devi&#269;anska Ostrva','美屬處女群島'),
'hm'=>array('Heard und McDonaldinseln','&Icirc;les Heard-et-MacDonald','Islas Heard y McDonald','Isole Heard e McDonald','Wyspy Heard i McDonalda','Heard-sziget &eacute;s McDonald-szigetek','Heard Island and McDonald Islands','جزر ماكدونالد','Heard-&oslash;en og McDonald-&oslash;erne','Heard Island and McDonald Islands','Heard en McDonaldeilanden','Heard- og McDonald-&oslash;yene','&#1061;&#1077;&#1088;&#1076; &#1080; &#1052;&#1072;&#1082;&#1076;&#1086;&#1085;&#1072;&#1083;&#1100;&#1076;','Ostrvo Hard i Ostrva Mekdonald','赫德及麥當奴群島'),
'cc'=>array('Kokosinseln','&Icirc;les Cocos','Islas Cocos','Isole Cocos e Keeling','Wyspy Kokosowe','K&oacute;kusz (Keeling)-szigetek','Cocos Islands','جزر كوكوس','Cocos&oslash;erne','Cocos Islands','Cocoseilanden','Kokos&oslash;yene','&#1050;&#1086;&#1082;&#1086;&#1089;&#1086;&#1074;&#1099;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Kokosova Ostrva','可可斯群島'),
'mh'=>array('Marshallinseln','Marshall (pays)','Islas Marshall','Isole Marshall','Wyspy Marshalla','Marshall-szigetek','Ilhas Marshall','جزر مارشال','Marshall&oslash;erne','Marshall Islands','Marshalleilanden','Marshall&oslash;yene','&#1052;&#1072;&#1088;&#1096;&#1072;&#1083;&#1083;&#1086;&#1074;&#1099; &#1054;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Mar&scaron;alska Ostrva','馬紹爾群島'),
'fo'=>array('F&auml;r&ouml;er','&Icirc;les F&eacute;ro&eacute;','Islas Feroe','Isole F&aelig;r &Oslash;er','Wyspy Owcze','Fer&ouml;er','Faeroe Islands','جزر فارو','F&aelig;r&oslash;erne','Faroe Islands','Faer&ouml;er','F&aelig;r&oslash;yene','&#1060;&#1072;&#1088;&#1077;&#1088;&#1089;&#1082;&#1080;&#1077; &#1086;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Farska Ostrva','法羅群島'),
'sb'=>array('Salomonen','Salomon','Islas Salom&oacute;n','Isole Salomone','Wyspy Salomona','Salamon-szigetek','Ilhas Salom&atilde;o','جزر سليمان','Salomon&oslash;erne','Solomon Islands','Salomonseilanden','Salomon&oslash;yene','&#1057;&#1086;&#1083;&#1086;&#1084;&#1086;&#1085;&#1086;&#1074;&#1099; &#1054;&#1089;&#1090;&#1088;&#1086;&#1074;&#1072;','Solomonova Ostrva','所羅門群島'),
'st'=>array('S&atilde;o Tom&eacute; und Pr&iacute;ncipe','Sao Tom&eacute;-et-Principe','ao Tome and Principe','S&atilde;o Tom&eacute; e Pr&iacute;ncipe','Wyspy &#346;wi&#281;tego Tomasza i Ksi&#261;&#380;&#281;ca','S&atilde;o Tom&eacute; &eacute;s Pr&iacute;ncipe','S&atilde;o Tom&eacute; e Pr&iacute;ncipe','ساو تومي','Sao Tome og Principe','Sao Tome and Principe','Sao Tom&eacute; en Principe','S&atilde;o Tom&eacute; og Pr&iacute;ncipe','&#1057;&#1072;&#1085;-&#1058;&#1086;&#1084;&#1077; &#1080; &#1055;&#1088;&#1080;&#1085;&#1089;&#1080;&#1087;&#1080;','Sao Tome i Principe','聖多美及普林西比'),
'hu'=>array('Ungarn','Hongrie','Hungr&iacute;a','Ungheria','W&#281;gry','Magyarorsz&aacute;g','Hungria','هنغاريا','Ungarn','Hungary','Hongarije','Ungarn','&#1042;&#1077;&#1085;&#1075;&#1088;&#1080;&#1103;','Ma&#273;arska','匈牙利'),
'it'=>array('Italien','Italie','Italia','Italia','W&#322;ochy','Olaszorsz&aacute;g','It&aacute;lia','إيطاليا','Italien','Italy','Itali&euml;','Italia','&#1048;&#1090;&#1072;&#1083;&#1080;&#1103;','Italija','意大利'),
'zm'=>array('Sambia','ambia','Zambia','Zambia','Zambia','Zambia','Z&acirc;mbia','زامبيا','Zambia','Zambia','Zambia','Zambia','&#1047;&#1072;&#1084;&#1073;&#1080;&#1103;','Zambija','尚比亞'),
'zw'=>array('Simbabwe','imbabwe','Zimbabue','Zimbabwe','Zimbabwe','Zimbabwe','Zimbabu&eacute;','زيمبابوي','Zimbabwe','Zimbabwe','Zimbabwe','Zimbabwe','&#1047;&#1080;&#1084;&#1073;&#1072;&#1073;&#1074;&#1077;','Zimbabve','辛巴威'),
'ae'=>array('Vereinigte Arabische Emirate','&Eacute;mirats arabes unis','Emiratos &Aacute;rabes Unidos','Emirati Arabi Uniti','Zjednoczone Emiraty Arabskie','Egyes&uuml;lt Arab Em&iacute;rs&eacute;gek','Emiratos &Aacute;rabes Unidos','الامارات العربية المتحدة','Forenede Arabiske Emirater','United Arab Emirates','Verenigde Arabische Emiraten','De forente arabiske emirater','&#1054;&#1040;&#1069;','Ujedinjeni Arapski Emirati','阿聯酋'),
'lv'=>array('Lettland','Lettonie','Letonia','Lettonia','&#321;otwa','Lettorsz&aacute;g','Let&oacute;nia','لاتفيا','Letland','Latvia','Letland','Latvia','&#1051;&#1072;&#1090;&#1074;&#1080;&#1103;','Letonija','拉脫維亞'),



);


if(!isset($state_name[$term_in][$opt_language_index]))
      {$term_out=$term_in; }
    else
      {$term_out=trim($state_name[$term_in][$opt_language_index]);}
    echo "TI =".$term_in;
    echo "TO =".$term_out; 
    return $term_out;
}

function GG_funx_unscramble_day_night($gg_weather){
 
  $pos=strpos($gg_weather[0][1][1]," PM");
  if($pos> 0){$add_hours=12;}
  $pos=strpos($gg_weather[0][1][1],":");  
  $pos_1=$pos-2;
  $hours=substr($gg_weather[0][1][1],$pos_1,2);
  $minutes=substr($gg_weather[0][1][1],$pos+1,2);
  $time=$hours+$minutes/60+$add_hours;
  if($time<=5.5 or $time>=18.5){
    $gg_weather[0][19][7]="night";}
  else{
    $gg_weather[0][19][7]="day";}  
}
?>