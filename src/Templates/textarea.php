<div class="textarea"><?php if($this->label) {?>
  <label for="<?php echo $this->id;?>"><?php echo $this->label;?></label><?php }?>
  <textarea cols="<?php echo $this->cols;?>" rows="<?php echo $this->rows;?>" name="<?php echo $this->name;?>" id="<?php echo $this->id;?>"<?php echo ($this->placeholder?' placeholder="'.$this->placeholder.'"':'');?><?php echo ($this->attribute?' '.$this->attribute:'');?>><?php echo $this->value;?></textarea>
</div>