<fieldset id="<?php echo $this->fieldset;?>">
  <?php if(!empty($this->legend[$this->fieldset])) {?><legend><?php echo $this->legend[$this->fieldset];?></legend><?php }?>
  <?php echo implode(PHP_EOL,$this->output[$this->fieldset]);?>
</fieldset>