.. include:: ../Includes.txt



.. _installation:

============
Installation
============

Target group: **Administrators**

Import the extension from a local source or the TER like any other extension.

Create some pages as shown below.

.. figure:: ../Images/AdministratorManual/InstallPages.png
	:width: 200px
	:alt: Pages for booking

On the pages MonthMultiRow, List and Details insert the corresponding plugins.
On page MultiRowCalendar insert the plugin MultiRowCalendar and List. 
On the page Details insert the plugin Myseminars (Details). On the page RequestForm insert the plugin Myseminars (RequestForm).


On page 'Booking' or on your root page insert 'Typoscript Include static' (from extension) Booking (booking).
With the constant editor insert the desired settings of the extension like storage Pid, default calendar, jQuery options, path to templates etc.

.. image:: ../Images/AdministratorManual/IncludeStatic.png
	:width: 400px
	:alt: Include Static


To use your own templates, copy the directory folder /typo3conf/ext/booking/Resources/ with
subdirectories to your own template folder (for example fileadmin/includes/ext/booking/Resources/).
Then change the templateRootPath in the constant editor to the new value of the template directory.

.. Hint:: Make sure, you have inserted the correct page id for "Default storage PID"!


