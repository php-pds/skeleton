# pds/skeleton

This publication describes a standard filesystem skeleton suitable for all PHP
packages.

Please see <https://github.com/php-pds/skeleton_research> for background
information.

Command-line tools included with this standard are documented [here](./docs/tools.md).

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this publication are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

## Summary

A package MUST use these names for these root-level directories:

| If a package has a root-level directory for ... | ... then it MUST be named: |
| ----------------------------------------------- | -------------------------- |
| command-line executables                        | `bin/`                     |
| configuration files                             | `config/`                  |
| documentation files                             | `docs/`                    |
| web server files                                | `public/`                  |
| other resource files                            | `resources/`               |
| PHP source code                                 | `src/`                     |
| test code                                       | `tests/`                   |

A package MUST use these names for these root-level files:

| If a package has a root-level file for ...      | ... then it MUST be named: |
| ----------------------------------------------- | -------------------------- |
| a log of changes between releases               | `CHANGELOG(.*)`            |
| guidelines for contributors                     | `CONTRIBUTING(.*)`         |
| licensing information                           | `LICENSE(.*)`              |
| information about the package itself            | `README(.*)`               |

A package SHOULD include a root-level file indicating the licensing and
copyright terms of the package contents.

## Root-Level Directories

### bin/

If the package provides a root-level directory for command-line executable
files, it MUST be named `bin/`.

This publication does not otherwise define the structure and contents of the
directory.

### config/

If the package provides a root-level directory for configuration files, it MUST
be named `config/`.

This publication does not otherwise define the structure and contents of the
directory.

### docs/

If the package provides a root-level directory for documentation files, it MUST
be named `docs/`.

This publication does not otherwise define the structure and contents of the
directory.

### public/

If the package provides a root-level directory for web server files, it MUST be
named `public/`.

This publication does not otherwise define the structure and contents of the
directory.

> N.b.: This directory MAY be intended as a web server document root.
> Alternatively, it MAY be that the files will be served dynamically via other
> code, copied or symlinked to the "real" document root, or otherwise managed so
> that they become publicly available on the web.

### resources/

If the package provides a root-level directory for other resource files, it MUST
be named `resources/`.

This publication does not otherwise define the structure and contents of the
directory.

### src/

If the package provides a root-level directory for PHP source code files, it
MUST be named `src/`.

This publication does not otherwise define the structure and contents of the
directory.

### tests/

If the package provides a root-level directory for test files, it MUST be named
`tests/`.

This publication does not otherwise define the structure and contents of the
directory.

### Other Directories

The package MAY contain other root-level directories for purposes not described
by this publication.

This publication does not define the structure and contents of the other
root-level directories.

## Root-Level Files

### CHANGELOG

If the package provides a root-level file with a list of changes since the last
release or version, it MUST be named `CHANGELOG`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### CONTRIBUTING

If the package provides a root-level file that describes how to contribute to
the package, it MUST be named `CONTRIBUTING`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### LICENSE

Whereas package consumers might be in violation of copyright law when copying
unlicensed intellectual property, the package SHOULD include a root-level file
indicating the licensing and copyright terms of the package contents.

If the package provides a root-level file indicating the licensing and copyright
terms of the package contents, it MUST be named `LICENSE`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### README

If the package provides a root-level file with information about the package
itself, it MUST be named `README`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### Other Files

The package MAY contain other root-level files for purposes not described in
this publication.

This publication does not define the structure and contents of the other
root-level files.
