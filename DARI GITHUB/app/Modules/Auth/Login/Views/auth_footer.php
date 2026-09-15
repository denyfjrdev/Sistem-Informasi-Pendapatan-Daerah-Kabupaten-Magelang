 <?php 
    
  if(isset($js_tambahan)){
    foreach($js_tambahan as $jss2){
      echo '<script src="'.base_url($jss2).'"></script>';
    }
  }

  if(isset($js)){
    foreach($js as $jss){
      echo '<script src="'.base_url($jss).'"></script>';
    }
  } 
    
?>


<script>
  <?php if(isset($script)){ foreach($script as $sc){?>
    <?php require_once(FCPATH.'/'.$sc);?>
  <?php }}?>
</script>