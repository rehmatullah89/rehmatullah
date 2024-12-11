 <html>
    <head>
        <script language="JavaScript" type="text/javascript">
            
            function Runbat()
            {
			 WshShell = new ActiveXObject("WScript.Shell");
			WshShell.Run ("file:///C:\\SMSCaster\\sms.bat",1,true);
    
			 
            }
        </script>
    </head>
    <body>
        <h1>Run a Program</h1>
        This script launch the file any bat File<p>
        <button onclick="Runbat()">Run bat File</button>
    </body>
</html>
 <?php
  function exec_enabled() {
  $disabled = explode(', ', ini_get('disable_functions'));
  return !in_array('exec', $disabled);
}
exec_enabled();
 $number=$_REQUEST['n'];
 $text=$_REQUEST['m'];
 $file = 'C:\\SMSCaster\\smscaster.exe';
if (!file_exists($file)) echo 'File does not exists';
exec($file, $out);
var_dump($out);

$path="C:\SMSCaster\\"; 
//chdir($path); 
//system('cmd.exe /K C:\\SMSCaster\\smscaster.exe -Compose 00923325450983 fromsmsclinew -Start',$output);

//system("start sms.bat",$output);
//exec("cmd.exe /c dir", $output);
    print_r($output);
 ?>
 
 