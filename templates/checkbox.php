<div class="checkbox">
  <input type="checkbox"<?php echo (in_array($this->value,(array)$this->active)?' checked="checked"':'');?> name="<?php echo $this->name;?><?php echo ($this->multiple?'[]':'');?>" id="<?php echo $this->id;?>" value="<?php echo $this->value;?>"><?php if($this->label) {?>
  <label for="<?php echo $this->id;?>"><?php echo $this->label;?></label><?php }?>
</div>