kitDirList - Extension for KeepInTouch:

•	Making files available for download
•	Overview and program description
•	System requirements and download
•	Control of droplet by parameters
•	Using file description
•	Adaptations and extensions
 
Often the very things that seem completely straightforward in practice, may not be handled so easily.
Websites basically are an excellent tool for your visitors to provide them information and forward files to them. Below we develop a small scenario, which is typical for the development of websites:
•	First you want to offer the visiors of your website the actual product catalog for download. To achieve this you just link from the appropriate locations to the catalog. This is done easily and quickly.
•	Your online information grows and grows and there are now several different catalogs. Instead of linking your customers to a certain subject catalog you lead them to a download area where they can select and download the desired catalog. This can be a simple list or can provide more comfort by means of DirList: this add-on provides the content af any given directory on the server for downloading in a way similar to the Windows explorer.
•	Meanwhile you not only look for direct customers but also for resellers. For them you built up a protected area with additional information. Resellers have to log in to this area.  You also provide different price lists for download.  But now you are - quite rightly - concerned: Are the price lists safe?  How can I prevent, that third parties can access these files?
If you deploy the files on your server as before the curious surfer only needs to know the address of the file -  often an assumption is sufficient – and he can access it directly.
The problem is that the directories on your webserver are not protected. Anyone can access the contents without any control! If you deal with this issue you will very soon learn that there are as well secure, as user friendly solutions for this problem, but no secure, flexible and easy-to-use options.
Unless you are using kitDirList.
 
Overview and program description

The function expansion kitDirList provides an access to the directories on your webserver in a very simple manner. In order to achieve this you only insert a droplet at the desired location. Neither installation nor configuration is required.
Accesssing the data provided can be publicly or via a full directory protection. In this case unauthorized access is not possible. 
The extension kitDirList was especially developed for the Customer Relationship Management (CRM) KeepInTouch (KIT). The extension can also be used without KeepInTouch. The functionality is limited in this case.
The installation is done as a feature enhancement (snippet) of WebsiteBaker. During installation a droplet is added to the system which can directly be referred to during editing of pages.
At first call of this droplet kitDirList creates a new folder /kit_protected in the /MEDIA directory of your webspace. This folder is protected by kitDirList by means of .htaccess and .htpasswd. External access to files in this folder is not possible. Try it: you will be asked to enter a username and a password. You, however, have direct access to the contents of the folder via media administration in backend of WebsiteBaker or via the FTP program that you use. You do not need an external access. 
In order to make data available for authorized access, locate them in a subdirectory of the folder /kit_protected  – the protection is extended to all directories and files within the directory /kit_protected.
You can use kitDirList to provide any other folder in the /MEDIA directory. In this case the access is public and the files are not protected.
 
System requirements and download

System requirements:
•	WebsiteBaker 2.8.1
•	PHP 5.2 or higher
•	dbConnect_LE installed
•	Dwoo installed
•	recommended: KeepInTouch installed
•	UTF-8 character set is used
 
Parameters and examples

Control of kitDirList is completely performed by the automatically installed droplet: [[kit_dirlist]]
With a "pure" call without defining any parameters kitDirList displays all files in the top layer of your directory /MEDIA.
Does kitDirList really display all files? No, not all. There are some files which, for security reasons, are generally not be displayed by kitDirList. This is true for alle system files beginning with a dot (.htaccess, .htpasswd …) and all program files (*.php, *.php5 …). In a public acces the directory /kit_protected will also not be displayed.
Whenever you insert or change parameters save the page and view it in frontend. kitDirList displays all error messages or hints in frontend in case the parameters are incomplete or conditions are not met. Make sure to always check this information!
Sequence of parameters is insignificant. The first parameter you attach to the droplet kit_dirlist starts with an "?". Next character is an "=" followed by the value or values assigned to the parameter. Each additional parameter starts with an "&".

kitDirList supports the following parameters:

•	media – Name of the directory in folder /MEDIA to be displayed
•	sort – Sort order of the directory: sort=asc : ascending, sort=desc : descending, default: ascending a-z 
•	recursive – Inclusion of subdirectories: recursive=true or recursive=false, default is false
•	include – Extensions of files to be shown. All other files will be hidden. Enter only file extensions without dot or wildcards e.g.: include=pdf,jpg,gif,tif : shows all *.pdf, *.jpg, *.gif and *.tif files.
•	exclude – Extensions of files to be hidden. All other files will be shown. Enter only file extensions without dot or wildcards (see include). Parameters include and exclude must not be used together as they are mutually exclusive.
•	kit_intern – Protected access. INTERNAL categories in KeepInTouch (KIT), e.g. kit_intern=catWBUser. Separate multiple internal categories by comma.
•	kit_news – Protected access. NEWSLETTER categories in KIT, e.g. kit_news=newsNewsletter
•	kit_dist – Protected access. DISTRIBUTION categories in KIT, e.g. kit_dist=distControl
•	wb_group – Protected access. WebsiteBaker groups. Separate multiple groups by comma. Please note that the group names must be clearly identifyable for kitDirList. Avoid spaces, special characters and umlauts. A group name like „Häkelgruppe – Redaktion, Nachmittagsgruppe (Mechthild Fadenspur)“ will definitefely confuse kitDirList. „Haekelgruppe_Fadenspur“ – would be much better and makes entering of parameters easier.
•	copyright – The copyright notice of kitDirList can be turned off with copyright=false. Please consider not only for commercial use of kitDirList an active and / or financial support for various Open Source projects of phpManufaktur.

Insert the droplet [[kit_dirlist]] in that location of the desired page where the directory list is to be displayed.

What is important?

In a protected access kitDirList expects the directory to be displayed located underneath /media/kit_protected.  An open-access directory cannot have protection against unauthorized acces!
In protected access kitDirList displays the directory not right away. First the user is asked to log in. 
Login itself is - depending on parameters - either performed via login of KeepInTouch (KIT) or via login dialog of WebsiteBaker. After successful login, users have access to the protected data. In protected access all data access is performed by a speccial programm file of kitDirList. This programm file checks whether the user is loggeed in and authorized to download the file. Only if this is true, the data is made available. Otherwise the access will be rejected (HTTP error code 403: Access denied). Don't hesitate to try this! 

Using file description

File names in many cases are not sufficiently describing a file. Additional information is helpful and supports the users of your website in selecting the proper data to downlaod. Tooltips meanwhile are a standard and will certainly be appreciated by your customers.

How to include the information required into kitDirList?

Use an editor to create a new textfile and insert in a separate line per file the filename and then, separated by a pipe symbol | the file description:

kit_dirlist_300.jpg|This image shows a symbol for a half opened folder with lock. 
projekt-em.jpg|Logo of the Berlin agency "Projekt EM"
kit_dirlist_beispiel.jpg|Screenshot of a directory list displayed by kitDirList

Save this testfile in UTF-8 format as dirlist.txt in the same directory, where the according data files are residing. Whenever kitDirList detects a file dirlist.txt in a directory to be displayed, this is automatically read, the descriptions are assigned to the appropriate files and file dirlist.txt will not be displayed.
It is not neccessary to provide a description for each file in the directory. Just make sure to save dirlist.txt in UTF-8 format. This ensures correct display of special characters and umlauts. Since double quotes ("") create conflicts in HTML title tag during output, kitDirList replaces them by single quotes.
 
Adaptations and extensions

KeepInTouch (KIT) and the extensions attached provide a very powerful basis for further development of applications which may be customized to your individual requirements.
If you have any question about the usability and possibilities of KeepInTouch or if you are looking for a customized solution, please don't hesitate to contact me! Customized internet solutions are very efficient!

Translation by Armin Ipfelkofer.

kitDirList - Copyright (c) 2011 by phpManfaktur, Berlin (Germany)
http://phpManufaktur.de/kit_dirlist
ralf.hertsch@phpManufaktur.de