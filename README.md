# Formulargenerator

Rendering fast and dynamic a formular.

##HowTo

At first include and initialice the form class:

	include 'classes/Form.php';
	$Form = new Form(string $TemplatePath, string $Fieldsetname, bool $post = true,array $formdata = array());
	
Now you can load the formular elements in to ways:

	$Form->field('text',[
		'name'=>'name',
		'label'=>'Name',
		'placeholder'=>'Insert here!'
	]);

Case two:

	$Form->field('text');
	$Form->name = 'name';
	$Form->label = 'Lastname';
	$Form->placeholder = 'Insert here!';
		
Both cases can be combined, load the form elemend with the second param and replace later some of those element params like in case two.

The Method to load new formular elements will save the element automatically and load the next. So every time you load a new, all params will be saved for the loaded element.

	$Form->field('text');
	$Form->name = 'name';
	$Form->label = 'Name';
	$Form->placeholder = 'Please insert your name';
	
	$Form->field('text');
	$Form->name = 'lastname';
	$Form->label = 'Lastname';
	$Form->placeholder = 'Please insert your lastname';

##Direct Input

You can also insert an array with fieldsets and formular elements. This can be very useful if  you save the formular settigns as JSON in your database.

	$FormData = [
  		'fieldset_one' => [
    		'name' => ['template'=>'text','value'=>'Sascha Weidner','label'=>'Name','placeholder'=>'Insert here!'],
    		'lastname' => ['template'=>'text','label'=>'Lastname','placeholder'=>'Insert here!'],
  		]
	];

Now inititialize the formular and you will get the same output as in the next examples.
	
	$Form = new Form('/to/your/templates','std',true,$FormData);
	$Form->generate(true);


##Fieldsets

It's possible to group the elements in fieldsets. All elements are in fieldset `std` by default. If you wan't to goup some elements, just define a new fieldset:

	$Form->fieldset(string $fieldsetID,string $legend);	
Example:

	$Form->fieldset('radio_1','Radio-Buttons');

	$Form->field('radio');
	$Form->name = 'radio';
	$Form->label = 'Radio 1';
	$Form->value = 1;

	$Form->field('radio');
	$Form->name = 'radio';
	$Form->label = 'Radio 2';
	$Form->value = 2;


	$Form->fieldset('checkbox_1','Checkboxen');

	$Form->field('checkbox');
	$Form->name = 'checkbox';
	$Form->label = 'Checkbox 1';
	$Form->value = 1;

	$Form->field('checkbox');
	$Form->name = 'checkbox';
	$Form->label = 'Checkbox 2';
	$Form->value = 2;

##Output

The class returns an array with all rendered elements. You can build now your own formular if needed or you can print it directly.

	$Form->generate(true);
	
If the value is not null or false it will print all rendered fieldsets and elements. TRUE means that all elements will chained without space. You can also define `$Form->generate('<br>');` to chain all elements with HTML breaks.

##Attributes

All elements has default attributes and some has their own specific attributes. So every element needs a name but just textarea needs cols and rows – to print a valid form.

###Defaults

- $Form->label
- $Form->value	if $post is true in the first line, it can be overrided by POST-Values.
- $Form->name every element needs a name, if $Form->multiple is set, it will add those tho the name `name[]`
- $Form->placeholer
- $Form->multiple is false by default
- $Form->attribute can contain everything. This is useful for javascript attributes.

###Select

- $Form->size

###Checkbox

- Form->multiple is by default true. Just write `$Form->multiple = false;` if you wan't to undone this.

###Textarea

- $Form->cols by default 50
- $Form->rows by default 10

##Futur releases

This project is increasing by it's requirements. If i need or maybe if you need, i can update it.

Thx  
Sioweb