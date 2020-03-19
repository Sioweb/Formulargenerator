<div class="select"><?php if($this->label) {?>
  <label for="<?php echo $this->id;?>"><?php echo $this->label;?></label><?php }?>
  <select name="<?php echo $this->name;?>" id="<?php echo $this->id;?>"<?php echo ($this->attribute?' '.$this->attribute:'');?> size="<?php echo $this->size;?>">
  <?php foreach($this->options as $value => $option) {?>
  <option value="<?php echo $value;?>"<?php echo ($this->active == $value?' selected="selected"':'');?>><?php echo $option;?></option>
  <?php }?>
  </select>
</div>