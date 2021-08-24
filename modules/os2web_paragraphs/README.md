#OS2 paragraphs
The base module, OS2 paragraphs contains a variety of submodules each providing their own paragraphs.

## How to use
Set up a content type. Create a field of type "Reference revision: paragraph (paragraph reference)".
This field should only be able to choose the paragraph type Wrapper. Set the limitation of this field to "unlimited".

## Base module
The base module provides a wrapper paragraph, which serves as the outer layer.
This module does not provide any other paragraphs. Enable the submodules to provide extra functionality.

The base module's only purpose is to provide default functionality for the submodules.

This module provides the following paragraphs:
- wrapper
- row with 2 columns
- row with 3 columns

## Submodules
A set of submodules is provided.

The following submodules are available, each providing their own paragraph types
  - accordion - set of paragraph to show content grouping as accordion
  - banner - paragraph for render banner image
  - box - paragraph with content in colored box
  - contact_form - reference to contact form entity
  - contact - paragraph with field set for contact section
  - content_reference - paragraph with multiple content reference field
  - factbox - paragraph with field set for factabox section
  - files - paragraph with multiple file field
  - gis_map - shows gis map iframe
  - iframe - paragraph with iframe code field
  - image - simple paragraph with an image field
  - keyword - paragraph with taxonomy term reference to Keywords vocabulary
  - menu_links - shows menu links
  - section - shows taxonomy terms links
  - simple_text - "simple text" paragraph
  - slideshow - paragraph with multiple image field
  - twi - text with image
  - webform - webform reference
