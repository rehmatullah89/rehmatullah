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
// alert(series_name);
// var series_name = document.getElementById("series_name").value";
     document.getElementById("series_name_show").innerHTML = series_name;
        $.ajax({
            url: "get_items.php?s_n="+series_name,
			type: 'POST',
			success: function(data){
                $("#load_items").html(data);
            }
        });
}

function myFunction(id, value){
 // alert("id="+id+"value="+value);
var dist = document.getElementById("Distributor").value;
var cstmr = document.getElementById("customers").value;
 
 var id_c = encodeURIComponent(id);
 var value_c = encodeURIComponent(value);
 
 $.ajax({
            url: "add_bill_item.php?id="+id_c+"&value="+value_c+"&dist="+dist+"&cstmr="+cstmr,
			type: 'POST',
			success: function(data){
                $("#get_bill").html(data);
            }
        });

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
$q="SELECT series_name FROM series";
$series_names=mysql_query($q);
 echo "<u><h4 style='color:red'>Series Names:&nbsp;<span style='font-size:12pt; margin-top:-5px;' id='series_name_show'></span></h4></u>";
while($row1=mysql_fetch_array($series_names)){
 $s_name = trim($row1['series_name']);
 ?>
 <button style="width:120px;" onclick="get_items(this.value)" id='series_name_btn' value="<?php echo $s_name;?>"><?php echo $s_name;?></button></br>
<?php 
 }
?>
			</td>
			
			
			<td>									
	
	<div id="load_items">
	<!-- form data will be printed here -->			
	</div>		</td><td>
	<form action="reverse_bill_exec.php" method="post" onsubmit="return confirm('Are you sure you want to complete this Order?');">
		<div align="center"><label>Select Distributor:</label><select name="Distributor" id="Distributor">
		<option>Select Distributor Name</option>
		<?php
		$qry = "SELECT Name_distributor from distributors_list";
		$dist_names=mysql_query($qry);
		while($row2=mysql_fetch_array($dist_names)){
			echo "<option>".$row2['Name_distributor']."</option>";
		}
		?>
		</select>&nbsp&nbsp&nbsp&nbsp
		<label>Select Customer:</label><select id="customers" name="customers">
			<!-- the customers options will be added here -->
		</select>
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