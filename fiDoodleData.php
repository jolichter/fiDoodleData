<?php
#fiDoodleData V 17.05.006
#read FormIt data from sql table 'modx_formit_forms' and parse a html data table
#Note: https://docs.modx.com/extras/revo/formit/formit.hooks/formit.hooks.formitsaveform
#
#e.g. chunk call with one date, qty and purecss table
#[[!fiDoodleData?
#  &context=`web42`
#  &formName=`Doodle`
#  &class=`pure-table pure-table-horizontal`
#  &name=`Dein Name`
#  &date1=`01.11.2017`
#  &qty=`Anzahl`
#]]
# or chunk call with 3 dates and boostrap table
#[[!fiDoodleData?
#  &context=`web07`
#  &formName=`Doodle`
#  &class=`table table-striped`
#  &date1=`01.04.2017`
#  &date2=`22.04.2017`
#  &date3=`06.05.2017`
#]]
#qty, date2 - date5 are optional!


#PARAMETERS
#Field Names
$strFN1 = $modx->getOption('id',$scriptProperties,'#');
$strFN2 = $modx->getOption('name',$scriptProperties,'Name');
$strFN3 = $modx->getOption('date1',$scriptProperties,'');
#optional!
$strFN4 = $modx->getOption('date2',$scriptProperties,'');
$strFN5 = $modx->getOption('date3',$scriptProperties,'');
$strFN6 = $modx->getOption('date4',$scriptProperties,'');
$strFN7 = $modx->getOption('date5',$scriptProperties,'');
$intFNQty = $modx->getOption('qty',$scriptProperties,'');

#SQL WHERE context and form
$wc = $modx->getOption('context',$scriptProperties,'web');
$wf = $modx->getOption('formName',$scriptProperties,'');

$sqlData = '';
$sql = "SELECT * FROM modx_formit_forms WHERE context_key = '". $wc ."' AND form = '". $wf ."'";
foreach ($modx->query($sql) as $row) {
    $sqlData .= $row['values'] .',';
}

#remove last comma
$sqlData = substr($sqlData, 0, -1);

#make it json_decode conform
$sqlData = '['.$sqlData.']';

$arr = json_decode($sqlData, true);
$count = count($arr);

$strTable = '<table class="';
$strTable = $strTable.$class;
$strTable = $strTable.'">
  <thead>
    <tr>
      <th>'.$strFN1.'</th>
      <th>'.$strFN2.'</th>
      <th>'.$strFN3.'</th>
';

#optional
      if (!empty($strFN4)) $strTable = $strTable.'<th>'.$strFN4.'</th>';
      if (!empty($strFN5)) $strTable = $strTable.'<th>'.$strFN5.'</th>';
      if (!empty($strFN6)) $strTable = $strTable.'<th>'.$strFN6.'</th>';
      if (!empty($strFN7)) $strTable = $strTable.'<th>'.$strFN7.'</th>';
      if (!empty($intFNQty)) $strTable = $strTable.'<th>'.$intFNQty.'</th>';

$strTable = $strTable.'
    </tr>
  </thead>
  <tbody>
';

$c = 0;
for ($i = 0; $i < $count; $i++) {
   $c++;
    $strName = $arr[$i]['name'];
    $bolDat1 = $arr[$i]['datum1'];
    $bolDat2 = $arr[$i]['datum2'];
    $bolDat3 = $arr[$i]['datum3'];
    $bolDat4 = $arr[$i]['datum4'];
    $bolDat5 = $arr[$i]['datum5'];

   if ($bolDat1 == 0) {
       $strDat1 = 'Nein';
   } else {
       $strDat1 = 'Ja';
   }

   if ($bolDat2 == 0) {
       $strDat2 = 'Nein';
   } else {
       $strDat2 = 'Ja';
   }

   if ($bolDat3 == 0) {
       $strDat3 = 'Nein';
   } else {
       $strDat3 = 'Ja';
   }

   if ($bolDat4 == 0) {
       $strDat4 = 'Nein';
   } else {
       $strDat4 = 'Ja';
   }

   if ($bolDat5 == 0) {
       $strDat5 = 'Nein';
   } else {
       $strDat5 = 'Ja';
   }

   #if all dates NEIN then qty is nothing !
   if (!empty($intFNQty)) {
       #if all dates NEIN then qty is nothing!
       if($bolDat1 == 0 and $bolDat2 == 0 and $bolDat3 == 0 and $bolDat4 == 0 and $bolDat5 == 0)
       {
         $intFNQty = '-';
       }  else {
         $intFNQty = $arr[$i]['qty'];
       }
   }

   $strTable = $strTable.'
   <tr>
     <th scope="row">'.$c.'</th>
     <td>'.$strName.'</td>
     <td>'.$strDat1.'</td>
';

#optional
     if (!empty($strFN4)) $strTable = $strTable.'<td>'.$strDat2.'</td>';
     if (!empty($strFN5)) $strTable = $strTable.'<td>'.$strDat3.'</td>';
     if (!empty($strFN6)) $strTable = $strTable.'<td>'.$strDat4.'</td>';
     if (!empty($strFN7)) $strTable = $strTable.'<td>'.$strDat5.'</td>';
     if (!empty($intFNQty)) $strTable = $strTable.'<td>'.$intFNQty.'</td>';
   $strTable = $strTable.'
   </tr>
   ';
}
$strTable = $strTable.'</tbody></table>';

return $strTable;
