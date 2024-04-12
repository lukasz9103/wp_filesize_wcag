# WCAG - file size and extension check
Functions that determine the sizes and extensions of files included in the WordPress website content, e.g. in the classic editor, which are required for WCAG compliance


## How to use:

Copy the code from ```wcage_filesize.php``` file or import ```wcage_filesize.php``` into functions.php

```php
  include_once('includes/wcag_filesizes.php');
```
#### Use:

```php
    ob_start();
    the_content();
    $content = ob_get_clean();
    if ($content) :
      $modified_content = check_and_insert_filesize($content);
      echo $modified_content;
    endif;
```

**For example:**

```php
<?php
    if (have_posts()) :
      while (have_posts()) : the_post(); ?>
        <h2><?php the_title(); ?></h2>
        <?php
        ob_start();
        the_content();
        $content = ob_get_clean();
        if ($content) :
          $modified_content = check_and_insert_filesize($content);
          echo $modified_content;
        endif;
        ?>
      <?php endwhile; ?>
<?php endif; ?>
```

Created by: [@lukasz9103](https://github.com/lukasz9103)