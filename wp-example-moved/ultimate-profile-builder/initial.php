<?php





	if(isset($_REQUEST['submit']))





	{





		$value=$_REQUEST['upb_selecttype'];





		storedata($value);





	}





	





	if(!checkdata())





	{





		global $wpdb;





		$upb_cat=$wpdb->prefix."upb_cat";





		$select="SELECT * FROM $upb_cat";


		$data = $wpdb->get_results($select);





		//$data=mysql_query($select);





?>





        <form action="#" method="post">





            <div id="upb_selecttype_div" style="height:400px; width:400px;">





                <select id="upb_selecttype" name="upb_selecttype">





                    <option value="select">Select</option>





<?php





					foreach($data as $row)





					{





?>





                        <option value="<?php echo $row->id; ?>">





                            <?php echo $row->name; ?>





                        </option>





<?php





					}





?>





                </select>





                <input type="submit" value="Submit" name="submit" onclick="check();" />





            </div>





        </form>





<?php





	}





	else





	{





		include 'upboption.php';





	}











	function storedata($value)





	{





		add_option( 'upb_preinstallation',$value, '', 'yes' );





//		include 'upboption.php';





	}





	function checkdata()





	{





		$value=get_option('upb_preinstallation');





		if(!empty($value))





		{





			return true;





		}





		return false;





	}





?>