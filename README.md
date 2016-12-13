# pds/skeleton

This publication describes a standard filesystem skeleton suitable for all PHP
packages.

Please see <https://github.com/php-pds/skeleton_research> for background information.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this publication are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

## Summary

A package MUST use the following names for the following kinds of directories
and files:

```
bin/            # command-line executables
config/         # configuration files
docs/           # documentation and examples
public/         # web server files
src/            # source code
tests/          # test code
vendor/         # reserved for package managers
CHANGELOG       # a log of changes between releases
CONTRIBUTING    # guidelines for contributors
LICENSE         # licensing information
README          # information about the package itself
```

## Root-Level Directories

### bin/

If the package provides a directory for command-line executable files, it MUST
be named `bin/`.

This publication does not otherwise define the structure and contents of the
directory.

### config/

If the package provides a directory for configuration files, it MUST be named
`config/`.

This publication does not otherwise define the structure and contents of the
directory.

### docs/

If the package provides a directory for documentation files, it MUST be named
`docs/`.

This publication does not otherwise define the structure and contents of the
directory.

### public/

If the package provides a directory for web server files, it MUST be named
`public/`.

This publication does not otherwise define the structure and contents of the
directory.

> N.b.: This directory MAY be intended as a web server document root.
> Alternatively, it MAY be that the files will be served dynamically via other
> code, copied or symlinked to the "real" document root, or otherwise managed so
> that they become publicly available on the web.

### src/

If the package provides a directory for source code files, it MUST be named
`src/`.

This publication does not otherwise define the structure and contents of the
directory.

### tests/

If the package provides a directory for test files, it MUST be named `tests/`.

This publication does not otherwise define the structure and contents of the
directory.

### vendor/

The `vendor/` directory MUST be reserved for use by package managers (e.g.:
Composer).

It MUST be ignored by revision control tools (e.g.: Git, Mercurial, Subversion,
etc.).

It MUST NOT be committed to revision control repositories.

This publication does not otherwise define the structure and contents of the
directory.

### Other Directories

The package MAY contain other root-level directories for purposes not described
by this publication.

This publication does not define the structure and contents of other root-level
directories.

## Root-Level Files

### CHANGELOG

If the package provides a file with a list of changes since the last release or
version, it MUST be named `CHANGELOG`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### CONTRIBUTING

If the package provides a file that describes how to contribute to the package,
it MUST be named `CONTRIBUTING`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### LICENSE

If the package provides a file with licensing information, it MUST be named
`LICENSE`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### README

If the package provides a file with information about the package itself, it
MUST be named `README`.

It MAY have a lowercase filename extension indicating the file format.

This publication does not otherwise define the structure and contents of the
file.

### Other Files

The package MAY contain other root-level files for purposes not described in
this publication.

This publication does not otherwise define the structure and contents of the
other root-level files.
