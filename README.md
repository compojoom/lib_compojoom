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

## COPYRIGHT AND DISCLAIMER
Compojoom library -  Copyright (c) 2008-2013 Compojoom.com

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the
Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/.
