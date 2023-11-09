
                           Swiss 721 Font Installation

The font file is capable of functioning within XiIIIPlus, Z4Mplus/Z6Mplus, PAX4,
and 105SL printers.

NOTE:  Print a copy of this README file, and refer to it
       during the font file installation.


System requirements:  WINDOWS or DOS based P.C.
                      3 MBytes free hard drive space

The 77849_002.ZSU file is the Font file which needs to be downloaded to the printer
using one of the methods below.  This file will create TT0003M_.FNT within the E: memory of the printer.

The file tt0003m_.ttf is the file used to optimize your on-screen display of the Swiss 721 font.

The file 13656L-001_R1.pdf is the instruction file for copying the on-screen display file tt0003m_.ttf
to its correct location.  The available instructions are for installation from CD but can be used
for any location the tt0003m_.ttf file resides in.

Directions for uploading from the Windows environment:

1. Make sure the printer is ON and ready to accept data.

2. Save the file (77849_002.EXE) downloaded from www.zebra.com to
   an appropriate working directory on the hard drive.

3. From a File Manager, double click on the 77849_002.EXE file
   name.  

4. The new files will be created within the same directory as the original .exe file. 
   (The 77849_002.ZSU is the file which needs to be sent to the printer.)

6. Open a DOS Window and change to the directory containing the
   77849_002.ZSU file.  For printers with a Network connection it is also possible to use the copy & paste
   functionality of ZebraNet Bridge.

7. Copy the file (77849_002.ZSU) directly to the printer.

***Instructions below include examples for Parallel, Network or Serial(slowest option)
   download options

   Parallel
   To send file through the parallel port, type:

   	COPY 77849_002.ZSU LPT1
   
   Network
   To send file through a network connection via FTP File Transfer Protocol from DOS
	
	1.)    Go to a DOS prompt and change to the directory where the firmware file is stored. 

	2.)    From the DOS prompt Type: ftp <space> <ip address of the destination printer> <enter> 

	3.)    Press <enter>  again. 

	4.)    You will then see the FTP prompt:   FTP> 

	5.)    Type: put <space> <filename> <enter> 

	The file is now sent to its destination. 

	Example: 

	put 77849_002.ZSU <enter> 


		Note:  FTP is case sensitive, use lower case… 
 
   Serial  
   To send the file though a com port, COM1 for example, you need to set 
   the communications parameters on the port first, then copy the file:

   	1.)	Type: MODE COM1 57600,N,8,1,p (for Hardware handshaking)

   		      OR

   		      MODE COM1 57600,N,8,1,X (for Xon/Xoff)

   	2.)   	Type: COPY 77849_002.ZSU COM1 
   
 

   NOTE:  For quicker uploading, use the P.C.'s parallel
          printer port.  Sending data through the serial port will take 
          considerably longer.

8. Once the file download to the printer is complete LIST FONTS from the Printer's 
   Front Panel can be used to confirm that the TT0003M_.FNT file was downloaded successfully.
 


