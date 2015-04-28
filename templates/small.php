<p><?php if($this->label) {?>
  <label for="<?php echo $this->id;?>"><?php echo $this->label;?></label><?php }?>
  <input<?php echo ($this->required?' required':'');?> type="text" name="<?php echo $this->name;?>" class="small<?php echo ($this->required?' required':'');?>" id="<?php echo $this->id;?>"<?php echo ($this->placeholder?' placeholder="'.$this->placeholder.'"':'');?><?php echo ($this->attribute?' '.$this->attribute:'');?> value="<?php echo $this->value;?>"<?php echo ($this->maxlength?' maxlength="'.$this->maxlength.'"':'');?><?php echo ($this->size?' size="'.$this->size.'"':'');?>>
</p>