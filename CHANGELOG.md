GLS PARCEL BUILDER
==================

May. 04, 2016 - v0.2.0
--------------------------

This release contain additional information on PDF Generator parcel

 * Additional recipient information add on parcel bottom, as the contact name, 
   mobile phone, extra reference.
   
 * Put the comment parameter just below the address2 parameter, and 
   if the address2 is greater than 35 characters, the rest of string 
   are put in front of comment.

May. 02, 2016 - v0.1.3
--------------------------

This release was fix for rebuild origin parameter

 * Origin parameter must be unique and as used for package identification.
   he must be set by user land.

Apr. 27, 2016 - v0.1.2
--------------------------

This release contains the fix for DATAMATRIX origin parameter.

 * Change format of incrementPackageNumber by 10 digit long instead 14
 * Move the matrix padding space into `Response` class
 * Clean code of couples php Notice
 * First version of CHANGELOG.md file


Apr. 26, 2016 - v0.1.1
--------------------------

Fix unit test for sucess build on ContinuousPHP test integration.

