<?php
  
  function attachment_url_to_path($url)
  {
    $parsed_url = parse_url($url);
    if (empty($parsed_url['path'])) return false;
    $file = ABSPATH . ltrim($parsed_url['path'], '/');
    if (file_exists($file)) return $file;
    return false;
  }
  
  function check_and_insert_filesize(string $content): string
  {
    // Create an array of allowed file extensions
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'ico', 'webp', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', 'psd', 'xml', 'zip', 'rar', '7z'];
    // Initialize DOMDocument
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Suppress warnings
    // Load the content HTML
    //    $dom->loadHTML($content);
    $dom->loadHTML(mb_encode_numericentity($content, [0x80, 0x10FFFF, 0, ~0], 'UTF-8'));
    // Get all <a> tags
    $links = $dom->getElementsByTagName('a');
    
    foreach ($links as $link) {
      $href = $link->getAttribute('href');
      // Check if the link contains a valid file extension
      $extension = strtolower(pathinfo($href, PATHINFO_EXTENSION));
      if (in_array($extension, $allowed_extensions)) {
        
        $fileURLABS = attachment_url_to_path($href);
        $temp_file = download_url($href);
        $file_size = wp_filesize($fileURLABS);
        // Format the file size
        $formatted_size = size_format($file_size, 2);
        // Create a <span> element with the file size
        $span = $dom->createElement('span', '(' . $formatted_size . ', ' . $extension . ')');
        $span->setAttribute('class', 'ms-2');
        
        // Insert the <span> element after the <a> tag
        $link->appendChild($span);
      }
    }
    // Save the modified content
    return $dom->saveHTML();
  }