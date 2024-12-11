<?php
error_reporting(0);
include("header2.php");
unset($_SESSION['counter']);
unset($_SESSION['cart']);
?>
<script>
$(document).ready(function () {
    $('#Distributor').change(function(){
        $.ajax({
            url: "get_customers.php",
            type: "post",
            data: {option: $(this).find("option:selected").val()},
            success: function(data){
                //adds the echoed response to our container
                $("#customers").html(data);
            }
        });
    });
});

function get_items(series_name)
{
   document.getElementById("series_name_show").innerHTML = series_name;
// alert(series_name);
// var series_name = document.getElementById("series_name").value";
var cstmr = document.getElementById("customers").value;
var phone = document.getElementById("cphone").value; 
var address = document.getElementById("caddress").value; 
   
        $.ajax({
            url: "get_manual_items.php?s_n="+series_name+"&phone="+phone+"&cstmr="+cstmr+"&address="+address,
			type: 'POST',
			success: function(data){
                $("#load_items").html(data);
            }
        });
}

function myFunction(id, value){
 // alert("id="+id+"value="+value);
var cstmr = document.getElementById("customers").value;
var phone = document.getElementById("cphone").value; 
var address = document.getElementById("caddress").value; 
			 // alert(document.getElementById("discount["+1+"]").value);
			// console.log(document.querySelectorAll("[name='discount[]']").length);
			var disc_=0;
			var	series_name="";
			var disc = new Array();;
			for (var i = 0; i < 100; i++)
            {
                 try
				  {
				   disc_ = document.getElementById("discount["+i+"]").value; 
				   if(disc_ == "")
				   series_name = "";
				   else
				   series_name = document.getElementById("series_name_btn["+i+"]").value;
				   
				   disc[i] = disc_+"~"+series_name;
				  }
				catch(err)
				  {
					break; 
				  }
			}
			// var data_disc = $.serialize(disc);
			// var discount = document.getElementById("discount").value; 
 //var disc_arr= JSON.stringify(disc);
 var disc_arr= disc;
 var id_c = encodeURIComponent(id);
 var value_c = encodeURIComponent(value);
 
		
 
 $.ajax({
            url: "add_manual_bill_item.php?id="+id_c+"&value="+value_c+"&phone="+phone+"&cstmr="+cstmr+"&address="+address+"&discount="+disc_arr,
			type: 'POST',
			success: function(data){
                $("#get_bill").html(data);
            }
        
		 });
		 
		 for (var i = 0; i < 100; i++)
            {
                 try
				  {
				   document.getElementById("discount["+i+"]").disabled = true; 
				  }
				catch(err)
				  {
					break; 
				  }
			}

}
</script>

<!-- content -->
	<section id="content">
		<div class="container">
			<div class="inside bot-indent">
					<h4 align="right"><a href="logout.php" style="color:red;">(Log Out)</a></h4>
				<!-- <h2 align="center" class="extra">Place Order Here!</h2> -->
			<table width="1020">
			<tr><td>
			<?php 
				
	//Include database connection details
	require_once('config.php');
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die('Unable to select database');
	}

	$arr = array();
	$arr1 = array();
	$s_name_arr = array();
	$discount = array();
	$kp=0;
$q="SELECT series_name FROM series";
$series_names=mysql_query($q);
 echo "<u><font style='color:red; size='18px;''>Series Names:&nbsp;&nbsp;<span style='font-size:10pt; color:blue; margin-top:-5px;' id='series_name_show'></span>&nbsp&nbsp&nbsp&nbsp&nbsp</font></u>Discount(%age)</br>";
while($row1=mysql_fetch_array($series_names)){
 $s_name = trim($row1['series_name']);
 ?>
 <button style="width:120px;" onclick="get_items(this.value)" id="series_name_btn[<?php echo $kp?>]" value="<?php echo $s_name;?>"><?php echo $s_name;?></button><input type="text" size="4" style="height:20px;" name="discount[<?php echo $kp?>]" id="discount[<?php echo $kp?>]" value=""/></br>
<?php 
 $kp++;
 }
?>
			</td>
			
			
			<td>									
	
	<div id="load_items">
	<!-- form data will be printed here -->			
	</div>		</td><td>----------</td><td>
	<form action="manual_order_exec.php" name="myForm" method="post" onsubmit="return confirm('Are you sure you want to complete this Order?');">
	<h4>Click to Change! ->[<a style="color:red;" href="orders.php?tab=orders">Click For Distributor Bill</a>]</h4>
		<div align="center">
		<table>
		<tr><td><label>Enter Customer Name:</label></td><td><input type="text" name="customers" id="customers"></td></tr>
		<tr><td><label>Enter Customer Phone:</label></td><td><input type="text" name="cphone" id="cphone"></td></tr>
		<tr><td><label>Enter Customer Address:</label></td><td><input type="text" name="caddress" id="caddress"></td></tr>
		</table>
		</div></br>
<div id="get_bill">
<!-- Bill will be printed here-->
</div>
<!-- <input type='submit'	name='submit' value='submit'> -->
</form>
</td></tr>										
											
				</table>
				</br></br></br></br>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
include("footer.php");
?>		