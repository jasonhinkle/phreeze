[parameters]
name= Action column crud with Savant
description=A Full PHP Phreeze Application Using Backbone, Savant & Bootstrap

# files is a tab-delimited list. columns = souce_file, destination_file, generate_mode
# generate_mode: 0 = generate file for each table, 1 = generate single file, 2 = copy file as-is, 3 = phar package

[files]
phreeze.actioncolumn/templates/ListView.tpl.tpl	templates/{$singular}ListView.tpl.php	0
phreeze.actioncolumn/scripts/app/objectname.js.tpl	scripts/app/{$plural|lower}.js	0