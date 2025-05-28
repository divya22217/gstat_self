  <?php 
  
 $val=$_REQUEST['val'];

  
  ?>
  
  

  <select id="in_searchby" name="searchby" style="
  <?php if($val =='NO'){ ?>  background-color: red; <?php } else {?>
  background-color: green;
  <?php }?>
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;">
    <?php if($val =='YES' || $val =='NA') { ?>           
   <option value="2"  <?php if($val =='YES' || $val =='NA') { print " selected"; }?>>DEFECT FREE</option>
	<?php } ?>
	<?php if($val =='NO') { ?>  
   <option value="1"  <?php if($val =='NO') { print " selected"; }?>> DEFECTIVE</option>
       <?php } ?>    
   </select>
  