Compojoom - Utilities library for Joomla
=======================================================

The goal of this library is to ease the development process by abstracting commonly used
functions in one place.

## USAGE##

Add the following line to your code:

```
require_once JPATH_LIBRARIES . '/compojoom/include.php';
```

## Building the package from github
In order to build the installation packages of this library you need to have
the following tools:

- A command line environment. Bash under Linux / Mac OS X . On Windows
  you will need to run most tools using an elevated privileges (administrator)
  command prompt.
- The PHP CLI binary in your path

- Command line Subversion and Git binaries(*)

- PEAR and Phing installed, with the Net_FTP and VersionControl_SVN PEAR
  packages installed

You will also need the following path structure on your system

- lib_compojoom - This repository
- buildtools - Compojoom build tools (https://github.com/compojoom/buildtools)

## Using the Avatar & Profile support
In your component config you can create the following fields:

```
<fieldset name="integrations" label="Integrations"
	          addfieldpath="/libraries/compojoom/form/fields">
  <field name="support_avatars" type="avatars"
         default="0"
         isPro="@@PRO@@"
         label="LIB_COMPOJOOM_SUPPORT_AVATARS_LABEL" description="LIB_COMPOJOOM_SUPPORT_AVATARS_DESC" />
  <field name="profile_link" label="LIB_COMPOJOOM_SUPPORT_PROFILES_LABEL"
         description="LIB_COMPOJOOM_SUPPORT_PROFILES_DESC"
         isPro="@@PRO@@"
         type="profiles" default="" />
</fieldset>
```

Note: the fieldset has an addfieldpath -> this would fetch the fields from that location.
The isPRO attribute determines if all available options should be made available for selection. 
Generally in Core extensions we don't want to make those fields available. 

Now to actually use the system in your php files you need to do the following:

```php
$avatarSystem = CompojoomAvatars::getInstance($system);
$avatars = $avatarSystem->getAvatars($users);
```
$system is the avatar system name. Generally the value from: support_avatars in your config.<br>
$users is an array with user ids

To use the profile system in php files you need to do the following

```php
$profileSystem = CompojoomProfiles::getInstance($profile);
$link = $profileSystem->getLink($id);
```
$profile is the profile system name. Generally the value from profile_link in your config<br>
$id is the user id that we are generating the link for

## COPYRIGHT AND DISCLAIMER
Compojoom library -  Copyright (c) 2008-2015 Compojoom.com

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the
Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/.
