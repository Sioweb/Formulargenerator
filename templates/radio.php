<div class="radio">
  <input type="radio"<?php echo ($this->value == $this->active?' checked="checked"':'');?> name="<?php echo $this->name;?>" id="<?php echo $this->id;?>" value="<?php echo $this->value;?>"><?php if($this->label) {?>
  <label for="<?php echo $this->id;?>"><?php echo $this->label;?></label><?php }?>
</div>