<div class="text"><?php if($this->label) {?>
  <label for="<?php echo $this->id;?>"><?php echo $this->label;?></label><?php }?>
  <input type="text" name="<?php echo $this->name;?>" id="<?php echo $this->id;?>"<?php echo ($this->placeholder?' placeholder="'.$this->placeholder.'"':'');?><?php echo ($this->attribute?' '.$this->attribute:'');?> value="<?php echo $this->value;?>">
</div>