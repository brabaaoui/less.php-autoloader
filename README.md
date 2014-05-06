Less.php Autoloader
========

Small PHP Class dealing with oyejorge's compiler [less.php](https://github.com/oyejorge/less.php)
Just put it in your CI's Autoloader and it'll automatically parse less files to css if necessary. 
Working with CodeIgniter. 


Basic Use
---


Step 1. Put less.php folder and LessHandler.php in your libraries folder  
Step 2. Put less_parser.php in your config folder  
Step 3. Set directories for input and output in less_parser.php  
Step 4. Go to autoloader.php and add :  

```

$autoload['libraries'] = array('LessHandler');

```

Step 5. Enjoy!



Credits
---
less.php was originally ported to php by [Matt Agar](https://github.com/agar) and then updated by [Martin Jantošovič](https://github.com/Mordred).
This project was made with [Less.php compiler](https://github.com/oyejorge/less.php) from [Oyejorge](http://lessphp.gpeasy.com) 
